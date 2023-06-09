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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'entity_recognition')
            ->using(EntityRecognition::class)->withPivot('start', 'end', 'performer_id')
            ->withTimestamps();
    }

    public function entity_recognition()
    {
        return $this->hasMany(EntityRecognition::class);
    }
}
