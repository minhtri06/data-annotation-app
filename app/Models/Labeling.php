<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Labeling extends Pivot
{
    public function user()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }
}
