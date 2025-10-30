<?php

namespace App\Livewire\Commandes;

use App\Http\Traits\ListGouvernorats as TraitsListGouvernorats;
use App\Models\commandes;
use App\Models\produits;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Mail\{OrderChangeStatut, ChangeStatut};
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;


class ListCommande extends Component
{
    use WithPagination;
    use TraitsListGouvernorats;

    public $selectedCommandes = [];
    public $date, $statut, $key, $gouvernoratsTunisie, $gouvernorat, $statut2;



    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }


    public function render()
    {
        $commandesQuery = commandes::query();
   // Filtrage selon l'utilisateur connecté
    if (auth()->user()->role != 'admin') {
        $commandesQuery->where('commercial_id', auth()->id());
    }
   
        if (strlen($this->date) > 0) {
            $commandesQuery->whereDate('created_at', $this->date);
        }
      
        if (strlen($this->statut) > 0) {
            $commandesQuery->where('statut', $this->statut);
        }
        if (strlen($this->statut2) > 0) {
            if ($this->statut2 == "confirmer") {
                $commandesQuery->where('etat', "confirmé");
            } else {
                $commandesQuery->where('etat', "annulé");
            }
        }
        if (strlen($this->key) > 0) {
            $commandesQuery->where('nom', 'like', '%' . $this->key . '%')
                ->orWhere('adresse', 'like', '%' . $this->key . '%')
                ->orWhere('phone', 'like', '%' . $this->key . '%')
                 ->orWhere('reference', 'like', '%' . $this->key . '%')

                ->orWhere('prenom', 'like', '%' . $this->key . '%');
        }
        $commandes = $commandesQuery->Orderby('id', "Desc")->paginate(80);
        $total = commandes::count();
       
        return view('livewire.commandes.list-commande', compact("commandes", "total"));
    }



    public function updateStatus($commandeId, $newStatus)
    {

        $commande = commandes::findOrFail($commandeId);
        if ($commande) {
            $commande->statut = $newStatus;


            if ($newStatus == "retournée") {
                foreach ($commande->contenus as $contenus) {
                    $article = produits::find($contenus->id_produit);
                    if ($article) {
                        $article->retourner_stock($contenus->quantite);
                    }
                }
                $this->sendOrderConfirmationMail($commande);
            }
            if ($newStatus == "En cours livraison") {

                $this->sendOrderConfirmationMail($commande);
            }

            if ($newStatus == "traitement") {

                $this->sendOrderConfirmationMail($commande);
            }
            if ($newStatus == "planification") {

                $this->sendOrderConfirmationMail($commande);
            }


            $commande->save();
        }
    }



    public function sendOrderConfirmationMail($commande)
    {
        try {
            Mail::to($commande->email)->send(new OrderChangeStatut($commande));
        } catch (\Exception $e) {

            \Log::error('Erreur lors de l\'envoi de l\'email de confirmation de commande : ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        $commande = commandes::find($id);

        // $commande->delete();
        if ($commande->statut == "attente" || $commande->statut == "créé" || $commande->statut == "traitement" || $commande->statut == "planification" || $commande->statut == "livrée") {

            foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->retourner_stock($contenus->quantite);
                }
            }


            $commande->delete();

            //flash message
            session()->flash('success', 'Commande supprimée avec succès');
        }
        return view('livewire.commandes.list-commande');
    }

    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }


    public function confirmer($id)
    {
        $commande = commandes::find($id);
        if ($commande) {
            /*  foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->diminuer_stock($contenus->quantite);
                }
            } */
            $commande->etat = "confirmé";

            $commande->save();
            $this->sendOrderConfirmationMail($commande);
        }
    }

    public function annuler($id)
    {
        $commande = commandes::find($id);
        if ($commande) {
            foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->retourner_stock($contenus->quantite);
                }
            }
            $commande->statut = "retournée";
            $commande->etat = "annulé";

            $commande->save();
            $this->sendOrderConfirmationMail($commande);
        }
    }


    public function toggleCommandeSelection($commandeId)
    {
        if (in_array($commandeId, $this->selectedCommandes)) {
            $this->selectedCommandes = array_diff($this->selectedCommandes, [$commandeId]);
        } else {
            $this->selectedCommandes[] = $commandeId;
        }
    }


    public function getSelectedCommandes()
    {
        //check if $this->selectedCommandes is not empty
        if (count($this->selectedCommandes) > 0) {
            $ids = json_encode($this->selectedCommandes);
            return redirect()->route('print_bordereau', ["ids" => $ids]);
        } else {
            return false;
        }
    }
}
