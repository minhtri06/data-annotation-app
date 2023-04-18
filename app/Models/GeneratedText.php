<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedText extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'performer_id',
        'text',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
