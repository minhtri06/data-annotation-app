<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'labeling')
            ->using(Labeling::class)->withPivot('pick_one')->withTimestamps();
    }

    public function sample_texts()
    {
        return $this->hasMany(SampleText::class);
    }

    public function generated_texts()
    {
        return $this->hasMany(GeneratedText::class);
    }
}
