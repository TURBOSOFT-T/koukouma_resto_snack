<?php

namespace App\Livewire\Commandes;

use App\Models\config;
use App\Models\contenu_commande;
use Livewire\Component;

class EditCommande extends Component
{
   

    public $commande,  $nom, $prenom, $adresse, $gouvernorat, $phone,$frais;

    public function mount($commande)
    {
        $this->commande = $commande;

        $this->frais = $commande->frais;
        $this->nom = $commande->nom;
        $this->prenom = $commande->prenom;
        $this->adresse = $commande->adresse;
        $this->gouvernorat = $commande->gouvernorat;
        $this->phone = $commande->phone;
    }

    public function render()
    {
       
        return view('livewire.commandes.edit-commande');
    }


    public function update_user_info()
    {
        $this->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'adresse' => 'required|string|max:150',
            'phone' => 'required|string|max:100',
         
            'frais'=> 'nullable',
        ]);
        $config = config::first();
        $this->commande->nom = $this->nom;
        $this->commande->prenom = $this->prenom;
        $this->commande->adresse = $this->adresse;
        $this->commande->phone = $this->phone;
       
        $this->commande->frais = $this->frais ? $config->frais : null;
        $this->commande->save();

        session()->flash('success', __('Les informations de la commandes ont été  modifiés !')); 
    }


    public function change($id_contenu, $quantite, $type)
    {
        $contenu = contenu_commande::find(intval($id_contenu));
        if (!$contenu) {
            //flash error message
            session()->flash('warning', 'Contenu non trouvé');
            return;
        }

        if ($type == "up") {
            //verification du stock
            if ($contenu->produit->stock < intval($quantite)) {
                //flash error message
                session()->flash('error', 'Quantité demandée excède le stock disponible pour ce produit');
                return;
            }
            $contenu->quantite =  intval($quantite);
            $contenu->produit->diminuer_stock(intval($quantite));
            $contenu->save();
        } else {
            //ajout d'un contenu à la commande
            $contenu->quantite =  intval($quantite);
            $contenu->produit->retourner_stock(intval($quantite));
            $contenu->save();
        }
    }

    public function delete($id)
    {
        $contenu = contenu_commande::find(intval($id));
        if (!$contenu) {
            //flash error message
            session()->flash('warning', 'Contenu non trouvé');
            return;
        }
        $contenu->delete();
        //fash mesage
        session()->flash('success', 'Le contenu a été supprimé de votre commande');

        $total = $this->commande->contenus->count();
        if ($total == 0) {
            //supprimer la commande
            $this->commande->delete();
            //redirection vers la page des commandes
            return redirect()->route('commandes')->with('success', '');
        }
    }
}
