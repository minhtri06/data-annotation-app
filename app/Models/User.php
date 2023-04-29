<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function entity_recognition()
    {
        return $this->hasMany(EntityRecognition::class, 'performer_id');
    }

    public function generated_texts()
    {
        return $this->hasMany(GeneratedText::class, 'performer_id');
    }

    public function labeling()
    {
        return $this->hasMany(Labeling::class, 'performer_id');
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'user_id');
    }

    public function assigned_projects()
    {
        return $this->belongsToMany(Project::class, 'assignment')
            ->using(Assignment::class)->withTimestamps();
    }
}
