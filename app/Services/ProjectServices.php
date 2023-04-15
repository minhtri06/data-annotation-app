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
            'entities' => 'array|nullable'
        ]);
        if (
            array_key_exists('label_sets', $fields) && !$fields['has_label_sets']
        ) {
            throw ApiException::badRequest(
                "Got 'label_sets' in request but 'has_label_sets' is false"
            );
        }
        if (array_key_exists('entities', $fields) && !$fields['has_entity_recognition']) {
            throw ApiException::badRequest(
                "Got 'entities' in request but 'has_entity_recognition' is false"
            );
        }
        return $fields;
    }
}
