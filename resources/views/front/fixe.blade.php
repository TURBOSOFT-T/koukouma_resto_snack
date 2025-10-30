@include('sweetalert::alert')
@php
$config = DB::table('configs')->first();
$service = DB::table('services')->get();
$produit = DB::table('produits')->get();
@endphp

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Koukouma Martket</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ Storage::url($config->icon ?? ' ') }}">

    <!-- CSS
    ============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/vendor/flaticon/flaticon.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick-theme.css">
    <link rel="stylesheet" href="/assets/css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/sal.css">
    <link rel="stylesheet" href="/assets/css/vendor/magnific-popup.css">
    <link rel="stylesheet" href="/assets/css/vendor/base.css">
    <link rel="stylesheet" href="/assets/css/style.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Script.js"></script>
    @yield('header')

</head>


<body class="sticky-header overflow-md-visible">
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->
    <a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>
    <!-- Start Header -->
   <header class="header axil-header header-style-5">
            <div class="axil-header-top"  style=" background-color: #a42f5a;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="header-top-dropdown">
                                <div class="dropdown">
                                    <form action="{{ route('locale.change') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            {{ app()->getLocale() == 'fr' ? 'Français' : 'English' }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <button type="submit" name="locale" value="fr" class="dropdown-item">
                                                <img src="https://img.icons8.com/color/20/france-circular.png" alt="fr">
                                                Français
                                            </button>
                                            <button type="submit" name="locale" value="en" class="dropdown-item">
                                                <img src="https://img.icons8.com/color/20/great-britain-circular.png"
                                                    alt="en">
                                                English
                                            </button>

                                        </ul>
                                    </form>
                                </div>
                                <div class="dropdown">

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="header-top-link">
                                <ul class="quick-link">
                                    <li><a href="#">Help</a></li>
                                    <li><a href="sign-up.html">Join Us</a></li>
                                    <li><a href="sign-in.html">Sign In</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="axil-sticky-placeholder"></div>
            <div class="axil-mainmenu">
                <div class="container">
                    <div class="header-navbar">
                        <div class="header-brand">
                            <a class="nav-brand" href="{{ route('home') }}">

                                <img src="{{ Storage::url($config->logo ?? ' ') }}" alt="Logo" />
                            </a>

                            <style>
                                .nav-brand {
                                    display: flex;
                                    align-items: center;
                                    text-decoration: none;
                                    padding: 0px;
                                }

                                .nav-brand img {
                                    height: 90px;
                                    width: 80px;
                                    object-fit: contain;
                                    transition: transform 0.3s ease;
                                    margin-top: -11px;
                                }

                                @media (max-width: 768px) {
                                    .nav-brand img {
                                        height: 100px;
                                        width: 100px;
                                        margin-top: 30;
                                        padding: 10;
                                        margin-left: 20px;



                                    }
                                }

                                .menu-toggle {
                                    display: none;
                                    font-size: 2em;
                                    cursor: pointer;
                                    margin-left: auto;
                                }


                                .nav-brand:hover img {
                                    transform: scale(1.1);
                                }


                                .navbar .nav-brand {
                                    padding: 5px;
                                }

                                .navbar .nav-brand img {
                                    max-height: 50px;
                                }
                            </style>

                        </div>
                        <div class="header-main-nav">

                            <nav class="mainmenu-nav">
                                <button class="mobile-close-btn mobile-nav-toggler"><i
                                        class="fas fa-times"></i></button>
                                <div class="mobile-nav-brand">
                                    <a href="{{ route('home') }}" class="logo">
                                        <img href="{{ Storage::url($config->logo ?? '') }}" alt="Site Logo">
                                    </a>
                                </div>
                                <ul class="mainmenu">
                                    <li><a href="{{ route('home') }}">{{ __('accueil') }}</a>

                                    </li>


                                    <li><a href="{{ route('shop') }}">{{ __('boutique') }}</a></li>
                                    </li class="menu-item">
                                    <li><a href="{{ route('about') }}">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('A propos de nous') }}
                                        </a></li>



                                    <li><a href="{{ route('contact') }}">Contact</a></li>


                                    @guest
                                    @else
                                    @if (auth()->user()->role != 'client')
                                    <li><a href="{{ url('dashboard') }}" class="nav-item nav-link">Dashboard</a>
                                    </li>
                                    @endif







                                    @endguest
                                </ul>
                            </nav>

                        </div>
                        <div class="header-action">
                            <ul class="action-list">
                                <li class="axil-search d-xl-block d-none">
                                    <input type="search" class="placeholder product-search-input" name="search2"
                                        id="search2" value="" maxlength="128"
                                        placeholder="{{ \App\Helpers\TranslationHelper::TranslateText(" Rechercher
                                        produit") }}" autocomplete="off">
                                    <button type="submit" class="icon wooc-btn-search">
                                        <i class="flaticon-magnifying-glass"></i>
                                    </button>
                                </li>
                                <li class="axil-search d-xl-none d-block">
                                    <a href="javascript:void(0)" class="header-search-icon" title="Search">
                                        <i class="flaticon-magnifying-glass"></i>
                                    </a>
                                </li>
                                <li class="wishlist">
                                    <a href="{{ route('favories') }}">
                                        <i class="flaticon-heart"></i>
                                    </a>
                                </li>
                                <li class="shopping-cart">
                                    <a href="#" class="cart-dropdown-btn">
                                        <span class="cart-count" id="count-panier-span">00</span>
                                        <i class="flaticon-shopping-cart"></i>
                                    </a>
                                </li>

                                <li class="my-account">
                                    <a href="javascript:void(0)">
                                        <i class="far fa-user"></i>
                                    </a>
                                    <div class="my-account-dropdown">

                                        @if (Auth()->user())
                                        <ul>
                                            @if (auth()->user()->role != 'client')
                                            <li><a href="{{ url('dashboard') }}">Dashboard</a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('account') }}">Mon compte</a>
                                            </li>

                                            <li>
                                                <a href="{{ route('favories') }}">Mes favoris</a>
                                            </li>

                                            <li>
                                                <a href="{{ route('cart') }}">Mon panier</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('profile') }}">{{ __('parametres') }}
                                                </a>
                                            </li>
                                            <li>

                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();   document.getElementById('logout-form').submit();">
                                                    Déconnexion
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                            </li>




                                        </ul>
                                        @else
                                        <div class="login-btn">
                                            <a href="{{ url('login') }}" class="axil-btn btn-bg-primary">Connexion</a>
                                        </div>

                                        <div class="reg-footer text-center">Pas de compte? <a
                                                href="{{ url('register') }}" class="btn-link">S'inscrire ici.</a>
                                        </div>
                                        @endif

                                    </div>


                                    
                                </li>

                                
                        <style>
                            .custom-dropdown {
                                position: relative;
                                display: inline-block;
                            }

                            .dropbtn {
                                background-color: #ffffff;
                                color: #000;
                                padding: 10px 15px;
                                font-size: 16px;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                            }

                            .dropbtn img {
                                margin-right: 8px;
                            }

                            .dropdown-content {
                                display: none;
                                position: absolute;
                                background-color: #ffffff;
                                min-width: 150px;
                                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                                z-index: 1;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            }

                            .dropdown-content .dropdown-item {
                                display: flex;
                                align-items: center;
                                padding: 10px 15px;
                                background-color: transparent;
                                border: none;
                                width: 100%;
                                text-align: left;
                                cursor: pointer;
                            }

                            .dropdown-content .dropdown-item img {
                                margin-right: 8px;
                            }

                            .dropdown-content .dropdown-item:hover {
                                background-color: #f0f0f0;
                            }

                            .dropdown:hover .dropdown-content {
                                display: block;
                            }

                        </style>


                        <div class="custom-dropdown">
                            <form action="{{ route('locale.change') }}" method="POST">
                                @csrf
                                <div class="dropdown">
                                    <button class="dropbtn">
                                        {{ app()->getLocale() == 'fr' ? 'Français' : 'English' }}
                                    </button>
                                    <div class="dropdown-content">
                                        <button type="submit" name="locale" value="fr" class="dropdown-item">
                                            <img src="https://img.icons8.com/color/20/france-circular.png" alt="fr">
                                            Français
                                        </button>
                                        <button type="submit" name="locale" value="en" class="dropdown-item">
                                            <img src="https://img.icons8.com/color/20/great-britain-circular.png" alt="en">
                                            English
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>


                                <li class="axil-mobile-toggle">
                                    <button class="menu-btn mobile-nav-toggler">
                                        <i class="flaticon-menu-2"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
          
        </header>
    <!-- End Header -->



    <main>



        @yield('body')




    </main>

    <footer class="axil-footer-area footer-style-2">
        <!-- Start Footer Top Area  -->
        <div class="footer-top separator-top">
            <div class="container">
                <div class="row">
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title"></h5>
                            <style>
                                .logo {
                                    position: relative;
                                    top: -30px;
                                    /* Déplace le logo de 30px vers le haut */
                                }

                            </style>
                            <div class="logo mb--30">
                                <a href="{{ route('home') }}">
                                    <img class="light-logo" src="{{ Storage::url($config->logofooter ?? ' ') }}" alt="Logo" height="200" width="200">
                                </a>
                            </div>

                            <p class="logo" style="font-size: 18px; line-height: 1.6; text-align: justify;">
                             
                                {!! \App\Helpers\TranslationHelper::TranslateText($config->description) !!}
                            </p>


                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title">  {{ \App\Helpers\TranslationHelper::TranslateText('Mon compte') }}</h5>
                            <div class="inner">
                                <ul>
                                    @if (Auth()->user())
                                    <li><a href="{{ route('profile') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('Paramètres') }}</a></li>
                                    <li><a href="{{ route('favories') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('Mes favoris') }}</a></li>
                                    <li><a href="{{ route('cart') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('Mon panier') }}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title">  {{ \App\Helpers\TranslationHelper::TranslateText(' Pages') }}</h5>
                            <div class="inner">
                                <ul>
                                    <li><a href="{{ route('home') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('Accueil') }}</a></li>
                                    <li><a href="{{ route('about') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('A propos de nous') }}</a></li>

                                    <li><a href="{{ route('shop') }}">   {{ \App\Helpers\TranslationHelper::TranslateText('Produits') }}</a></li>
                                    <li><a href="{{ route('contact') }}">  {{ \App\Helpers\TranslationHelper::TranslateText('Contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title">
                                {{ \App\Helpers\TranslationHelper::TranslateText('Contact info') }}
                            </h5>
                            <div class="inner">
                                {{-- <span>Save $3 With App & New User only</span> --}}
                                <div class="download-btn-group">

                                    <div class="inner">

                                        <ul class="support-list-item">
                                            <li><a href="mailto:example@domain.com"><i class="fal fa-envelope-open"></i>
                                                    {{ $config->email ?? ' ' }}</a></li>
                                            <li><a href="tel:(+01)850-315-5862"><i class="fal fa-phone-alt"></i>{{ $config->telephone ?? ' ' }}</a>
                                            </li>
                                            <li><i class="fal fa-map-marker-alt"></i>{{ $config->addresse ?? ' ' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                </div>
            </div>
        </div>
        <!-- End Footer Top Area  -->
        <!-- Start Copyright Area  -->
        <div class="copyright-area copyright-default separator-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-4">

                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="copyright-left d-flex flex-wrap justify-content-center">
                            <ul class="quick-link">
                                <li>©{{ date('Y') }} Koukouma Market | Design By<a href="#" style="color: #c71f17;">
                                        <b> TUROSOFT </b>
                                    </a>.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="copyright-right d-flex flex-wrap justify-content-xl-end justify-content-center align-items-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Copyright Area  -->
    </footer>
    <!-- End Footer Top Area  -->


    <!-- End Footer Area  -->


    <!-- Product Quick View Modal Start -->
    <div class="modal fade quick-view-product" id="quick-view-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="single-product-thumb">
                        <div class="row">
                            <div class="col-lg-7 mb--40">
                                <div class="row">
                                    <div class="col-lg-10 order-lg-2">
                                        <div class="single-product-thumbnail product-large-thumbnail axil-product thumbnail-badge zoom-gallery">
                                            <div class="thumbnail">
                                                <img src="assets/images/product/product-big-01.png" alt="Product Images">
                                                <div class="label-block label-right">
                                                    <div class="product-badget">20% OFF</div>
                                                </div>
                                                <div class="product-quick-view position-view">
                                                    <a href="assets/images/product/product-big-01.png" class="popup-zoom">
                                                        <i class="far fa-search-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="thumbnail">
                                                <img src="assets/images/product/product-big-02.png" alt="Product Images">
                                                <div class="label-block label-right">
                                                    <div class="product-badget">20% OFF</div>
                                                </div>
                                                <div class="product-quick-view position-view">
                                                    <a href="assets/images/product/product-big-02.png" class="popup-zoom">
                                                        <i class="far fa-search-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="thumbnail">
                                                <img src="assets/images/product/product-big-03.png" alt="Product Images">
                                                <div class="label-block label-right">
                                                    <div class="product-badget">20% OFF</div>
                                                </div>
                                                <div class="product-quick-view position-view">
                                                    <a href="assets/images/product/product-big-03.png" class="popup-zoom">
                                                        <i class="far fa-search-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 order-lg-1">
                                        <div class="product-small-thumb small-thumb-wrapper">
                                            <div class="small-thumb-img">
                                                <img src="assets/images/product/product-thumb/thumb-08.png" alt="thumb image">
                                            </div>
                                            <div class="small-thumb-img">
                                                <img src="assets/images/product/product-thumb/thumb-07.png" alt="thumb image">
                                            </div>
                                            <div class="small-thumb-img">
                                                <img src="assets/images/product/product-thumb/thumb-09.png" alt="thumb image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 mb--40">
                                <div class="single-product-content">
                                    <div class="inner">
                                        <div class="product-rating">
                                            <div class="star-rating">
                                                <img src="assets/images/icons/rate.png" alt="Rate Images">
                                            </div>
                                            <div class="review-link">
                                                <a href="#">(<span>1</span> customer reviews)</a>
                                            </div>
                                        </div>
                                        <h3 class="product-title">Serif Coffee Table</h3>
                                        <span class="price-amount">$155.00 - $255.00</span>
                                        <ul class="product-meta">
                                            <li><i class="fal fa-check"></i>In stock</li>
                                            <li><i class="fal fa-check"></i>Free delivery available</li>
                                            <li><i class="fal fa-check"></i>Sales 30% Off Use Code: MOTIVE30</li>
                                        </ul>
                                        <p class="description">In ornare lorem ut est dapibus, ut tincidunt nisi
                                            pretium. Integer ante est, elementum eget magna. Pellentesque sagittis
                                            dictum libero, eu dignissim tellus.</p>

                                        <div class="product-variations-wrapper">

                                            <!-- Start Product Variation  -->
                                            <div class="product-variation">
                                                <h6 class="title">Colors:</h6>
                                                <div class="color-variant-wrapper">
                                                    <ul class="color-variant mt--0">
                                                        <li class="color-extra-01 active"><span><span class="color"></span></span>
                                                        </li>
                                                        <li class="color-extra-02"><span><span class="color"></span></span>
                                                        </li>
                                                        <li class="color-extra-03"><span><span class="color"></span></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- End Product Variation  -->

                                            <!-- Start Product Variation  -->
                                            <div class="product-variation">
                                                <h6 class="title">Size:</h6>
                                                <ul class="range-variant">
                                                    <li>xs</li>
                                                    <li>s</li>
                                                    <li>m</li>
                                                    <li>l</li>
                                                    <li>xl</li>
                                                </ul>
                                            </div>
                                            <!-- End Product Variation  -->

                                        </div>

                                        <!-- Start Product Action Wrapper  -->
                                        <div class="product-action-wrapper d-flex-center">
                                            <!-- Start Quentity Action  -->
                                            <div class="pro-qty"><input type="text" value="1"></div>
                                            <!-- End Quentity Action  -->

                                            <!-- Start Product Action  -->
                                            <ul class="product-action d-flex-center mb--0">
                                                <li class="add-to-cart"><a href="cart.html" class="axil-btn btn-bg-primary2">Add to Cart</a></li>
                                                <li class="wishlist"><a href="wishlist.html" class="axil-btn wishlist-btn"><i class="far fa-heart"></i></a>
                                                </li>
                                            </ul>
                                            <!-- End Product Action  -->

                                        </div>
                                        <!-- End Product Action Wrapper  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Quick View Modal End -->

    <!-- Header Search Modal End -->
    <div class="header-search-modal" id="header-search-modal">
        <button class="card-close sidebar-close"><i class="fas fa-times"></i></button>
        <div class="header-search-wrap">
            <div class="card-header">
                <form role="search" action="{{ url('search') }}" method="get">
                    <div class="input-group">
                        <input value="{{ $nom ?? '' }}" class="form-control" id="search" type="search" name="search" placeholder="  {{ \App\Helpers\TranslationHelper::TranslateText('Rechercher produit') }}">

                        <button type="submit" class="axil-btn btn-bg-primary"><i class="far fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="search-result-header">
                    <h6 class="title"></h6>
                    <a href="{{ route('shop') }}" class="view-all">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Voir tout') }}
                    </a>
                </div>
                <div class="psearch-results">
                    @if (isset($searchproducts))
                    @foreach ($searchproducts as $produit)
                    <div class="axil-product-list">
                        <div class="thumbnail">
                            <a href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                <img width="100" height="100" src="{{ Storage::url($produit->photo) }}" alt="Yantiti Leather Bags">
                            </a>
                        </div>
                        <div class="product-content">
                            <div class="product-rating">
                                {{-- <span class="rating-icon">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fal fa-star"></i>
                            </span>
                                <span class="rating-number"><span>100+</span> Reviews</span> --}}
                            </div>
                            <h6 class="product-title"><a href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                {!! \App\Helpers\TranslationHelper::TranslateText($produit->nom ?? '') !!}
                              </a>
                            </h6>

                            <div class="product-price-variant">
                                @if ($produit->inPromotion())
                                <span class="price current-price"><b class="text-success" style="color: #4169E1">
                                        {{ $produit->getPrice() }} <x-devise></x-devise>
                                    </b></span>
                                <span class="price old-price">
                                    <span class="price old-price" style="position: relative; font-size: 1.2rem; color: #dc3545; font-weight: bold;">
                                        {{ $produit->prix }}  <x-devise></x-devise>
                                        <span style="position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background-color: black;"></span>
                                    </span>
                                </span>
                                @else
                                {{ $produit->getPrice() }} <x-devise></x-devise>
                                @endif

                            </div>
                            <div class="product-cart">
                                <a onclick="AddToCart( {{ $produit->id }} )" class="cart-btn"><i class="fal fa-shopping-cart"></i></a>
                                @if (Auth()->user())
                                <a onclick="AddFavoris({{ $produit->id }})" class="cart-btn"><i class="fal fa-heart"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Header Search Modal End -->




    <div class="cart-dropdown" id="cart-dropdown">
        <div class="cart-content-wrap">
            <div class="cart-header">
                <h2 class="header-title">  {{ \App\Helpers\TranslationHelper::TranslateText('Mon panier') }}</h2>
                <button class="cart-close sidebar-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="cart-body">
                <ul class="cart-item-list" id="list_content_panier">

                    {{-- <div class="cart-item row" id="list_content_panier">

                    </div> --}}


                </ul>
            </div>
            <div class="cart-footer">
                <h3 class="cart-subtotal">
                    <span class="subtotal-title">Subtotal:</span>
                    <span class="subtotal-amount" id="montant_total_panier">00</span>
                </h3>
                <div class="group-btn">
                    <a href="{{ route('cart') }}" class="axil-btn btn-bg-primary2 viewcart-btn">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Voir panier') }}
                    </a>
                    <a href="{{ url('/commander') }}" class="axil-btn btn-bg-secondary2 checkout-btn">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Passer commande') }}
                        </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-bg-primary2 {
            background-color: #5EA13C;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-bg-secondary2 {
            background-color: #EFB121;
            /* Couleur de fond, bleu dans cet exemple */
            color: #ffffff;
            /* Couleur du texte, blanc dans cet exemple */
            border: none;
            padding: 10px 20px;
            /* Optionnel, ajuste la taille */
            border-radius: 5px;
            /* Optionnel, arrondit les coins */
            text-decoration: none;
            /* Supprime le soulignement */
        }

    </style>
    <!-- JS
============================================ -->
    <!-- Modernizer JS -->
    <script src="/assets/js/vendor/modernizr.min.js"></script>
    <!-- jQuery JS -->
    <script src="/assets/js/vendor/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="/assets/js/vendor/popper.min.js"></script>
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <script src="/assets/js/vendor/slick.min.js"></script>
    <script src="/assets/js/vendor/js.cookie.js"></script>
    <!-- <script src="assets/js/vendor/jquery.style.switcher.js"></script> -->
    <script src="/assets/js/vendor/jquery-ui.min.js"></script>
    <script src="/assets/js/vendor/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/js/vendor/jquery.countdown.min.js"></script>
    <script src="/assets/js/vendor/sal.js"></script>
    <script src="/assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="/assets/js/vendor/imagesloaded.pkgd.min.js"></script>
    <script src="/assets/js/vendor/isotope.pkgd.min.js"></script>
    <script src="/assets/js/vendor/counterup.js"></script>
    <script src="/assets/js/vendor/waypoints.min.js"></script>

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>

</body>

</html>
