<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'maximum_performer',
        'project_type_id',
    ];

    public function project_type()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function label_sets()
    {
        return $this->belongsToMany(LabelSet::class, 'project_label_set')
            ->using(ProjectLabelSet::class)->withPivot('pick_one')->withTimestamps();
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
