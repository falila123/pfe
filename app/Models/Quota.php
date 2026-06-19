<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    protected $fillable = [
        'role',
        'max_jours',
        'max_livres',
    ];
}
