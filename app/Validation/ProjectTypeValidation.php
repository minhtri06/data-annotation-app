<?php

namespace App\Validation;

use Illuminate\Http\Request;

class ProjectTypeValidation {
    static public function store(Request $request)
    {
        return $request->validate(['name' => 'required|string']);
    }

    static public function update(Request $request)
    {
        return $request->validate(['name' => 'string|nullable']);
    }
}