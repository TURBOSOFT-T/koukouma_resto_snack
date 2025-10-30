<?php

namespace App\Livewire\Developper;

use App\Models\domaines;
use Livewire\Component;

class GestionDomaine extends Component
{

    public $nom, $lien, $id;

    public function render()
    {
        $domaines = domaines::orderby("id", "Desc")->get();
        return view('livewire.developper.gestion-domaine', compact("domaines"));
    }

    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:200',
            'lien' => 'required|string'
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'nom.string' => 'Le nom doit être une chaine de caractère',
            'nom.max' => 'Le nom ne peut pas dépasser 200 caractères',
            'lien.required' => 'Lien est obligatoire',
        ]);

        if ($this->id) {
            $find = domaines::find($this->id);
            if ($find) {
                $domaine = $find;
            }
        } else {
            $domaine = new domaines();
        }
        
        $domaine->nom = $this->nom;
        $domaine->lien = $this->lien;
        $domaine->save();

        //flash message
        if ($this->id) {
            session()->flash('success', 'le domaine a été modifié avec succès');
            $this->id = "";
        } else {
            session()->flash('success', 'le domaine a été ajouté avec succès');
        }



        //reset input
        $this->nom = "";
        $this->lien = "";
    }


    public function delete($id)
    {
        $domaine = domaines::find($id);
        if ($domaine) {
            $domaine->delete();
            //flash message
            session()->flash('warning', 'le domaine a été supprimé avec succès');
        }
    }


    public function edit($id)
    {
        $domaine = domaines::find($id);
        if ($domaine) {
            $this->nom = $domaine->nom;
            $this->lien = $domaine->lien;
            $this->id = $domaine->id;
        }
    }



    public function extractDomain($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $domain = str_replace('www.', '', $host);

        return $domain;
    }

}
