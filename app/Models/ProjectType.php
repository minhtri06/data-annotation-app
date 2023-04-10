<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'has_label_sets',
        'has_generated_texts',
        'has_entity_recognition',
        'text_titles',
        'generated_text_titles',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
