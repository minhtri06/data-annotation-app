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
}
