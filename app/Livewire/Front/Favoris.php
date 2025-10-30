<?php

namespace App\Livewire\Front;

use App\Models\favoris as ModelsFavoris;
use Livewire\Component;

class Favoris extends Component
{
    public function render()
    {
        $favoris= ModelsFavoris::where('id_user', auth()->id() )->get();
        return view('livewire.front.favoris', compact("favoris"));
    }



    public function delete($id){
        $favoris = ModelsFavoris::find($id);
        if ($favoris) {
            $favoris->delete();
            session()->flash('success', 'Favoris supprimé avec succès');
        }
    }



}
