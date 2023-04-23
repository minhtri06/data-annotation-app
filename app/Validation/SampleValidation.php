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
            'texts' => 'required|array',
            'texts.*' => 'required|string'
        ]);
    }
}
