<?php

namespace App\Livewire\Developper;

use App\Imports\ImportClient;
use App\Models\clients;
use App\Models\commandes;
use App\Models\contenu_commande;
use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Importation extends Component
{
    use WithFileUploads;
    public $fichier, $produit, $check;


    public function render()
    {
        $produits = produits::all(["nom", "id"]);
        return view('livewire.developper.importation', compact("produits"));
    }

    public function importer()
    {
        $this->validate([
            'fichier' => 'required|file|mimes:csv,xls,xlsx,xml',
            'produit' => 'required|integer|exists:produits,id',
            'check' => 'nullable'
        ], [
            'fichier.required' => 'Le fichier est obligatoire',
            'fichier.file' => 'Le fichier doit etre un fichier',
            'fichier.required' => 'Le fichier doit etre csv,xls,xlsx,xml',
            'produit.required' => 'Le produit est obligatoire',
            'produit.integer' => 'Le produit doit etre un entier',

        ]);

        $import =0;
        $check  = $this->check ? true : false;
        $path = $this->fichier->store('temp');
        $realPath = storage_path('app/' . $path);
        $array = Excel::toCollection(new ImportClient,  $realPath);
        $produit = produits::find($this->produit);
        foreach ($array as $item) {
            // Each $item is a Collection of Collections
            foreach ($item as $entry) {
                // Check if the entry contains the keys we're interested in
                if ($entry->has('full_name') && $entry->has('phone_number')) {
                    // Now $entry is the Collection containing the data fields
                    $fullName = $entry->get('full_name');
                    $phoneNumber = $entry->get('phone_number');

                    if ($check) {
                        $exist = commandes::where('phone', $phoneNumber)->exists();
                        if (!$exist) {
                            $this->create_commande($fullName,$phoneNumber,$produit);
                            $import++;
                        }
                    } else {
                        $this->create_commande($fullName,$phoneNumber,$produit);
                        $import++;
                    }
                    
                }
            }
        }

        //Storage::delete($path);
        session()->flash('success', 'Les données ont été importées avec succès. ( '.$import.' Lignes )');
        $this->produit = null;
        $this->fichier = null;
    }


    public function create_commande($fullName,$phoneNumber,$produit){
        // Process or save the data as needed
        $commande = new commandes();
        $commande->nom = $fullName;
        $commande->phone = $phoneNumber;
        if ($commande->save()) {
            $contenu_commande = new contenu_commande();
            $contenu_commande->id_commande = $commande->id;
            $contenu_commande->id_produit = $produit->id;
            $contenu_commande->quantite = 1;
            $contenu_commande->prix_unitaire = $produit->getPrice();
            $contenu_commande->benefice = $produit->getPrice() - $produit->prix_achat;
            if ($contenu_commande->save()) {
                $produit->diminuer_stock(1);
            }
        }

        $count = clients::Where("phone", $phoneNumber)->count();
        if ($count == 0) {
            $client = new clients();
            $client->nom = $fullName;
            $client->phone = $phoneNumber;
            $client->save();
        }
    }



}
