<?php

namespace App\Models;

use Illuminate\Contracts\Queue\EntityResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleText extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'sample_id',
    ];

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

    public function entity_recognition()
    {
        $this->hasMany(EntityRecognition::class);
    }
}
