<?php


namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Label;
use App\Models\LabelSet;

class LabelSetService
{
    static public function createLabelSet($label_set_body)
    {
        $new_label_set = LabelSet::create([
            'pick_one' => $label_set_body['pick_one'],
            'project_id' => $label_set_body['project_id']
        ]);

        if ($label_set_body['labels'] ?? null) {
            $labels = [];
            foreach ($label_set_body['labels'] as $label) {
                $labels[] = Label::create([
                    'label' => $label, 'label_set_id' => $new_label_set->id
                ]);
            }
            $new_label_set['labels'] = $labels;
        }

        return $new_label_set;
    }

    /**
     * Create multiple label sets that belong to a project
     */
    static public function createLabelSetsOfProject($project_id, $label_set_bodies)
    {
        $labe_sets = [];
        foreach ($label_set_bodies as $label_set_body) {
            $label_set_body['project_id'] = $project_id;

            $labe_sets[] = LabelSetService::createLabelSet($label_set_body);
        }
        return $labe_sets;
    }
}
