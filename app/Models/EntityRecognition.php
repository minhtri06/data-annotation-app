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
        $this->belongsTo(User::class, 'performer_id');
    }

    public function entities()
    {
        $this->belongsTo(Entity::class);
    }

    public function sample_texts()
    {
        $this->belongsTo(SampleText::class);
    }
}
