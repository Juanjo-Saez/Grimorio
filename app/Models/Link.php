<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'note_id',
        'read_only',
        'expiry_date',
    ];

    public function note() {
        return $this->belongsTo(Note::class);
    }
}
