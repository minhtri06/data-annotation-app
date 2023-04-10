<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedText extends Model
{
    use HasFactory;

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
