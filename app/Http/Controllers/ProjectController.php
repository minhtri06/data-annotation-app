<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Entity;
use Illuminate\Http\Request;

use App\Services\ProjectServices;

use App\Models\Label;
use App\Models\LabelSet;
use App\Models\Project;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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
        $fields = ProjectServices::validateNewProjectFromRequest($request);

        // ['label_sets' => $label_sets, 'entities' => $entities] = $fields;
        // return [$label_sets, $entities];
        $new_project = Project::create($fields);

        if ($fields['label_sets'] ?? null) {
            $label_sets = $fields['label_sets'];
            foreach ($label_sets as $label_set) {
                ['pick_one' => $pick_one, 'labels' => $labels] = $label_set;

                $new_label_set = LabelSet::create([
                    'pick_one' => $pick_one,
                    'project_id' => $new_project->id
                ]);

                foreach ($labels as $label) {
                    Label::create([
                        'label' => $label, 'label_set_id' => $new_label_set->id
                    ]);
                }
            }
        }
        if ($fields['entities'] ?? null) {
            $entities = $fields['entities'];
            foreach ($entities as $entity) {

                Entity::create([
                    'name' => $entity,
                    'project_id' => $new_project->id
                ]);
            }
        }
        return response(['data' => ['project' => $new_project]], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return ApiException::NotFound();
        }
        return $project;
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

        if ($project) {
            return ApiException::NotFound();
        }
        return $project;
    }
}
