<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'pick_one', 'project_id'
    ];

    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }
}
