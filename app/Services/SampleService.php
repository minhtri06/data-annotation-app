<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Assignment;
use App\Models\Entity;
use App\Models\LabelSet;
use App\Models\Project;
use App\Models\Sample;

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
        $label_sets = LabelSet::where('project_id', $project_id)
            ->with('labels.labeling', function ($query) use ($sample_id, $user) {
                $query->where('sample_id', $sample_id);

                if ($user->role != 'admin' && $user->role != 'manager') {
                    $query->where('performer_id', $user->id);
                }
            })->get();

        if ($user->role != 'admin' && $user->role != 'manager') {
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

    static private function getEntitiesForSample($project_id, $sample_id, $user)
    {
        $entities = Entity::where('project_id', $project_id);
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
}
