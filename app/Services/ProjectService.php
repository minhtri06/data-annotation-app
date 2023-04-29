<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Project;
use App\Models\User;
use \Illuminate\Database\QueryException;

class ProjectService
{
    static public function assignUsersToProject($project_id, $user_ids)
    {
        $project = Project::find($project_id);
        if ($project == null) {
            throw ApiException::NotFound("Project not found");
        }
        try {
            $project->assigned_users()->attach($user_ids);
        } catch (QueryException $exception) {
            throw ApiException::BadRequest($exception->errorInfo[2]);
        }
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

    static public function getProjectById($id, $query_options, $user)
    {
        $project_query = Project::query();

        $project_query->with('project_type');

        if ($query_options['with_samples'] ?? false) {
            $project_query->with('samples', 'samples.sample_texts');
        }

        if (
            ($query_options['with_assigned_users'] ?? false) &&
            ($user->role == 'manager' || $user->role == 'admin')
        ) {
            $project_query->with('assigned_users');
        }

        if ($user->role == 'annotator') {
            $project_query->whereHas('assignment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $project = $project_query->find($id);
        if ($project == null) {
            throw ApiException::NotFound("Project not found");
        }

        if ($project->has_label_sets) {
            $project_query->with('label_sets', 'label_sets.labels');
        }
        if ($project->has_entity_recognition) {
            $project_query->with('entities');
        }

        return $project_query->find($id);
    }

    static public function getProjects($query_options, $user)
    {
        $project_query = Project::query();

        if (array_key_exists('project_type_id', $query_options)) {
            $project_query->where('project_type_id', $query_options['project_type_id']);
        }

        if (
            ($query_options['with_assigned_users'] ?? false) &&
            ($user->role == 'manager' || $user->role == 'admin')
        ) {
            $project_query->with('assigned_users');
        }

        if ($user->role == 'annotator') {
            $project_query->whereHas('assignment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return $project_query->orderBy('id', 'desc')->get();
    }

    static public function getUnassignUsersOfProject($project_id)
    {
        return User::whereDoesntHave(
            'assignment',
            function ($query) use ($project_id) {
                $query->where('project_id', $project_id);
            }
        )->get();
    }

    static public function updateProject($id, $update_body)
    {
        $project = Project::find($id);

        if ($project == null) {
            throw ApiException::NotFound('Project not found');
        }

        if (array_key_exists('maximum_performer', $update_body)) {
            if ($project->samples->max('number_of_performer') > $update_body['maximum_performer']) {
                throw ApiException::BadRequest(
                    "number_of_performer > maximum_performer! Please check again"
                );
            }
        }

        $project->update($update_body);
        $project->save();

        return $project;
    }

    static public function deleteProject($id)
    {
        $project = Project::find($id);

        if ($project == null) {
            throw ApiException::NotFound('Project not found');
        }

        $project->delete();
    }
}
