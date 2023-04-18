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
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(['projects' => Project::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project_body = ProjectService::validateNewProjectFromRequest($request);

        $new_project = ProjectService::createProject($project_body);
        return response(['project' => $new_project], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {

        /** @var \App\Models\User $user **/
        $user = auth()->user();

        if ($request->query()['with_samples'] ?? null) {
            $withs[] = 'samples';
        }
        if (($request->query()['with_assigned_users'] ?? null) &&
            ($user->role == 'admin' || $user->role == 'manager')
        ) {
            $withs[] = 'assigned_users';
        }

        $project = Project::with($withs)->find($id);

        if ($project == null) {
            throw ApiException::NotFound("Project not found");
        }

        if ($user->role == 'annotator') {
            if (Assignment::where(['user_id' => $user->id, 'project_id' => $project->id])->first() == null) {
                throw ApiException::NotFound("Project not found");
            }
        }

        return response(['project' => $project], 200);
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
        $project = Project::find($id);

        if ($project == null) {
            throw ApiException::NotFound("Project not found");
        }

        Project::destroy($project->id);

        return response(['project' => $project], 200);
    }
}
