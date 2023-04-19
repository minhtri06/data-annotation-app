<?php

namespace App\Validation;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;

class ProjectValidation
{
    static function index(Request $request)
    {
        return $request->validate([
            'project_type_id' => 'integer'
        ]);
    }

    static function store(Request $request)
    {
        $project_body = $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'has_label_sets' => 'required|boolean',
            'has_entity_recognition' => 'required|boolean',
            'number_of_texts' => 'required|integer|min:1',
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
        if (array_key_exists('label_sets', $project_body)) {
            if ($project_body['has_label_sets'] == false) {
                throw ApiException::BadRequest("Got 'label_sets' but 'has_label_sets' is false");
            }
            $request->validate([
                'label_sets' => 'array',
                'label_sets.*.pick_one' => 'required|boolean',
                'label_sets.*.labels' => 'required|array',
                'label_sets.*.labels.*' => 'required|string',
            ]);
        }
        if (array_key_exists('entities', $project_body)) {
            if ($project_body['has_entity_recognition'] == false) {
                throw ApiException::BadRequest("Got 'entities' but 'has_entity_recognition' is false");
            }
            $request->validate([
                'entities' => 'array',
                'entities.*.name' => 'required|string',
            ]);
        }
        return $project_body;
    }

    static function update(Request $request)
    {
        $project_body = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'text_titles' => 'string',
            'generated_text_titles' => 'string|nullable',

            'maximum_performer' => 'integer|min:0',
        ]);
        return $project_body;
    }

    static function show(Request $request)
    {
        return $request->validate([
            'with_samples' => 'boolean',
            'with_assigned_users' => 'boolean'
        ]);
    }
}
