<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'owner_id',
        'recipient_id',
        'token',
        'access_level',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    const UPDATED_AT = null; // No actualizamos este campo

    // Relaciones
    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
