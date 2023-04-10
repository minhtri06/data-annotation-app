<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSet extends Model
{
    use HasFactory;

    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_label_set')
            ->using(ProjectLabelSet::class)->withPivot('pick_one')->withTimestamps();
    }
}
