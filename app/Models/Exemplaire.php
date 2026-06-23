<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exemplaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'livre_id',
        'code',
        'statut'
    ];

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function emprunts()
{
    return $this->hasMany(Emprunt::class);
}
}