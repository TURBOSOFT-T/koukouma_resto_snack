<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}


    <div class="shop">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="shop-sidebar">
                        <div class="shop-sidebar__content">
                            <div class="shop-sidebar__section -categories">
                                <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
                                    <h2>Categories</h2><img
                                        src="assets/images/introduction/IntroductionOne/content-deco.png"
                                        alt="Decoration" />
                                </div>
                                <ul>
                                    
                                    @foreach ($categories as $category)
                                    <li>
                                        <a href="/shop?id_categorie={{ $category->id }}" class="small"
                                            class="{{ isset($current_category) && $current_category->id === $category->id ? 'selected' : '' }}">
                                            {{ Str::limit($category->nom, 25) }}
                                        </a>

                                    </li>

                                    @endforeach
                                </ul>
                            </div>
                            <div class="shop-sidebar__section -refine">
                                <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
                                    <h2>Refine Search</h2><img
                                        src="assets/images/introduction/IntroductionOne/content-deco.png"
                                        alt="Decoration" />
                                </div>
                                <div class="shop-sidebar__section__item">
                                    <h5>Marques</h5>
                                    <ul>
                                        @php
                                        $marques = DB::table('marques')->latest()->take(20)->get();
                                        $counter=0;
                                        @endphp
                                        
                                        @foreach ($marques as $marque)
                                        <li>
                                            <label for="brand-0">
                                                <input type="checkbox"  name="brandInputs" value="{{ $marque->nom
                                                }}"  />{{ $marque->nom
                                                }}
                                            </label>
                                        </li>
                                        @endforeach




                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop-header">
                        <div class="shop-header__view">
                            <div class="shop-header__view__icon"><a class="active" href="#"><i
                                        class="fas fa-th"></i></a><a href="#"><i class="fas fa-bars"></i></a>
                            </div>
                            <h5 class="shop-header__page">Shop Fullwidth 4 Columns</h5>
                        </div>
                        <select class="customed-select" name="#">
                            <option value="az">A to Z</option>
                            <option value="za">Z to A</option>
                            <option value="low-high">Croissant</option>
                            <option value="high-low">Décroissant</option>
                        </select>
                    </div>
                    <div class="shop-products">
                        <div class="shop-products__gird">
                            <div class="row">
                                @forelse ($produits as $key => $produit)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="product ">
                                        <div class="product-type">
                                            <h5 class="-new">New</h5>
                                        </div>
                                        <div class="product-thumb"><a class="product-thumb__image"
                                                href="{{ url('details-produits', $produit->id) }}"><img
                                                    src="{{ Storage::url($produit->photo) }}" width="200 "
                                                    height="200 " border-radius="8px" alt="Product image" /></a>
                                            <div class="product-thumb__actions">
                                                <div class="product-btn">

                                                    <button type="button"
                                                        class="btn -white product__actions__item -round product-atc"
                                                        onclick="AddToCart( {{ $produit->id }} )">
                                                        <i class="fas fa-shopping-bag"></i>
                                                    </button>
                                                </div>
                                                <div class="product-btn"><a
                                                        class="btn -white product__actions__item -round product-qv"
                                                        href="#"><i class="fas fa-eye"></i></a>
                                                </div>
                                                <div class="product-btn">

                                                    <button type="button"
                                                        class="btn -white product__actions__item -round"
                                                        onclick="AddFavoris({{ $produit->id }})">
                                                        <i class="fas fa-heart"></i>

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-content__header">

                                                <div class="rate"><i class="fas fa-star"></i><i
                                                        class="fas fa-star"></i><i class="fas fa-star"></i><i
                                                        class="fas fa-star"></i><i class="far fa-star"></i>
                                                </div>
                                            </div><a class="product-name"
                                                href="{{ url('details-produits', $produit->id) }}">{{ $produit->nom
                                                }}</a>
                                            <div class="product-content__footer">
                                                <h5 class="product-price--main">
                                                    @if ($produit->inPromotion())
                                                    <span class=" small">
                                                        - {{ $produit->inPromotion()->pourcentage }} %
                                                    </span>
                                                    <b class="text-success">
                                                        {{ $produit->getPrice() }} DT
                                                    </b>
                                                    <br>
                                                    <strike>
                                                        <span class="text-danger small">
                                                            {{ $produit->prix }} DT
                                                        </span>
                                                    </strike>
                                                    @else
                                                    {{ $produit->getPrice() }} DT
                                                    @endif
                                                </h5>
                                                <div class="product-colors">
                                                    {{-- <div class="product-colors__item"
                                                        style="background-color: #8B0000"></div>
                                                    <div class="product-colors__item"
                                                        style="background-color: #4169E1"></div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach


                            </div>
                        </div>
                        <div class="shop-products__list">
                            <div class="row">
                                @forelse ($produits as $key => $produit)
                                <div class="col-12">
                                    <div class="product-list">
                                        <div class="product-list-thumb">
                                            <div class="product-type">
                                                <h5 class="-new">New</h5>
                                            </div><a class="product-list-thumb__image"
                                                href="{{ url('details-produits', $produit->id) }}"><img
                                                    src="{{ Storage::url($produit->photo) }}" width="200 "
                                                    height="200 " border-radius="8px" alt="Product image" /></a>
                                        </div>
                                        <div class="product-list-content">
                                            <div class="product-list-content__top">
                                                <div class="product-category__wrapper">
                                                </div><a class="product-name"
                                                    href="{{ url('details-produits', $produit->id) }}">{{
                                                    $produit->nom }}</a>
                                                <div class="product__price">
                                                    <div class="product__price__wrapper">
                                                        <h5 class="product-price--main">
                                                            @if ($produit->inPromotion())
                                                            <span class=" small">
                                                                -
                                                                {{ $produit->inPromotion()->pourcentage }}
                                                                %
                                                            </span>
                                                            <b class="text-success">
                                                                {{ $produit->getPrice() }} DT
                                                            </b>
                                                            <br>
                                                            <strike>
                                                                <span class="text-danger small">
                                                                    {{ $produit->prix }} DT
                                                                </span>
                                                            </strike>
                                                            @else
                                                            {{ $produit->getPrice() }} DT
                                                            @endif
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-list-content__bottom">
                                                <p class="product-description">
                                                    {{ Str::limit($produit->description, 50) }} </p>
                                                <div class="product-actions">
                                                    <div class="product-btn">
                                                        <div class="add-to-cart ">
                                                            <button type="button" class="btn -round -red"
                                                                onclick="AddToCart( {{ $produit->id }} )">
                                                                <i class="fas fa-shopping-bag"></i>
                                                            </button>
                                                            <h5>Add to cart</h5>
                                                        </div>


                                                    </div>
                                                    <div class="product-btn"><a
                                                            class="btn -white product__actions__item -round"
                                                            href="#"><i class="fas fa-eye"></i></a>
                                                    </div>
                                                    <div class="product-btn">

                                                        <button type="button"
                                                            class="btn -white product__actions__item -round"
                                                            onclick="AddFavoris({{ $produit->id }})">
                                                            <i class="fas fa-heart"></i>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                    <ul class="paginator">
                        <li class="page-item active">
                            <button class="page-link">1</button>
                        </li>
                        <li class="page-item">
                            <button class="page-link">2</button>
                        </li>
                        <li class="page-item">
                            <button class="page-link"><i class="far fa-angle-right"></i></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-one">


        <div class="modal" id="quick-view-modal">
            <div class="product-quickview">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="product-detail__slide-one">
                            <div class="slider-wrapper">
                                <div class="slider-item"><img src="{{ Storage::url($produit->photo) }}" width="300 "
                                        height="300 " border-radius="8px" alt="Product image" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="product-detail__content">
                            <div class="product-detail__content__header">

                                <h2>{{ $produit->nom }}</h2>
                            </div>
                            <div class="product-detail__content__header__comment-block">
                                {{-- <div class="rate"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="far fa-star"></i></div>
                                <p>03 review</p><a href="#">Write a reviews</a> --}}
                                {{ $produit->description }}
                            </div>
                            <h3>
                                @if ($produit->inPromotion())
                                <span class=" small">
                                    - {{ $produit->inPromotion()->pourcentage }} %
                                </span>
                                <b class="text-success">
                                    {{ $produit->getPrice() }} DT
                                </b>
                                <br>
                                <strike>
                                    <span class="text-danger small">
                                        {{ $produit->prix }} DT
                                    </span>
                                </strike>
                                @else
                                {{ $produit->getPrice() }} DT
                                @endif
                            </h3>
                            <div class="divider"></div>
                            <div class="product-detail__content__footer">
                                <ul>
                                    @if ($produit->stock > 0)
                                    <label class="badge bg-success"> Stock disponible</label>
                                    @else
                                    <label class="badge bg-danger"> Stock non disponible</label>
                                    @endif

                                    <li>Categorie:<span> {{ Str::limit($produit->categories->nom, 30) }}</span>
                                    </li>
                                </ul>
                                {{-- <div class="product-detail__colors"><span>Color:</span> --}}
                                    {{-- <div class="product-detail__colors__item"
                                        style="background-color: #8B0000"></div>
                                    <div class="product-detail__colors__item" style="background-color: #4169E1">
                                    </div>
                                    --}}
                                </div>
                                <div class="product-detail__controller">
                                    <div class="quantity-controller -border -round">
                                        <div class="quantity-controller__btn -descrease">-</div>
                                        <div class="quantity-controller__number">2</div>
                                        <div class="quantity-controller__btn -increase">+</div>
                                    </div>
                                    <div class="add-to-cart -dark">
                                        <button type="button" class="btn -round -red"
                                            onclick="AddToCart( {{ $produit->id }} )">
                                            <i class="fas fa-shopping-bag"></i>
                                        </button>
                                    </div>
                                    <div class="product-detail__controler__actions">
                                        <button type="button" class="btn -round -white"
                                            onclick="AddFavoris({{ $produit->id }})">
                                            <i class="fas fa-heart"></i>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
