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
        'has_label_sets',
        'has_entity_recognition',
        'has_generated_text',
        'number_of_generated_texts',
        'maximum_of_generated_texts',
        'number_of_texts',
        'text_titles',
        'generated_text_titles',
        'maximum_performer',
    ];

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }

    public function label_sets()
    {
        return $this->hasMany(LabelSet::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'project_id');
    }

    public function assigned_users()
    {
        return $this->belongsToMany(User::class, 'assignment')
            ->using(Assignment::class)->withTimestamps();
    }
}
