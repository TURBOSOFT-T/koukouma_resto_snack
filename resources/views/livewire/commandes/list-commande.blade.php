<div>
    <form wire:submit="filtrer">
        <div class="row">
            <div class="col-sm-4">
                <span>
                    <b>{{ $commandes->count() }}</b> Résultats sur {{ $total }}
                </span>
            </div>
            <div class="col-sm-12">
                <div class="input-group mb-3">
                    @if ($selectedCommandes)
                        <button type="button" class="btn btn-sm btn-secondary" wire:click="getSelectedCommandes">
                            Exporter le bordereau
                            ({{ count($selectedCommandes) }})
                        </button>
                    @endif
                    <input type="text" wire:model.live="key" placeholder="Recherche par nom,prenom, nnuméro"
                        class="form-control">
                    <select class="form-control" wire:model="statut2">
                        <option value="">Etat de confirmation</option>
                        <option value="">Tous</option>
                        <option value="confirmer">Confirmé</option>
                        <option value="non_confirmer">Non confirmé</option>
                    </select>
                    {{-- <select class="form-control" wire:model="gouvernorat">
                        <option value="">Gouvernorat</option>
                        @foreach ($gouvernoratsTunisie as $gouvernorat)
                            <option value="{{ $gouvernorat }}">
                                {{ $gouvernorat }}
                            </option>
                        @endforeach
                    </select> --}}
                    <select class="form-control" wire:model="statut">
                        <option value="">Etat</option>
                        <option value="créé">Créé</option>
                        <option value="traitement">Traitement</option>
                    {{--     <option value="Livraison">Livraison</option> --}}
                        <option value="livrée">Livré</option>
                        <option value="payée">Payée</option>
                        <option value="planification">Planification de retour</option>
                        <option value="retournée">Retournée</option>
                    </select>
                    <input type="date" class="form-control" wire:model="date">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('components.alert')

    <div wire:poll.5s class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <td></td>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prenom</th> 
                    <th>Téléphone</th>
                 {{--  <th>Responssale  Commercial</th> --}}
                    <th>Montant</th>
                    
                    <th>Traitement</th>
                     <th>Statut</th>
                    <th>Mode</th>
                    <th>Coupon(Valeur)</th>
                    <th>Date</th>
                    <th class="text-end">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                        </span>
                    </th>
                </tr>
            </thead>


            <tbody>
                @forelse ($commandes as $commande)
                    <tr>
                        <td>
                            <input type="checkbox" wire:click="toggleCommandeSelection({{ $commande->id }})">
                        </td>
                        <td>
                            <button class="btn btn-sm" data-bs-toggle="modal"
                                data-bs-target="#qr-code-{{ $commande->id }}">
                                <i class="ri-qr-scan-2-line"></i>
                            </button>
                            <!-- Center modal content -->
                            <div class="modal fade" id="qr-code-{{ $commande->id }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myCenterModalLabel">
                                                Commande #{{ $commande->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 class="text-muted">
                                                Veuillez scanner ce code Qr pour impprimer le Reçu de commande .
                                            </h6>
                                            <div class="text-center p-2">
                                                {!! QrCode::size(100)->generate(route('print_commande', ['id' => $commande->id])) !!}
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </td>
                        <td>{{ $commande->id }}</td>
                        <td>
                            {{ $commande->nom }}

                            

                            @if ($commande->note)
                                <i class="ri-message-2-fill" title="Une note a été ajouté"></i>
                            @endif
                        </td>
                        <td>
                              {{ $commande->prenom }}
                        </td>
                        <td>{{ $commande->phone }}</td>
                      {{--   <td>{{ $commande->commercial->nom ?? 'Non attribué' }}   {{ $commande->commercial->prenom ??  '' }}</td>
 --}}
                     
                        <td>{{ $commande->montant() -  $commande->coupon   ??    ''  }} <x-devise></x-devise> </td>
                        <td>
                            @can('order_edit')
                                @if ($commande->statut === 'payée')
                                    <b class="text-success">
                                        <i class="ri-check-double-fill"></i>
                                        Payée
                                    </b>
                                @elseif($commande->statut == 'retournée')
                                    <b class="text-danger">
                                        @if ($commande->etat == 'confirmé')
                                            <i class="ri-text-wrap"></i>
                                            retournée
                                        @else
                                            <i class="ri-close-circle-line"></i>
                                            Annulé
                                        @endif
                                    </b>
                                @else
                                    @if ($commande->etat == 'confirmé')


                                    <select class="form-control-sm"
                                                onchange="confirmStatusChange(event, {{ $commande->id }})"
                                                data-current-status="{{ $commande->statut }}">
                                                <option value="créé"
                                                    {{ $commande->statut === 'créé' ? 'selected' : '' }}>Créé</option>
                                                <option value="traitement"
                                                    {{ $commande->statut === 'traitement' ? 'selected' : '' }}> En Traitement
                                                </option>
                                                <option value="En cours livraison"
                                                    {{ $commande->statut === 'En cours livraison' ? 'selected' : '' }}>En
                                                    cours de Livraison</option>
                                                <option value="livrée"
                                                    {{ $commande->statut === 'livrée' ? 'selected' : '' }}>Livrée</option>
                                                <option value="payée"
                                                    {{ $commande->statut === 'payée' ? 'selected' : '' }}>Payée</option>
                                               
                                                <option value="retournée"
                                                    {{ $commande->statut === 'retournée' ? 'selected' : '' }}>Retournée
                                                </option>
                                            </select>
                                        
                                    @elseif($commande->etat == 'attente')
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="confirmer({{ $commande->id }})">
                                                <i class="ri-checkbox-circle-line"></i>
                                                Valider
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                wire:click="annuler({{ $commande->id }})">
                                                <i class="ri-close-line"></i>
                                                Annluer
                                            </button>
                                        </div>
                                    @else
                                        <i class="ri-close-circle-line"></i>
                                        Annulé
                                    @endif
                                @endif
                            @endcan

                        </td>
                        <td>@switch($commande->statut)
        @case('attente')
            <span class="badge bg-warning text-dark">En attente</span>
            @break

        @case('traitement')
            <span class="badge bg-info text-dark">En Traitemet</span>
            @break

        @case('En cours livraison')
            <span class="badge bg-success">En cours livraison</span>
            @break

        @case('livrée')
            <span class="badge bg-primary">Livrée</span>
            @break

        @case('retournée')
            <span class="badge bg-danger">Retournée</span>
            @break

            

        @default
            <span class="badge bg-secondary">{{ ucfirst($commande->statut) }}</span>
    @endswitch
                        </td>
                        <td>
                            <span class="text-capitalize">
                                {{ $commande->mode }}
                            </span>
                        </td>
                        <td>@if($commande->coupon)
                            {{ $commande->coupon }}  <x-devise></x-devise>
                            @else
                            ---
                        @endif</td>
                        <td>{{ $commande->created_at }} </td>
                        <td style="text-align: right;">
                            <div class="btn-group">
                                @can('order_edit')
                                    @if ($commande->modifiable())
                                        <button class="btn btn-sm btn-warning"
                                            onclick="url('{{ route('edit_commande', ['id' => $commande->id]) }}')">
                                            <i class="ri-edit-2-line"></i>
                                        </button>
                                    @endif
                                @endcan
                                @can('order_edit')
                                    <button class="btn btn-sm btn-primary"
                                        onclick="add_note({{ $commande->id }},'{{ $commande->nom }}')">
                                        <i class="ri-sticky-note-add-line"></i> Note
                                    </button>
                                @endcan
                                <button class="btn btn-info btn-sm" type="button" title="Imprimer la commande"
                                    onclick="url('{{ route('print_commande', ['id' => $commande->id]) }}')">
                                    <i class="ri-printer-line"></i>
                                </button>
                                <button class="btn btn-sm btn-dark"
                                    onclick="url('{{ route('details_commande', ['id' => $commande->id]) }}')">
                                    <i class="ri-eye-line"></i>
                                </button>
                                @can('order_delete')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="toggle_confirmation({{ $commande->id }})">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                @endcan
                            </div>
                            @can('order_delete')
                                <button class="btn btn-sm btn-success d-none" type="button"
                                    id="confirmBtn{{ $commande->id }}" wire:click="delete({{ $commande->id }})">
                                    <span class="hide-tablete">
                                        Confirmer
                                    </span>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="text-center">
                                <div>
                                    <img src="/icons/icons8-ticket-100.png" height="100" width="100"
                                        alt="" srcset="">
                                </div>
                                Aucune commande trouvé
                                @if ($key)
                                    <b> " {{ $key }} " </b>
                                @endif
                                .
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>


        </table>
    </div>

    {{ $commandes->links('pagination::bootstrap-4') }}


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmStatusChange(event, commandeId) {
            const newStatus = event.target.value;

            Swal.fire({
                title: 'Etes vous sûr de changer de status?',
                text: ` Voulez vous réellement changer le tatus à: ${newStatus}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('updateStatus', commandeId, newStatus);
                    Swal.fire(
                        'Changed!',
                        'Le status a été changé avec succès.',
                        'success'
                    );
                } else {
                    // Reset the dropdown to the original value if the user cancels
                    event.target.value = event.target.getAttribute('data-current-status');
                }
            });
        }
    </script>

</div>
