<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    public function sample_texts()
    {
        return $this->belongsToMany(SampleText::class)
            ->using(EntityRecognition::class)->withPivot('start')->withPivot('end')
            ->withTimestamps();
    }
}
