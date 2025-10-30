<div>

    @include('components.alert')

    @if ($produit)
        <form wire:submit="update_produit">
        @else
            <form wire:submit="create">
    @endif

    <div class="row">
        <div class="col-sm-8">
            
            <div class="mb-3">
                <label for="">Nom du produit</label>
                <input type="text" name="nom" class="form-control" wire:model="nom">
                @error('nom')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>
            <div class="mb-3">
                <label><strong>Description :</strong></label>
                <textarea class=" form-control" name="description" wire:model="description"></textarea>
                @error('description')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>


            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label for="">Categorie </label>
                    <select wire:model='category_id' class="form-control @error('category_id') is-invalid @enderror">
                        <option value=""></option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger small"> {{ $message }} </span>
                    @enderror
                </div>
                {{--   <div class="col-sm-6 mb-3">
                    <label for="">Marque </label>
                    <select wire:model='marque_id' class="form-control @error('marque_id') is-invalid @enderror">
                        <option value=""></option>
                        @foreach ($marques as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger small"> {{ $message }} </span>
                    @enderror
                </div>  --}}
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Prix de vente</label>
                        <input type="number" step="0.1" name="prix" class="form-control" wire:model="prix">
                        @error('prix')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Prix d'achat</label>
                        <input type="number" step="0.1" name="prix_achat" class="form-control"
                            wire:model="prix_achat">
                        @error('prix_achat')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Référence du produit</label>
                        <input type="text" step="0.1" name="reference" class="form-control"
                            wire:model="reference">
                        @error('reference')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="mb-3">
                <label for="">Photo d'illustration (300*300)</label>
                <div class="preview-produit-illustration" onclick="preview_illustration('new-prosduit')">
                    @if ($produit)
                        @if ($photo2 && is_null($photo))
                            <img src="{{ Storage::url($photo2) }}" alt="" class="w-100">
                        @else
                            <img src="{{ $photo->temporaryUrl() }}" alt="" srcset="">
                        @endif
                    @else
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-100">
                        @else
                            <img src="/icons/no-image.webp" alt="" class="w-100">
                        @endif
                    @endif
                </div>
                <input type="file" name="photo" accept="image/*" class=" d-none" id="file-input-new-prosduit"
                    wire:model="photo">
                @error('photo')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="">Autres photos</label>
                <input type="file" multiple name="photos" accept="image/*" class="form-control" wire:model="photos">
                @error('photos')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>

        </div>
    </div>
    <div style="text-align: right;">
        <button class="btn btn-primary btn-sm px-5" type="submit" wire:loading.attr="disabled">
            <span wire:loading>
                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
            </span>
            @if ($produit)
                Mettre a jour
            @else
                Enregistrer le produit
            @endif
        </button>
    </div>
    </form>
</div>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
</script>
