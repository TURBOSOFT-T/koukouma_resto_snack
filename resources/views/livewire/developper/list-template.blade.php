<div>
    <div class="table-responsive-sm">
        <table class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark cusor">
                <tr>
                    <td>
                        <i class="ri-information-line" data-bs-toggle="modal" data-bs-target="#Informations"></i>
                    </td>
                    <th>Nom</th>
                    <th>Réfrence</th>
                    <th>Produit</th>
                    <th>Domaine</th>
                    <td>Creation</td>
                    <td>Meta</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                    <tr>
                        <td>
                            <input type="checkbox" wire:click="use_like_error_meta({{ $template->id }})"
                                @checked($template->meta_error == true) class="form-check-input" id="">
                        </td>
                        <td>
                            {{ $template->titre ?? '' }}
                        </td>
                        <td> {{ $template->reference ?? '' }} </td>
                        <td> {{ $template->produit->nom ?? '' }} </td>
                        <td> {{ $template->domaine->nom ?? '' }} </td>
                        <td> {{ $template->created_at }} </td>
                        <td>
                            @if ($template->meta)
                                <span>
                                    <i class="ri-terminal-box-fill text-success"></i> Oui
                                </span>
                            @else
                                <span>
                                    <i class="ri-terminal-box-fill text-danger"></i> Non
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-success" type="button" data-bs-toggle="modal"
                                data-bs-target="#link{{ $template->id }}">
                                <i class="ri-links-fill"></i>
                                Obtenir le ien
                            </button>
                            <a href="{{ route('edit-template', ['id' => $template->id]) }}"
                                class="btn btn-sm btn-warning">
                                <i class="ri-edit-2-line"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                wire:click="delete({{ $template->id }})" wire:confirm="Confirmer la suppression ?">
                                <i class="ri-delete-bin-line"></i>
                            </button>


                            <!-- Center modal content -->
                            <div class="modal fade" id="link{{ $template->id }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="myCenterModalLabel">
                                                <b>
                                                    <i class="ri-links-fill"></i>
                                                    lien du template : {{ $template->titre }}
                                                </b>
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="text-align: left !important">
                                            @if ($template->domaine)
                                                @php
                                                    $url =
                                                        $template->domaine->lien . '/produit/v/' . $template->reference;
                                                @endphp
                                            @endif
                                            <p>
                                                Veuillez copier le lien suivant ou scanner le code.
                                            </p>
                                            <div class="text-center">
                                                {!! QrCode::size(100)->generate($url ?? 'url') !!}
                                            </div>
                                            <br>
                                            <input type="text" class="form-control" value="{{ $url ?? '' }}">
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="text-center alert">
                                <div>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios/50/0000ff/template.png" alt="template" />
                                </div>
                                Aucun template enregistré pour l'instant.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>




    <!-- Center modal content -->
    <div class="modal fade" id="Informations" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        <b>
                            Selection de la configuration meta .
                        </b>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align: left !important">
                    <div>
                        <img src="/icons/error.png" class="w-100" alt="100" srcset="">
                    </div>
                    <div class="p-2">
                        La configuration du Meta du header de la page que vous allez selectionner sera utiliser dans le
                        header de la page d'érreurs des domaines autres.
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
