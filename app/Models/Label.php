<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'label_set_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

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
