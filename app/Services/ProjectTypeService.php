<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Sample;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProjectTypeService
{
    static public function getAllProjectTypes()
    {
        return ProjectType::orderBy('id', 'desc')->get();
    }

    static public function getProjectById($id)
    {
        $project_type = ProjectType::find($id);
        if ($project_type == null) {
            throw ApiException::NotFound("Project type not found");
        }
        return $project_type;
    }

    static public function createProjectType($project_type_body)
    {
        if (ProjectType::where('name', $project_type_body['name'])->first() != null) {
            throw ApiException::BadRequest("Project type's name already exists");
        }
        return ProjectType::create($project_type_body);
    }

    static public function updateProjectTypeById($id, $update_body)
    {
        $project_type = ProjectType::find($id);
        if ($project_type == null) {
            throw ApiException::NotFound("Project type not found");
        }

        if (array_key_exists("name", $update_body)) {
            if (ProjectType::where('name', $update_body['name'])->where('id', '!=', $id)->first() != null) {
                throw ApiException::BadRequest("Project type's name already exists");
            }
        }

        $project_type->update($update_body);

        return $project_type;
    }

    static public function deleteProjectTypeById($id)
    {
        $project_type = ProjectType::find($id);
        if ($project_type == null) {
            throw ApiException::NotFound("Project type not found");
        }

        if (count($project_type->projects) != 0) {
            throw ApiException::BadRequest(
                "Can not delete project type because it has projects"
            );
        }

        $project_type->delete();
    }
}
