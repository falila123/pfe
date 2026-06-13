<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $fillable = [
        'titre',
        'cote',
        'isbn',
        'categorie',
        'sous_categorie_dewey',
        'type_livre',
        'langue',
        'couverture',
        'description',
    ];

    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class);
    }

    public function exemplaires()
{
    return $this->hasMany(Exemplaire::class);
}

public function emprunts()
{
    return $this->hasMany(Emprunt::class);
}
public function demandes()
{
    return $this->hasMany(Demande::class);
}

}