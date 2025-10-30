<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class produits extends Model
{
    use HasFactory,SoftDeletes;
  
    protected $fillable = [
    
    'nom',
    'description',
   'reference',
'prix', 
   'prix_achat',
    'photo',
   'id_promotion',
  'category_id',
    'stock',
    'statut',
    'photos',
    'free_shipping  ',
    'top',
    ];
    protected $casts = [
        'photos' => 'json',
    ];
    

   public function vendus()
    {
        return $this->hasMany(contenu_commande::class, 'id_produit');
    }

    public function getPrice()
    {
        if ($this->id_promotion) {
            $promotion = promotions::find($this->id_promotion);
            if ($promotion) {
                $price = $this->prix - ($this->prix * ($promotion->pourcentage / 100));
                return $price;
            } else {
                return $this->prix;
            }
        } else {
            return $this->prix;
        }

    }

    public function inPromotion()
    {
        if ($this->id_promotion) {
            $promotion = promotions::find($this->id_promotion);
            if ($promotion) {
                return $promotion;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function diminuer_stock(int $quantite): void
    {
        if ($this->stock >= $quantite) {
            $this->stock -= $quantite;
            $this->save();
        }
    }

    public function retourner_stock(int $quantite): void
    {
        $this->stock += $quantite;
        $this->save();
    }
    

    public function historique_stock(){
        return $this->hasMany(historiques_stock::class, 'id_produit');
    }


    public function vues()
    {
        return $this->hasMany(views::class, 'id_produit');
    }


   

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function marques()
    {
        return $this->belongsTo(Marque::class, 'marque_id', 'id');
    }



}
