<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Assignment extends Pivot
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
