<?php

namespace App\Services;

use App\Exceptions\ApiException;
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
}
