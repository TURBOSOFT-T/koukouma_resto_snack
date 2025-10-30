<?php

namespace App\Livewire\Developper;

use App\Http\Traits\ListGouvernorats;
use App\Models\commandes;
use App\Models\contenu_commande;
use App\Models\notifications;
use App\Models\produits;
use Livewire\Component;

class CommanderTemplate extends Component
{
    public $nom, $telephone;
    use ListGouvernorats;
    public $template;

    public function mount($template)
    {
        $this->template = $template;
    }

    public function render()
    {
        $gouvernorats = $this->getListGouvernorat();
        return view('livewire.developper.commander-template', compact("gouvernorats"));
    }

    public function save()
    {
        // validation des champs
        $this->validate([
            'nom' => 'required|string',
            'telephone' => 'required|numeric',
        ], [
            'nom.required' => "Veuillez entrer votre nom complet",
            'telephone.required' => "Le numéro de téléphone est requis",
        ]);

        $telephone = $this->telephone;
        if (strlen($telephone) < 8) {
            $this->addError('telephone' , "Le numéro de téléphone doit contenir au moins 8 caractères.");
            return;
        }





        $quantite = 1;

        $commande = new commandes();
        $commande->nom = $this->nom;
        $commande->phone = $this->telephone;
        $commande->pays = "Tunisie";
        if ($commande->save()) {
            $produit = produits::find($this->template->produit->id);
            if ($produit) {
                $contenu = new contenu_commande();
                $contenu->id_commande = $commande->id;
                $contenu->id_produit = $produit->id;
                $contenu->quantite =  $quantite;
                $contenu->prix_unitaire =  $produit->getPrice();
                $contenu->benefice = ($produit->getPrice() - $produit->prix_achat) * $quantite;
                if ($contenu->save()) {
                    //diminuer le stock
                    $produit->diminuer_stock(intval($quantite));
                };
            } else {
                session()->flash('error', 'Produit introuvable !');
                return;
            }

            //NOTIFICATION

            //generate notification
            $notification = new notifications();
            $notification->titre = "Nouvelle commande.";
            $notification->url = "/admin/commande/" . $commande->id;
            $notification->message = "Commande passée par " . $this->nom;
            $notification->save();

            //stocker le produit dans une session
            session(['produit' => $produit]);

            //redirection
            return redirect()->route('confirmation');


            return;
        } else {
            //flash error message
            session()->flash('warning', 'Echec de la création de la commande.');
            return;
        }
    }
}
