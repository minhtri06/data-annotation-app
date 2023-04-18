<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;

use App\Models\Project;

class ProjectService
{
    static function validateNewProjectFromRequest(Request $request)
    {
        $project_body = $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'has_label_sets' => 'required|boolean',
            'has_entity_recognition' => 'required|boolean',
            'number_of_texts' => 'required|integer|min:1',
            'text_titles' => 'required|string',
            'has_generated_text' => 'required|boolean',
            'number_of_generated_texts' => 'integer|nullable',
            'maximum_of_generated_texts' => 'integer|nullable',
            'generated_text_titles' => 'string|nullable',
            'maximum_performer' => 'required|integer',
            'label_sets' => 'array|nullable',
            'entities' => 'array|nullable',
            'project_type_id' => 'required|integer'
        ]);
        if (array_key_exists('label_sets', $project_body)) {
            if ($project_body['has_label_sets'] == false) {
                throw ApiException::BadRequest("Got 'label_sets' but 'has_label_sets' is false");
            }
            $request->validate([
                'label_sets' => 'array',
                'label_sets.*.pick_one' => 'required|boolean',
                'label_sets.*.labels' => 'required|array',
                'label_sets.*.labels.*' => 'required|string',
            ]);
        }
        if (array_key_exists('entities', $project_body)) {
            if ($project_body['has_entity_recognition'] == false) {
                throw ApiException::BadRequest("Got 'entities' but 'has_entity_recognition' is false");
            }
            $request->validate([
                'entities' => 'array',
                'entities.*.name' => 'required|string',
            ]);
        }
        return $project_body;
    }

    /**
     * Create a project
     * (Assume the fields is correctly formatted)
     */
    static public function createProject($project_body)
    {
        $new_project = Project::create($project_body);

        if ($project_body['label_sets'] ?? null) {
            $new_project['label_sets'] = LabelSetService::createLabelSetsOfProject(
                $new_project->id,
                $project_body['label_sets']
            );
        }
        if ($project_body['entities'] ?? null) {
            $new_project['entities'] = EntityService::createEntitiesOfProject(
                $new_project->id,
                $project_body['entities']
            );
        }
        return $new_project;
    }
}
