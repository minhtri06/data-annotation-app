<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;

use App\Models\Project;

class ProjectServices
{
    static function validateNewProjectFromRequest(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'has_label_sets' => 'required|boolean',
            'has_entity_recognition' => 'required|boolean',
            'number_of_texts' => 'required|integer',
            'text_titles' => 'required|string',
            'has_generated_text' => 'required|boolean',
            'number_of_generated_texts' => 'integer|nullable',
            'maximum_of_generated_texts' => 'integer|nullable',
            'generated_text_titles' => 'string|nullable',
            'maximum_performer' => 'required|integer',
            'label_sets' => 'array|nullable',
            'entities' => 'array|nullable',
            'project_type_id' => 'required|integer'
        ]);
        if (array_key_exists('label_sets', $fields)) {
            if ($fields['has_label_sets'] == false) {
                throw ApiException::badRequest("Got 'label_sets' but 'has_label_sets' is false");
            }
            $request->validate([
                'label_sets' => 'array',
                'label_sets.*.pick_one' => 'required|boolean',
                'label_sets.*.labels' => 'required|array',
                'label_sets.*.labels.*' => 'required|string',
            ]);
        }
        return $fields;
    }
}
