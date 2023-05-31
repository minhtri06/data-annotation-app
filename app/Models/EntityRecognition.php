<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EntityRecognition extends Pivot
{
    protected $fillable = [
        'sample_text_id',
        'entity_id',
        'start',
        'end',
        'performer_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function sample_text()
    {
        return $this->belongsTo(SampleText::class);
    }
}
