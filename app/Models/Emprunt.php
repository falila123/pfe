<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    protected $fillable = [
        'livre_id',
        'user_id',
        'exemplaire_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut'
    ];

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exemplaire()
    {
        return $this->belongsTo(Exemplaire::class);
    }
}