<?php

namespace App\Livewire\Produits;

use App\Models\{produits, Category, Marque};
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;

class AddProduit extends Component
{
    use WithFileUploads;

    public $nom,$tags, $prix, $category_id,$photo, $photos, $prix_achat, $photo2, $photos2, $produit, $reference, $description,$marque_id ;

public $free_shipping;
    public function mount($produit)
    {
        if ($produit) {
            $this->produit = $produit;
            $this->nom = $produit->nom;
            $this->tags = $produit->tags;
            $this->category_id = $produit->category_id;
           // $this->marque_id = $produit->marque_id;
            $this->reference = $produit->reference;
            $this->prix = $produit->prix;
            $this->prix_achat = $produit->prix_achat;
            $this->photo2 = $produit->photo;
            $this->photos2 = $produit->photos;
            $this->description = $produit->description;
            $this->free_shipping = $produit->free_shipping;
         //   $this->tags = $produit->tags;

        }
    }





    public function render()
    {
        $categories = Category::all();
        $marques = Marque::all();
        return view('livewire.produits.add-produit', compact('categories','marques'));
    }






    //validation
    public function create()
    {
        $data =  $this->validate([
            'nom' => 'required|string',
            'description' => 'required|string|max:5000060',
         //   'tags' => 'nullable|string|max:260',
            'reference' => 'required|string|unique:produits,reference',
            'prix' => 'required|numeric|gt:prix_achat',
            'prix_achat' => 'required|numeric',
            'photo' =>  'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'category_id' => 'required|integer|exists:categories,id',
            
            'free_shipping' => 'nullable|boolean',
           // 'marque_id' => 'nullable|integer|exists:marques,id',
         //  'marque_id' => 'nullable|integer|exists:marques,id',
        ]);
        ;[
            'reference.required' => ' La reference',
            'nom.required' => 'Veuillez entrer votre nom',
           'prix.required' => 'Veuillez entrer  le prix',
            //'adresse.required' => 'Veuillez entrer votre addresse',
      
          ];


        $categories = Category::findOrFail($data[('category_id')]);

        $produit = new produits();
        $produit->nom = $this->nom;

     //   $produit->tags = $this->tags;
                $produit->description = $this->description;
        $produit->reference = $this->reference;
        $produit->free_shipping = $this->free_shipping;
        // $produit->category = $this->category;

        $produit->category_id = $this->category_id;
       // $produit->marque_id = $this->marque_id;



        $produit->prix = $this->prix;
        $produit->prix_achat = $this->prix_achat;
        $produit->photo = $this->photo->store('produits', 'public');
        if ($this->photos) {
            $photosPaths = [];
            foreach ($this->photos as $photo) {
                $photosPaths[] = $photo->store('produits', 'public');
            }
            $produit->photos = json_encode($photosPaths);
        }
        // $produit->save();

        $categories->produits()->save($produit);

        //reset input
        $this->reset();

        //flash message
        session()->flash('success', 'Produit ajouté avec succès');
    }


    public function update_produit()
    {
        if ($this->produit) {
            $this->validate([
                'nom' => 'required|string',
                'prix' => 'required|numeric|gt:prix_achat',
               
                'prix_achat' => 'required|numeric',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
              //  'marque_id' => 'nullable|integer|exists:marques,id',
                'category_id' => 'required|integer|exists:categories,id',
                'free_shipping' => 'nullable|boolean',
            ]);



            $this->produit->nom = $this->nom;
            $this->produit->description = $this->description;
        
            $this->produit->prix = $this->prix;
            $this->produit->prix_achat = $this->prix_achat;
          //  $this->produit->marque_id = $this->marque_id;
            $this->produit->category_id = $this->category_id;
            $this->produit->free_shipping = $this->free_shipping;
          //  $produit->category_id = $this->category_id;

            if ($this->photo) {
                //delete old photo
                if ($this->produit->photo) {
                    Storage::disk('public')->delete($this->produit->photo);
                }
                $this->produit->photo = $this->photo->store('produits', 'public');
            }

            if ($this->photos) {
                $photosPaths = [];
                foreach ($this->photos as $photo) {
                    $photosPaths[] = $photo->store('produits', 'public');
                }
                $this->produit->photos = json_encode($photosPaths);
            }
            $this->produit->save();


            $this->resetInput();

            return redirect()->route('produits')->with('success', "Produit modifié avec succès");
        }
    }










    public function resetInput()
    {
        $this->nom = '';
        $this->tags = '';
        $this->prix = '';
        $this->photo = '';
        $this->photos = '';
    }
}
