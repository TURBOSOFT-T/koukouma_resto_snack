<?php

namespace App\Livewire;

use App\Models\clients;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListClients extends Component
{
    use WithPagination;

    public $key;
    public function render()
    {
        $clients =User::where('role', 'client')
      ->  Orderby("created_at");
        if (isset($this->key)) {
            $clients->where('nom', 'like', '%' . $this->key . '%')
                ->orWhere('phone', 'like', '%' . $this->key . '%')
                ->orWhere('prenom', 'like', '%' . $this->key . '%');
        }
        $clients = $clients->paginate(30);
        $total = clients::count();
        return view('livewire.list-clients', compact('clients','total'));
    }


    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }

    public function delete($id){
        //delete client
        $client = User::find($id);
        if($client){
            $client->delete();
            //flash message
            session()->flash('message', 'Client supprimé avec succès!');
        }
    }
}
