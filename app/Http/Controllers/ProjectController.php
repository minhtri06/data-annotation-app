<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        ]);
        $project = Project::create($fields);
        return $request;
        return response(201, ['data' => ['project' => $project]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
