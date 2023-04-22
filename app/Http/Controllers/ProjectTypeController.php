<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Services\ProjectTypeService as Service;
use App\Validation\ProjectTypeValidation as Validation;

use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project_types = Service::getAllProjectTypes();

        return response(['project_types' => $project_types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project_type_body = Validation::store($request);
        $new_project_type = Service::createProjectType($project_type_body);
        return response(['project_type' => $new_project_type], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project_type = Service::getProjectById($id);
        return response(['project_type' => $project_type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update_body = Validation::update($request);
        $project_type = Service::updateProjectTypeById($id, $update_body);
        return response(['project_type' => $project_type]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project_type = Service::deleteProjectTypeById($id);
        return response(['message' => 'Delete project type successfully']);
    }
}
