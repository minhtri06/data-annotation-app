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
            'number_of_texts' => 'required|integer|min:1',
            'text_titles' => 'required|string',
            'has_generated_text' => 'required|boolean',
            'number_of_generated_texts' => 'integer|nullable',
            'maximum_of_generated_texts' => 'integer|nullable',
            'generated_text_titles' => 'string|nullable',
            'maximum_performer' => 'required|integer',
            'label_sets' => 'array|nullable',
            'entities' => 'array|nullable'
        ]);
        if ($fields['label_sets']) {
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
        if ($fields['entities']) {
            if ($fields['has_entity_recognition'] == false) {
                throw ApiException::badRequest("Got 'entities' but 'has_entity_recognition' is false");
            }
            $request->validate([
                'entities' => 'array',
                'entities.*.name' => 'required|string',
            ]);
        }
        return $fields;
    }
}
