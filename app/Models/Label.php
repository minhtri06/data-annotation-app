<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    public function label_set()
    {
        return $this->belongsTo(LabelSet::class);
    }

    public function samples()
    {
        return $this->belongsToMany(Sample::class)
            ->using(Labeling::class)->withTimestamps();
    }
}
