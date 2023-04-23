<?php

namespace App\Services;

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
}
