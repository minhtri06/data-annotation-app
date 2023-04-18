<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Entity;

class EntityService
{
    /**
     * Create one entity
     */
    static public function createEntity($entity_body)
    {
        return Entity::create([
            'name' => $entity_body['name'],
            'project_id' => $entity_body['project_id'],
        ]);
    }

    /**
     * Create multiple entities
     */
    static public function createEntitiesOfProject($project_id, $entity_bodies)
    {
        $entities = [];
        foreach ($entity_bodies as $body) {
            $body['project_id'] = $project_id;
            $entities[] = EntityService::createEntity($body);
        }
        return $entities;
    }
}
