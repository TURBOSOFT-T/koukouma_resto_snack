<?php

namespace App\Livewire\Commandes;


use App\Models\clients;
use App\Models\commandes;
use App\Models\config;
use App\Models\contenu_commande;
use App\Models\packs;
use App\Models\produits;
use Illuminate\Support\Str;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AjouterCommande extends Component
{
    public $key, $nom, $pays, $prenom, $frais, $adresse, $gouvernorat, $phone, $recherche, $clients = [], $table_id;
    public $quantites = [];
    public $produits = [];
    public $panier;
  



    public function updateKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }


    public function updatedRecherche($recherche)
    {
        if (strlen($recherche) > 0) {
            $this->clients = User::where('nom', 'like', '%' . $recherche . '%')
                ->orWhere('prenom', 'like', '%' . $recherche . '%')
                ->orWhere('phone', 'like', '%' . $recherche . '%')
                ->take(10)
                ->get();
        } else {
            $this->clients = [];
        }
    }

    public function import($client)
    {
        $this->nom = $client["nom"];
        $this->prenom = $client["prenom"];
        $this->adresse = $client["adresse"];
        $this->phone = $client["phone"];
     
        $this->recherche = "";
        $this->clients = [];

        //flash message
        session()->flash("message", "Client Importé avec succés");
    }



    public function ajouterProduit($produitId, $type, $reference)
    {
        $cartData = Session::get('panier', []);

        if (!is_array($cartData)) {
            $cartData = [];
        }

        $quantiteDemandee = max(1, $this->quantites[$produitId] ?? 1);
        $article = produits::find($produitId);

        if (!$article) {
            session()->flash('error', "Le produit n'existe pas.");
            return;
        }

        // Recherche du produit dans le panier
        $produitExistant = null;
        foreach ($cartData as $index => $item) {
            if ($item['id'] == $produitId && $item['type'] == $type && $item['reference'] == $reference) {
                $produitExistant = $index;
                break;
            }
        }

        if ($produitExistant !== null) {
            // Si le produit existe déjà, calculer la quantité totale demandée
            $quantiteTotale = $cartData[$produitExistant]['quantite'] + $quantiteDemandee;

            // Vérifier si le stock est suffisant
            if ($quantiteTotale <= $article->stock) {
                // Mettre à jour la quantité
                $cartData[$produitExistant]['quantite'] = $quantiteTotale;
            } else {
                session()->flash('error', "La quantité demandée pour l'article '$article->nom' dépasse le stock disponible.");
                return;
            }
        } else {
            // Vérifier si la quantité demandée est disponible en stock
            if ($quantiteDemandee <= $article->stock) {
                $cartData[] = [
                    "id" => $article->id,
                    "quantite" => $quantiteDemandee,
                    "type" => $type,
                    "nom" => $article->nom,
                    "prix" => $article->prix,
                    "reference" => $article->reference,
                ];
            } else {
                session()->flash('error', "La quantité demandée pour l'article '$article->nom' est indisponible.");
                return;
            }
        }

        // Réinitialiser la quantité à 1 après l'ajout/mise à jour
        $this->quantites[$produitId] = 1;
        $this->produits = [];
        Session::put('panier', $cartData);
    }





    public function render()
    {
        //recuperation du panier dans la session
        $paniers = session()->get('panier', []);



        if (!is_null($this->key)) {
            $produits = Produits::where('nom', 'like', '%' . $this->key . '%')->take(5)->get();

            $result = [];

            // Ajouter les produits au tableau result avec le type 'produit'
            foreach ($produits as $produit) {
                $result[] = [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'prix' => $produit->prix,
                    'reference' => $produit->reference,
                    'type' => 'produit'
                ];
            }



            $this->produits = $result;
        }


      
        $tables   = Table::all();

        return view('livewire.commandes.ajouter-commande', compact('paniers', 'tables'));
    }








    public function delete_from_session($produitId)
    {
        // Check if the product exists in the panier
        $cartData = Session::get('panier', []);

        if (!is_array($cartData)) {
            $cartData = [];
        }

        // Recherche du produit dans le panier
        foreach ($cartData as $index => $item) {
            if ($item['id'] == $produitId) {
                // Supprimer le produit trouvé
                unset($cartData[$index]);
                break;
            }
        }

        // Réinitialiser les clés du tableau après la suppression
        $cartData = array_values($cartData);

        // Mettre à jour la session panier
        Session::put('panier', $cartData);
    }


    public function order()
    {
        //validation du formulaire
        $data =    $this->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'nullable|string|max:100',
            //   'adresse' => 'required|string|max:150',
            'phone' => 'required|string|max:100',
            //  'pays' => 'required|string|max:100',
            //  'gouvernorat' => 'required|string|max:12',
            // 'table_id' => 'nullable|integer|exists:tables,id',
            'table_id' => 'nullable|integer|exists:tables,id',
            'frais' => 'nullable',
        ]);

        $commercial_id = Auth::id();
         $reference = 'CMD-' . date('Ymd') . '-' . strtoupper(Str::random(6));


        //creer un nouveau client si le numerod e tel nest pas encore utiliser
        $count = clients::Where("phone", $this->phone)->count();
        if ($count == 0) {
            $client = new clients();
            $client->nom = $this->nom;
            $client->prenom = $this->prenom;
            //  $client->adresse = $this->adresse;
            $client->phone = $this->phone;


            $client->save();
        }



        $panier = session()->get('panier', []);
        if ($panier) {
            $config = config::first();
            $commande = new commandes();
            $commande->nom = $this->nom;
            $commande->reference= $reference;
          
            $commande->prenom = $this->prenom;
            $commande->commercial_id = $commercial_id;
            //  $commande->adresse = $this->adresse;
            $commande->phone = $this->phone;
            // $commande->pays = $this->pays;
            $commande->frais = $this->frais ? $config->frais : null;

            if ($commande->save()) {
                foreach ($panier as $panier) {

                    //recuperation du type
                    $type = $panier["type"];
                    $quantite = intval($panier["quantite"]);
                   
                        //il s'agit d'un produit
                        $article = produits::find($panier["id"]);



                        if ($article) {
                            $contenu = new contenu_commande();
                            $contenu->id_commande = $commande->id;
                            $contenu->id_produit = $article->id;
                            $contenu->commercial_id = $commercial_id;
                            $contenu->table_id = $data['table_id'] ?? null;
                            $contenu->quantite =  $quantite;
                            $contenu->type = $type;
                            $contenu->prix_unitaire =  $article->getPrice();
                            $contenu->benefice = ($article->getPrice() - $article->prix_achat) * $quantite;
                            $contenu->save();


                            //diminuer le stock de l'article
                            $article->diminuer_stock($quantite);
                        }
                   
                }

                //delete session panier
                session()->forget('panier');

                //redirection
                return Redirect()->route('details_commande', ["id" => $commande->id])->with("success", "Votre commande a été enregistré");
            } else {
                //flash error message
                session()->flash('warning', 'Echec de la création de la commande.');
            }
        } else {
            //flash error message
            session()->flash('warning', 'Votre panier est vide.');
        }
    }
}
