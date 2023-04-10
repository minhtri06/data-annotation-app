<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleText extends Model
{
    use HasFactory;

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'entity_recognition')
            ->using(EntityRecognition::class)->withPivot('start')->withPivot('end')
            ->withTimestamps();
    }
}
