<?php

namespace App\Http\Controllers;

//require './vendor/autoload.php';


use App\Models\{commandes,config, User,produits, Category, Service,Marque, Testimonial};
use App\Models\Banners;
use App\Models\templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Front\SearchRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    

    public function home(Request $request)
    {
        $key = $request->input("key", null);
        if(!is_null($key)){
            $produits->where('nom', 'like', '%'.$key.'%')
            ->Orwhere('description', 'like', '%'.$key.'%');
        }
       $produits = produits::select('*')->latest()->take(20)->get();
       $searchproducts = produits::select('*')->latest()->take(10)->get();
       $rescentesproduits = produits::select('*')->take(10)->get();
      // $produitspromotions = produits::select('*')->
    


      // $produits = produits::select('*')->latest()->take(16)->get();
       $categoryProducts = DB::table('produits')
       ->select('*')
       ->where('category_id', 'category_id')
       ->get();
 
       $configs= config::all();
       $banners = Banners::select("titre","sous_titre","image")->get();

       $testimonials = Testimonial::orderBy('created_at', 'desc')
       ->where('active', '1')
       ->limit(10)->get();

       $services = Service::all();
      return view('front.index', compact('rescentesproduits','searchproducts','produits','configs','banners','services','key','testimonials', 'categoryProducts'));

    }
     
      public function detailsServices($id, $slug){
        $service =Service:: findOrFail($id);
        $configs= config::all();
        $services = Service::all();
        return view('front.services.details', compact('service','services','configs'));
      }


    public function shop(Request $request){
        $key = $request->input("key", null);
        $id_categorie = $request->get("id_categorie", null);
        $price_range = $request->input("price_range", null);
        $ordre_affichage = $request->input("ordre_affichage", null);
        
        $produits = produits::query();

        if(!is_null($key)){
            $produits->where('nom', 'like', '%'.$key.'%')
            ->Orwhere('description', 'like', '%'.$key.'%');
        }
        if(!is_null($id_categorie)){
            $produits->where('category_id', $id_categorie);
        }
        if(!is_null($price_range)){
            $produits->whereBetween('prix', explode('-', $price_range));
        }

        if($request->sort_by == 'lowest_price'){
            $$produits = produits::orderBy('prix','asc')->get();
        }
        if($request->sort_by == 'highest_price'){
            $$produits = produits::orderBy('prix','desc')->get();
        }
        if(!is_null($ordre_affichage)){
            //if is not null will be Asc or Desc
            $produits->orderBy('prix', $ordre_affichage ? 'asc' : 'desc');
        }
        

        $produits = $produits->paginate(16);
        
        $total_produit = produits::count();
        $max_prix = produits::max('prix');
      // $categories = Category::with('produits')->get();
      $categories =Category::has('produits')->get();

    //  $lastproduits = produits::latest()->take(5)->get();

      
    
   
        $configs= config::all();
        return view('front.shop.index',compact('produits', 'categories', 'configs','key','total_produit','max_prix','ordre_affichage'));
    }

    public function search_products(Request $request)
    {
        $all_products = produits::whereBetween('prix',[$request->left_value, $request->right_value])->get();
        return view('front.shop.index',compact('all_products'))->render();
    }

    public function sort_by(Request $request)
    {
      
        //$produits = produits::query();

       
        if($request->sort_by == 'lowest_price'){
            $$produits = produits::orderBy('prix','asc')->get();
        }
        if($request->sort_by == 'highest_price'){
            $$produits = produits::orderBy('prix','desc')->get();
        }
        
       // $produits = $produits->paginate(24);
       $categories =Category::has('produits')->get();
    
   
       $configs= config::all();
        $total_produit = produits::count();
        $max_prix = produits::max('prix');
      // $categories = Category::with('produits')->get();
      $categories =Category::has('produits')->get();
        return view('front.shop.index',compact('produits','categories', 'configs'))->render();

    }


    public function decroissant(){
        $produits= produits:: select('*')
        ->orderBy('prix','DESC')
        ->paginate(24);;
      //  $categories = Category::with('produits')->get();
      $categories =Category::has('produits')->get();
        $configs= config::all();
        return view('front.shop.index',compact('produits', 'categories','configs'));
    }


    public function croissant(){
        $produits= produits:: select('*')
        ->orderBy('prix','ASC')
        ->paginate(24);;
      //  $categories = Category::with('produits')->get();
      $categories =Category::has('produits')->get();
        $configs= config::all();
        return view('front.shop.index',compact('produits', 'categories','configs'));
    }
    


    public function search(SearchRequest $request)
    {
        $search = $request->search;
      
        $produits = produits::where('nom', 'like', '%'.$search.'%')
        //    ->orWhere('description', 'like', '%'.$search.'%')
            ->paginate(10);
        $title = __('Produits nont trouvé avec la recherche: ') . '<strong>' . $search . '</strong>';
     
        return view('front.shop.index', compact('produits', 'title'));
    }




    public function produits($id)
    {
        $categories = Category::with('produits')->get();
        $current_category = Category::with('produits')->findOrFail($id);
        $produits = $current_category->produits()->paginate(10);

        $marques = Marque::with('produits')->get();
        $current_marque = Marque::with('produits')->findOrFail($id);
        $produits = $current_marque->produits()->paginate(10);
        $users = User::all();

        //dd($produits);
        return view('front.shop.index', compact('current_category','current_marque','marques', 'users', 'categories', 'produits'));
    }

    public function details($id){
        $produit =produits:: findOrFail($id);
        $configs= config::all();
        return view('front.shop.details', compact('produit','configs'));
    }
    
    public function products($id)
    {
        $categories = Category::with('produits')->get();
        $current_category = Category::with('produits')->findOrFail($id);
       // $produits = $current_category->produits;
       // $produits = $produits()->paginate(16);
       $produits = $current_category->produits()->paginate(24);
        $users = User::all();  
        return view('front.shop.index', compact('current_category', 'users', 'categories', 'produits'));
    }



    ///////////Login///////////////////////////////////////////////////
    public function login()
    {
        return view('auth.login');
    }
   

    public function Not_Autorized(){
        $template = templates::where('meta_error',true)->first();
        $meta = $template->meta ?? "";
        return view("front.Not_Autorized", compact("meta"));
    }

    public function voir_template($slug)
    {
        $template = templates::where('reference', $slug)->first();
        if ($template) {
            $code = Storage::get($template->code);
            return view("front.template", compact("template","code"));
        } else {
            abort(404, "template introuvable");
        }
    }

    public function print_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            abort('404');
        }

        $pdf = PDF::loadView('pdf.commande', compact('commande'));
        return $pdf->download("Facture-#" . $commande->id . ".pdf");
    }

    public function print($id)
{
    $commande = commandes::with('contenus.produit')->findOrFail($id);
    $pdf = Pdf::loadView('pdf.commande', compact('commande'));
    return $pdf->stream('recu_commande_'.$commande->reference.'.pdf');
}


    public function print_bordereau(Request $request, $ids)
    {
        $ids = json_decode($request->get('ids'));
        $pdf = PDF::loadView('pdf.bordereau', compact("ids"));
        return $pdf->download("bordereau.pdf");
    }



    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }


    public function inscription()
    {
        return view('front.inscription');
    }


    public function confirmation(Request $request){
        $produit = null;
        if ($request->session()->has('produit')) {
            $produit = Session::get('produit');
        }
        return view('front.confirmation',compact("produit"));
    }


}
