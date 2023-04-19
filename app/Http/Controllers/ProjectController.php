<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Entity;
use Illuminate\Http\Request;

use App\Services\ProjectService;

use App\Models\Label;
use App\Models\LabelSet;
use App\Models\Project;
use App\Models\Assignment;
use App\Models\Sample;
use App\Validation\ProjectValidation;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();

        $query = ProjectValidation::index($request);

        $projects = ProjectService::getProject($query, $user);

        return response(['project' => $projects], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project_body = ProjectValidation::store($request);

        $new_project = ProjectService::createProject($project_body);
        return response(['project' => $new_project], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();

        $query = ProjectValidation::show($request);
        $query['id'] = $id;

        $project = ProjectService::getProject($query, $user);
        if (count($project) == 0) {
            throw ApiException::NotFound('Project not found');
        }
        return response(['project' => $project[0]], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project_body = ProjectValidation::update($request);

        $update_project = ProjectService::updateProject($id, $project_body);
        return response(['project' => $update_project], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = ProjectService::deleteProject($id);

        return response(['project' => $project], 200);
    }
}
