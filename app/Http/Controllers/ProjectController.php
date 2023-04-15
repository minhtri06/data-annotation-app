<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
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
        return $fields;
        ['label sets' => $label_sets, 'entities' => $entities] = $fields;

        $new_project = Project::create($fields);

        if ($label_sets) {
            foreach ($label_sets as $label_set) {
                [$pick_one, $labels] = $label_set;

                $new_label_set = LabelSet::create([
                    'pick_one' => $pick_one,
                    'project_id' => $new_project->id
                ]);

                if ($labels) {
                    foreach ($labels as $label) {
                        Label::create([
                            'label' => $label, 'label set id' => $new_label_set->id
                        ]);
                    }
                }
            }
        }
        return 'not yolo';
        // return response(['data' => ['project' => $project]], 201);
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
