<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }

    public function sample_texts()
    {
        return $this->belongsToMany(SampleText::class)
            ->using(EntityRecognition::class)->withPivot('start')->withPivot('end')
            ->withTimestamps();
    }

    public function entity_recognition()
    {
        return $this->hasMany(EntityRecognition::class);
    }
}
