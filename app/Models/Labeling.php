<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Labeling extends Pivot
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }
}
