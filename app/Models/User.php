<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function sharedLinksOwned()
    {
        return $this->hasMany(SharedLink::class, 'owner_id');
    }

    public function sharedLinksReceived()
    {
        return $this->hasMany(SharedLink::class, 'recipient_id');
    }
}

