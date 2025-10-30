<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Http\Requests\commandes\CommandesRequest;
use Illuminate\Http\Request;
use App\Models\{commandes, produits, Coupon, contenu_commande, config, notifications, Table, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
//use Illuminate\Support\Facade\Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderMail;
use App\Mail\FirstOrder;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Mail\Mailable;
use App\Services\PayUService\Exception;
use Illuminate\Validation\ValidationException;


class CommandeController extends Controller
{

  public $cart;




  public function commander()
  {
    $configs = config::firstOrFail();

    $paniers_session = session('cart', []);

    // Vérifier que $paniers_session est bien un tableau
    if (!is_array($paniers_session)) {
      $paniers_session = [];
    }
    $paniers = [];
    $total = 0;
    if (empty($paniers_session)) {
      request()->session()->flash('error', 'La panier est vide !');
      return back();
    }


    if (session()->has('coupon')) {
      $coupon = session()->get('coupon');
      $value = Coupon::where('code', $coupon)->first();
      $discuont = session('coupon')['value'];
    }

    foreach ($paniers_session as $session) {
      $produit = produits::find($session['id_produit']);
      if ($produit) {
        $paniers[] = [
          'nom' => $produit->nom,
          'id_produit' => $produit->id,
          'photo' => $produit->photo,
          'quantite' => $session['quantite'],
          'prix' => $produit->getPrice(),
          'total' => $session['quantite'] * $produit->getPrice(),
        ];
        if (session()->has('coupon')) {
          $total += $session['quantite'] * $produit->getPrice() - session('coupon')['value'];
        } else {
          $total += $session['quantite'] * $produit->getPrice();
        }

        //  dd($total);
      }
    }

 $tables = Table::all();
    return view('front.commandes.checkout', compact('configs', 'paniers', 'total','tables'));
  }







  public function confirmOrder(Request $request)
  {
 $data=   $request->validate([

      'nom' => ['nullable', 'string', 'max:255'],
      'prenom' => ['nullable', 'string', 'max:255'],
      'email' => 'required',
      'coupon' => 'nullable|numeric',
      'type_commande' => 'required|in:emporter,table',
       'table_id' => 'nullable|integer|exists:tables,id',


      'phone' => 'required',


    ]);


    $connecte = Auth::user();
    $configs = config::firstOrFail();
    // Génération de la référence unique
    $reference = 'CMD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

    $total = 0;

    if (session()->has('coupon')) {
      $coupon = session()->get('coupon');
      $value = Coupon::where('code', $coupon)->first();
      $discuont = session('coupon')['value'];
    }


     if (Auth::check()) {
                $userId = Auth::id();
            } else {
                $user = User::where('email', $request->email)->first();

                if (!$user) {
                    $temporaryPassword = Str::random(8);
                    $user = User::create([
                        'nom' => $request->input('nom'),
                        'prenom' => $request->input('prenom'),
                        'email' => $request->input('email'),
                         //  'password' => Hash::make($request->input('telephone')),
                            'password' => Hash::make($request->input('phone')),
                       // 'password' => Hash::make($temporaryPassword),
                        'phone' => $request->input('telephone'),
                    ]);

                   // Mail::to($user->email)->send(new WelcomeUserMail($user, $temporaryPassword));
                }

                $userId = $user->id;
            }


    //dd($discuont);
    if ($connecte) {

      $order = new commandes([

        'user_id' => auth()->user()->id,
        'reference' => $reference,
        'nom' => $request->input('nom'),
        'prenom' => $request->input('prenom'),
        'email' => $request->input('email'),
        'adresse' => $request->input('adresse'),
        'phone' => $request->input('phone'),
        'pays' => $request->input('pays'),
        'note' => $request->input('note'),
        'type_commande' => $request->input('type_commande'),
        'frais' => $configs->frais ?? 0,

        'coupon' => isset(session('coupon')['value']) ? session('coupon')['value'] : null,
   


      ]);
      [
        'email.required' => 'Veuillez entrer votre email',
        'nom.required' => 'Veuillez entrer votre nom',
        'phone.required' => 'Veuillez entrer votre numéro de téléphone',
        'adresse.required' => 'Veuillez entrer votre addresse',

      ];
    } else {

    

      $order = new commandes([

        ///  'user_id' => auth()->user()->id,
        'user_id' => $userId,
        'reference' => $reference,
        'nom' => $request->input('nom'),
        'prenom' => $request->input('prenom'),
        'email' => $request->input('email'),
        'adresse' => $request->input('adresse'),
        'phone' => $request->input('phone'),
        'pays' => $request->input('pays'),
        'note' => $request->input('note'),
         'type_commande' => $request->input('type_commande'),
        'frais' => $configs->frais ?? 0,


        'coupon' => isset(session('coupon')['value']) ? session('coupon')['value'] : null,


      ]);
      [
        'email.required' => 'Veuillez entrer votre email',
        'nom.required' => 'Veuillez entrer votre nom',
        'phone.required' => 'Veuillez entrer votre numéro de téléphone',
        'adresse.required' => 'Veuillez entrer votre addresse',

      ];
    }


    $order->save();

   /*  $user = new User([

      'nom' => $request->input('nom'),
      'prenom' => $request->input('prenom'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('phone')),

      'phone' => $request->input('phone'),
    ]);
 */


    $existingUsersWithEmail = User::where('email', $request['email'])->exists();

    if (!$existingUsersWithEmail) {

      // Mail::to($user->email)->send(new FirstOrder($user));


     // $user->save();
    }

    $paniers_session = Session::get('cart') ?? [];
    $total = 0;

    foreach ($paniers_session as $session) {
      $produit = produits::find($session['id_produit']);
      if ($produit) {

        $items =   contenu_commande::create([
          'id_commande' => $order->id,
          'id_produit' => $produit->id,
         'table_id' => $data['table_id'] ?? null,
         

          'prix_unitaire' => $produit->getPrice(),
          'quantite' => $session['quantite'],

        ]);


        $produit->diminuer_stock($session['quantite']);
      }
    }

    //envoyer les emails
    // $this->sendOrderConfirmationMail($order);

    //effacer le panier
    session()->forget('cart');
    session()->forget('coupon');

    //generate notification
    $notification = new notifications();
    // $notification->url = "admin/commande" . $order->id;
    $notification->url = route('details_commande', ['id' => $order->id]);
    $notification->titre = "Nouvelle commande.";
    $notification->message = "Commande passée par " . $order->nom;
    $notification->type = "commande";
    $notification->save();


    return redirect()->route('thank-you');
  }





  public function sendOrderConfirmationMail($order)
  {

    Mail::to($order->email)->send(new OrderMail($order));
  }

  public function index(Request $request)
  {

    return view('front.commandes.thankyou');
  }
}
