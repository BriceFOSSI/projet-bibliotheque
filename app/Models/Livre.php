<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;

    protected $table = 'livres'; // le nom de la table

    protected $fillable = [
        'titre',
        'auteur',
        'categorie',
        'annee_publication',
        'resume',
        'prix',
    ];

    public function achats()
{
    return $this->belongsToMany(\App\Models\Achat::class, 'achat_livre', 'livre_id', 'achat_id')
                ->withPivot('quantite', 'prix_unitaire')
                ->withTimestamps();
}

}
