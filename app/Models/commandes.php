<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commandes extends Model
{
    use HasFactory;
    protected $table = 'commandes';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'adresse',
        'note',
        'avatar',
        'coupon',

            "phone",
           
            "pays",
            "gouvernorat",
            "frais", 
        'password',
        'user_id',
        'reference',
        'type_commande',
        'commercial_id'

        
    ];



    public function contenus()
    {
        return $this->hasMany(contenu_commande::class, 'id_commande');
    }

    public function montant(){
        $total = $this->frais;
        foreach ($this->contenus as $contenu){
            if($contenu->produit->free_shipping == true){
            $total += $contenu->prix_unitaire * $contenu->quantite - $this->frais  ;  
            }
            else{
                $total += $contenu->prix_unitaire * $contenu->quantite   ;
            }
        }
        return $total ?? 0;
    }

    public function client(){
        return $this->belongsTo(clients::class, 'phone','phone');
    }

    public function modifiable(){
        if ($this->statut === 'retournée' || $this->statut === 'payée' || $this->statut === 'livrée') {
            return false;
        } else {
            return true;
        }
        
    }

      public function commercial(){
        return $this->belongsTo(User::class ,'commercial_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
