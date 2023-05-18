<?php

namespace App\Validation;

use Illuminate\Http\Request;

class SampleValidation
{
    static public function index(Request $request)
    {
        return $request->validate(['project_id' => 'integer']);
    }

    static public function store(Request $request)
    {
        return $request->validate([
            'project_id' => 'required|integer',
            'sample_texts' => 'required|array',
            'sample_texts.*.text' => 'required|string'
        ]);
    }

    static public function update(Request $request)
    {
        return $request->validate([
            'sample_texts' => 'array|required',
            'sample_texts.*.id' => 'integer|required',
            'sample_texts.*.text' => 'string|required',
        ]);
    }

    static public function show(Request $request)
    {
        $fields =  $request->validate([
            'with_label_sets' => 'string',
            'with_entities' => 'string',
            'with_generated_texts' => 'string',
        ]);
        return [
            'with_label_sets' => ($fields['with_label_sets'] ?? 'false') === 'true',
            'with_entities' => ($fields['with_entities'] ?? 'false') === 'true',
            'with_generated_texts' => ($fields['with_generated_texts'] ?? 'false') === 'true',
        ];
    }

    static public function annotateSample(Request $request)
    {
        $rules = [];
        if (array_key_exists('entity_recognition', $request->all())) {
            $rules['entity_recognition'] = 'array|required';
            $rules['entity_recognition.*.sample_text_id'] = 'integer|required';
            $rules['entity_recognition.*.entity_id'] = 'integer|required';
            $rules['entity_recognition.*.start'] = 'integer|required';
            $rules['entity_recognition.*.end'] = 'integer|required';
        }
        if (array_key_exists('generated_texts', $request->all())) {
            $rules['generated_texts'] = 'array|required';
            $rules['generated_texts.*'] = 'string|required';
        }
        if (array_key_exists('labeling', $request->all())) {
            // Keys are label_set_id
            // Values are corresponding label_ids
            $rules['labeling'] = 'array|required';
            $rules['labeling.*'] = 'array|required';
        }

        return $request->validate($rules);
    }
}
