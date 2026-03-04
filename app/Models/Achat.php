<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Achat extends Model
{
    protected $fillable = ['user_id', 'total', 'methode_paiement'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livres()
    {
        return $this->belongsToMany(Livre::class, 'achat_livre')
                    ->withPivot('quantite', 'prix_unitaire');
    }
}
