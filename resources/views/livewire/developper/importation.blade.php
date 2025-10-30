<div>
    @include('components.alert')
    <form wire:submit="importer">
        <div class="mb-3">
            <label for="">
                Produit a joinde a l'importation
            </label>
            <select name="produit" id="produit" wire:model="produit" class="form-control">
                <option value=""></option>
                @foreach ($produits as $produit)
                    <option value="{{ $produit->id }}"> {{ $produit->nom }} </option>
                @endforeach
            </select>
            @error('produit')
                <span class="small text-danger"> {{ $message }} </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="">
                Veuillez selectionner le fichier excel a importer
            </label>
            <input type="file" required id="fichier" wire:model="fichier" name="fichier" class="form-control">
            @error('fichier')
                <span class="small text-danger"> {{ $message }} </span>
            @enderror
        </div>
        <div class="mb-3">
            <div>
                <input type="checkbox" wire:model="check" name="check" id="check" class="form-check-input">
                Importer et annuler la duplication des données.
            </div>
            @error('check')
                <span class="small text-danger"> {{ $message }} </span>
            @enderror
        </div>
        <div class="text-center">
            <button class="btn btn-primary" type="submit">
                <span wire:loading>
                    <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                </span>
                Importer
            </button>
        </div>
    </form>
</div>
