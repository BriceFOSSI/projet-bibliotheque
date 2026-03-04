<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages'; // le nom de la table

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
    ];
}
