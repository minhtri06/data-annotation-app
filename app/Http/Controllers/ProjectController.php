<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;

use App\Services\ProjectService as Service;

use App\Validation\ProjectValidation as Validation;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $query = Validation::index($request);
        $projects = Service::getProjects($query, $user);
        return response(['project' => $projects], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project_body = Validation::store($request);
        $new_project = Service::createProject($project_body);
        return response(['project' => $new_project], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $query = Validation::show($request);
        $project = Service::getProjectById($id, $query, $user);
        return response(['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project_body = Validation::update($request);
        $update_project = Service::updateProject($id, $project_body);
        return response(['project' => $update_project], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Service::deleteProject($id);
        return response(['message' => "Delete project successfully"]);
    }

    public function assignUsersToProject(Request $request, $project_id)
    {
        $fields = Validation::assignUsersToProject($request);
        Service::assignUsersToProject($project_id, $fields['user_ids']);
    }

    public function getUnassignedUsers($project_id)
    {
        $unassignedUsers = Service::getUnassignUsersOfProject($project_id);
        return response(['unassigned_users' => $unassignedUsers]);
    }
}
