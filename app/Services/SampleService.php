<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Assignment;
use App\Models\Entity;
use App\Models\EntityRecognition;
use App\Models\GeneratedText;
use App\Models\Labeling;
use App\Models\LabelSet;
use App\Models\Project;
use App\Models\Sample;
use App\Models\SampleText;

class SampleService
{
    static public function getSamples($query_options, $user)
    {
        $sample_query = Sample::query();

        if (array_key_exists("project_id", $query_options)) {
            $sample_query->where('project_id', $query_options['project_id']);
        }

        if ($user->role != 'admin' && $user->role != 'manager') {
            $sample_query->whereHas('project.assignment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $sample_query->with('sample_texts');

        return $sample_query->get();
    }

    static public function createSample($sample_body)
    {
        $project = Project::find($sample_body['project_id']);
        if ($project == null) {
            throw ApiException::NotFound("project_id does not exist");
        }
        if ($project->number_of_texts != count($sample_body['sample_texts'])) {
            throw ApiException::BadRequest(
                "Number of sample_texts is not equal the number_of_text of project"
            );
        }
        $sample = $project->samples()->create([]);
        $sample->sample_texts()->createMany($sample_body['sample_texts']);
        return $sample;
    }

    static private function getLabelSetsForSample($project_id, $sample_id, $user)
    {
        $label_set_query = LabelSet::where('project_id', $project_id);
        if ($user->role == 'admin' || $user->role == 'manager') {
            $label_sets = $label_set_query->with(
                'labels.performers',
                function ($query) use ($sample_id) {
                    $query->wherePivot('sample_id', $sample_id);
                    $query->select('performer_id', 'name');
                }
            )->get();
        } else {
            $label_sets = $label_set_query->with(
                'labels.labeling',
                function ($query) use ($sample_id, $user) {
                    $query->where('sample_id', $sample_id);
                    $query->where('performer_id', $user->id);
                }
            )->get();

            foreach ($label_sets as $label_set) {
                $label_set['pick_one'] = $label_set['pick_one'] === 1;
                foreach ($label_set->labels as $label) {
                    $label->setAttribute(
                        'picked',
                        count($label->labeling) != 0 ? true : false
                    );
                    unset($label->labeling);
                }
            }
        }

        return $label_sets;
    }

    static public function getSampleById($sample_id, $user, $query_options)
    {
        $response = [];
        $sample_query = Sample::query()->where('id', $sample_id)->with('sample_texts');

        if ($query_options['with_entities']) {
            $sample_query->with('sample_texts.entities', function ($query) use ($user) {
                if ($user->role != 'admin' && $user->role != 'manager') {
                    $query->wherePivot('performer_id', $user->id);
                }
            });
        }

        if ($query_options['with_generated_texts']) {
            $sample_query->with('generated_texts', function ($query) use ($user) {
                if ($user->role != 'admin' && $user->role != 'manager') {
                    $query->where('performer_id', $user->id);
                }
            });
        }

        if ($user->role != 'admin' && $user->role != 'manager') {
            $sample_query->whereHas('project.assignment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $sample = $sample_query->first();
        if ($sample == null) {
            throw ApiException::NotFound('Sample not found');
        }
        $response['sample'] = $sample;

        if ($query_options['with_label_sets']) {
            $response['label_sets'] = SampleService::getLabelSetsForSample(
                $sample->project_id,
                $sample_id,
                $user
            );
        }

        if ($query_options['with_entities']) {
            $response['entities'] = Entity::where('project_id', $sample->project_id)
                ->select('id', 'name')->get();
        }

        return $response;
    }

    public static function updateSampleById($sample_id, $sample_texts)
    {
        $sample = Sample::with('sample_texts')->find($sample_id);
        if ($sample == null) {
            throw ApiException::NotFound('Sample not found');
        }
        for ($i = 0; $i < count($sample->sample_texts); $i++) {
            if ($sample->sample_texts[$i]->id != $sample_texts[$i]['id']) {
                throw ApiException::BadRequest('Sample text ids is not match');
            }
            $sample->sample_texts[$i]->text = $sample_texts[$i]['text'];
        }
        foreach ($sample->sample_texts as $sample_text) {
            $sample_text->save();
        }
        return $sample;
    }

    public static function deleteSampleById($sample_id)
    {
        $sample = Sample::query()->find($sample_id);
        if ($sample == null) {
            throw ApiException::NotFound('Sample not found');
        }
        $sample->delete();
    }

    private static function entityRecognize(
        $sample_id,
        $project_id,
        $performer_id,
        $entity_recognition
    ) {
        // Delete entity recognition of this performer for this sample if it exists
        EntityRecognition::where([
            'performer_id' => $performer_id
        ])->whereHas('sample_text', function ($query) use ($sample_id) {
            $query->where(['sample_id' => $sample_id]);
        })->delete();

        $project_entity_ids = Entity::where('project_id', $project_id)->pluck('id')
            ->toArray();
        $sample_text_ids = SampleText::where('sample_id', $sample_id)->pluck('id')
            ->toArray();
        foreach ($entity_recognition as $er) {
            if (!in_array($er['entity_id'], $project_entity_ids)) {
                throw ApiException::BadRequest(
                    "entity id {$er['entity_id']} is not belong to the sample's project"
                );
            }
            if (!in_array($er['sample_text_id'], $sample_text_ids)) {
                throw ApiException::BadRequest(
                    "sample text id {$er['sample_text_id']} is not belong to the sample"
                );
            }
        }
        foreach ($entity_recognition as $er) {
            $er['performer_id'] = $performer_id;
            EntityRecognition::create($er);
        }
    }

    public static function addGeneratedTexts($sample_id, $performer_id, $generated_texts)
    {
        // Delete previous generated texts of this performer for this sample if it exists
        GeneratedText::where([
            'sample_id' => $sample_id, 'performer_id' => $performer_id
        ])->delete();

        foreach ($generated_texts as $gen_text) {
            GeneratedText::create([
                'text' => $gen_text,
                'performer_id' => $performer_id,
                'sample_id' => $sample_id
            ]);
        }
    }

    public static function labeling($sample_id, $project_id, $performer_id, $labeling)
    {
        // Delete labeling of this performer for this sample if it exists
        Labeling::where([
            'sample_id' => $sample_id, 'performer_id' => $performer_id
        ])->delete();

        $add_labeling = [];
        foreach ($labeling as $label_set_id => $label_ids) {
            $label_set = LabelSet::find($label_set_id);

            // Check pick one 
            if ($label_set->pick_one && count($label_ids) > 1) {
                throw ApiException::BadRequest('Label set ' . $label_set_id . ' is pick one');
            }

            $allowed_label_ids = $label_set->labels->pluck('id')->toArray();
            foreach ($label_ids as $label_id) {
                if (!in_array($label_id, $allowed_label_ids)) {
                    throw ApiException::BadRequest('Label id ' . $label_id . ' is not allowed');
                }
                $add_labeling[] = [
                    'performer_id' => $performer_id,
                    'label_id' => $label_id,
                    'sample_id' => $sample_id
                ];
            }
        }

        foreach ($add_labeling as $al) {
            Labeling::create($al);
        }
    }

    public static function annotateSample($sample_id, $annotation_body, $user)
    {
        $sample_query = Sample::with('project')->where('id', $sample_id);

        $user_id = null;
        if ($user->role == 'annotator') {
            $sample_query->whereHas('project.assignment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $user_id = $user->id;
        } else {
            $user_id = $annotation_body['user_id'] ?? null;
            if ($user_id == null) {
                throw ApiException::BadRequest('userId is required');
            }
        }

        $sample = $sample_query->first();
        if ($sample == null) {
            throw ApiException::NotFound('Sample not found');
        }

        if (array_key_exists('entity_recognition', $annotation_body)) {
            if (!$sample->project->has_entity_recognition) {
                throw ApiException::BadRequest('Sample does not allow entity recognition');
            }
            SampleService::entityRecognize(
                $sample->id,
                $sample->project_id,
                $user_id,
                $annotation_body['entity_recognition']
            );
        }

        if (array_key_exists('generated_texts', $annotation_body)) {
            if (!$sample->project->has_generated_text) {
                throw ApiException::BadRequest('Sample does not allow generated text');
            }
            SampleService::addGeneratedTexts(
                $sample->id,
                $user_id,
                $annotation_body['generated_texts']
            );
        }

        if (array_key_exists('labeling', $annotation_body)) {
            if (!$sample->project->has_label_sets) {
                throw ApiException::BadRequest('Sample does not allow labeling');
            }
            SampleService::labeling(
                $sample->id,
                $sample->project_id,
                $user_id,
                $annotation_body['labeling']
            );
        }
    }
}
