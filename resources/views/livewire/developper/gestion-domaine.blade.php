<div class="p-3">
    <div>
        <form wire:submit="create">
            <div class="mb-2">
                <label for="">Noveau nom de domaine</label>
                <div class="input-group">
                    <input type="text" wire:model="nom" placeholder="Nom du domaine" class="form-control">
                    <input type="text" wire:model="lien" placeholder="lien du domaine" class="form-control">
                    <button type="sumbit" class="btn btn-primary">
                        @if ($id)
                            Modifier
                        @else
                            Ajouter
                        @endif
                    </button>
                </div>
                @error('nom')
                    <span class="test-danger">{{ $message }}</span>
                @enderror
                @error('lien')
                    <span class="test-danger">{{ $message }}</span>
                @enderror
                @error('error')
                    <span class="test-danger">{{ $error }}</span>
                @enderror
            </div>
        </form>
        @include('components.alert')
        <hr>
        <div class="table-responsive-sm">
            <table class="table table-striped dt-responsive nowrap w-100">
                <thead class="table-dark cusor">
                    <tr>
                        <th>Nom</th>
                        <th>Lien</th>
                        <th>
                            <span wire:loading>
                                <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow"
                                    alt="">
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($domaines as $domaine)
                        <tr>
                            <td> {{ $domaine->nom }} </td>
                            <td> {{ $domaine->lien }} </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-dark"
                                    wire:click="edit({{ $domaine->id }})">
                                    <i class="ri-edit-box-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger"
                                    wire:click="delete({{ $domaine->id }})" wire:confirm="Confirmer la suppression ?">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-2">
                                <div>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios-filled/50/0000ff/domain.png" alt="domain" />
                                </div>
                                Pas de domaines enregistrés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div>

    </div>
</div>
