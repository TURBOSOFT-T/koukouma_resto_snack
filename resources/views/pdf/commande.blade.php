@php
    $config = DB::table('configs')->select('icon', 'logo')->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        {{ \App\Helpers\TranslationHelper::TranslateText('Reçu de commande') }}
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }


        .logo {
            width: 150px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
        }

        .produit {
            height: 70px !important;
            height: 70px !important;
            border-radius: 10px;
            overflow: hidden;
        }

        .produit img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .tr-montant {
            color: white !important;
            background-color: #000000;
            text-align: right !important
        }

        .text-center {
            text-align: center !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="text-align: center">
           {{--  <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/icons/logo png.png'))) }}" alt="logo"
                srcset=""> --}}
                <center>
                   {{--  <img src="{{  Storage::url($config->logo ?? '') }}" alt="logo"
                        height="70" srcset=""> --}}
                         <img href="{{ Storage::url($config->logo ?? '') }}" alt="Site Logo" >
                         {!! QrCode::format('svg')->size(120)->generate(route('print_commande', $commande->reference)) !!}

                </center>
                
                {{-- ✅ QR Code --}}

        </div>
        <h1>
            {{ \App\Helpers\TranslationHelper::TranslateText('Réçu de commande') }} .
        </h1>
        <h5> {{ \App\Helpers\TranslationHelper::TranslateText('Informations dur la commande') }}: #{{ $commande->id }}</h5>
       {{--  <center style="margin-bottom: 20px;"> --}}
        
    {!! QrCode::size(120)->generate(route('print_commande', $commande->reference)) !!}
    <div style="font-size: 13px; color: #333;">
        {{ \App\Helpers\TranslationHelper::TranslateText('Référence :') }}
        <strong>{{ $commande->reference }}</strong>
       {{--  <br>
        <em>{{ \App\Helpers\TranslationHelper::TranslateText('Scannez ce code pour consulter la commande en ligne') }}</em>
    < --}}</div>
{{-- </center> --}}
        <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Date de commande') }}:</strong> {{ $commande->created_at }}</p>

        <h3> {{ \App\Helpers\TranslationHelper::TranslateText('Produits commandés') }} :</h3>
        <table>
            <thead>
                <tr>
                    <th> {{ \App\Helpers\TranslationHelper::TranslateText('Produit') }}</th>
                    <th> {{ \App\Helpers\TranslationHelper::TranslateText('Quantité') }}</th>
                    <th> {{ \App\Helpers\TranslationHelper::TranslateText('Prix unitaire') }}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $commande->frais ?? 0;
                @endphp
                @foreach ($commande->contenus as $item)
                    <tr>
                        <td>
                           
                                {{ $item->produit->nom }}
                           
                        </td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ $item->prix_unitaire }} <x-devise></x-devise></td>
                        <td>{{ $item->prix_unitaire * $item->quantite }} <x-devise></x-devise> </td>
                    </tr>
                    @php
                        $total += $item->prix_unitaire * $item->quantite - $commande->coupon;
                    @endphp
                @endforeach
                <tr>
                    <td>
                        <b> {{ \App\Helpers\TranslationHelper::TranslateText('Frais de livraison') }} </b>
                    </td>
                    <td>1</td>
                    <td> {{ $commande->frais ?? 0 }} <x-devise></x-devise> </td>
                    <td> {{ $commande->frais ?? 0 }} <x-devise></x-devise></td>
                </tr>
                @if($commande->coupon ?? 0)
                <tr>
                    <td>
                        <b> {{ \App\Helpers\TranslationHelper::TranslateText('Coupon de réduction') }} </b>
                    </td>
                    <td>1</td>
                    <td> {{ $commande->coupon ?? 0 }} <x-devise></x-devise> </td>
                    <td> -{{ $commande->coupon ?? 0 }} <x-devise></x-devise> </td>
                </tr>
                    
                @endif
                <tr class="tr-montant">
                    <td colspan="3">
                        <b> {{ \App\Helpers\TranslationHelper::TranslateText('Total de la commande') }}:</b>
                    </td>
                    <td>
                        {{ $total }}  <x-devise></x-devise>
                    </td>
                </tr>
            </tbody>
        </table>


        <h4> {{ \App\Helpers\TranslationHelper::TranslateText('Informations sur la livraison') }} :</h4>
        <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Nom complet') }}:</strong> {{ $commande->prenom }} {{ $commande->nom }}</p>
      {{--   <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Addresse') }}:</strong> {{ $commande->adresse ?? 'N/A' }}</p>
       --}}  <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Numéro de téléphone') }}:</strong> {{ $commande->phone ?? 'N/A' }}</p>
      {{--   <p><strong>Pays:</strong> {{ $commande->pays ?? 'N/A' }}</p> --}}
       {{--  <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Gouvernorat') }}:</strong> {{ $commande->gouvernorat ?? 'N/A' }} </p>
        --}} <hr>

        <p>
            {{ \App\Helpers\TranslationHelper::TranslateText('Merci de votre confiance') }}!
            
           
            <br> 
            {{ \App\Helpers\TranslationHelper::TranslateText('Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter') }}.
            
            
          
        </p>
    </div>
</body>

</html>
