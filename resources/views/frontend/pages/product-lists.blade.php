@extends('frontend.layouts.master')

@section('title', 'AUTOZONE-SHOP || Page de Produit')

@section('main-content')

    <!-- Fil d'Ariane -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Accueil<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Liste des produits</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Breadcrumbs -->
    <form action="{{ route('shop.filter') }}" method="POST">
        @csrf
        <!-- Product Style 1 -->
        <section class="product-area shop-sidebar shop-list shop section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="shop-sidebar">
                            <!-- Widget Unique -->
                            <div class="single-widget category">
                                <h3 class="title">Catégories</h3>
                                <ul class="categor-list">
                                    @php
                                        // $category = new Category();
                                        $menu = App\Models\Category::getAllParentWithChild();
                                    @endphp
                                    @if ($menu)
                                        <li>
                                            @foreach ($menu as $cat_info)
                                                @if ($cat_info->child_cat->count() > 0)
                                        <li><a
                                                href="{{ route('product-cat', $cat_info->slug) }}"><u>{{ $cat_info->title }}</u></a>
                                            <ul>
                                                @foreach ($cat_info->child_cat as $sub_menu)
                                                    <li><a
                                                            href="{{ route('product-sub-cat', [$cat_info->slug, $sub_menu->slug]) }}">{{ $sub_menu->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li><a href="{{ route('product-cat', $cat_info->slug) }}">{{ $cat_info->title }}</a>
                                        </li>
                                    @endif
                                    @endforeach
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <!--/ Fin du Widget Unique -->
                            <!-- Filtrer par prix -->
                            <div class="single-widget range">
                                <h3 class="title">Filtrer par prix</h3>
                                <div class="price-filter">
                                    <div class="price-filter-inner">
                                        @php
                                            $max = DB::table('products')->max('price');
                                        @endphp
                                        <div id="slider-range" data-min="0" data-max="{{ $max }}"></div>
                                        <div class="product_filter">
                                            <button type="submit" class="filter_button">Filtrer</button>
                                            <div class="label-input">
                                                <span>Plage de prix:</span>
                                                <input style="" type="text" id="amount" readonly />
                                                <input type="hidden" name="price_range" id="price_range"
                                                    value="@if (!empty($_GET['price'])) {{ $_GET['price'] }} @endif" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Fin du Filtrer par prix -->
                            <!-- Widget Récent -->
                            <div class="single-widget recent-post">
                                <h3 class="title">Publications récentes</h3>
                                @foreach ($recent_products as $product)
                                    <!-- Publication Unique -->
                                    @php
                                        $photo = explode(',', $product->photo);
                                    @endphp
                                    <div class="single-post first">
                                        <div class="image">
                                            <img src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
                                        </div>
                                        <div class="content">
                                            <h5><a
                                                    href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                            </h5>
                                            @php
                                                $org = $product->price - ($product->price * $product->discount) / 100;
                                            @endphp
                                            <p class="price">
                                                @if ($product->discount != 0)
                                                    <del class="text-muted">{{ number_format($product->price, 2) }}
                                                        DH</del>
                                                @endif
                                                {{ number_format($org, 2) }} DH
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Fin de la Publication Unique -->
                                @endforeach
                            </div>
                            <!--/ Fin du Widget Récent -->
                            <!-- Widget Marques -->
                            <div class="single-widget category">
                                <h3 class="title">Marques</h3>
                                <ul class="categor-list">
                                    @php
                                        $brands = DB::table('brands')
                                            ->orderBy('title', 'ASC')
                                            ->where('status', 'active')
                                            ->get();
                                    @endphp
                                    @foreach ($brands as $brand)
                                        <li><a href="{{ route('product-brand', $brand->slug) }}">{{ $brand->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!--/ Fin du Widget Marques -->
                        </div>

                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- Shop Top -->
                                <div class="shop-top">
                                    <div class="shop-shorter">
                                        <div class="single-shorter">
                                            <label>Afficher :</label>
                                            <select class="show" name="show" onchange="this.form.submit();">
                                                <option value="">Par défaut</option>
                                                <option value="9" @if (!empty($_GET['show']) && $_GET['show'] == '9') selected @endif>09
                                                </option>
                                                <option value="15" @if (!empty($_GET['show']) && $_GET['show'] == '15') selected @endif>15
                                                </option>
                                                <option value="21" @if (!empty($_GET['show']) && $_GET['show'] == '21') selected @endif>21
                                                </option>
                                                <option value="30" @if (!empty($_GET['show']) && $_GET['show'] == '30') selected @endif>30
                                                </option>
                                            </select>
                                        </div>
                                        <div class="single-shorter">
                                            <label>Trier par :</label>
                                            <select class='sortBy' name='sortBy' onchange="this.form.submit();">
                                                <option value="">Par défaut</option>
                                                <option value="title" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'title') selected @endif>
                                                    Nom</option>
                                                <option value="price" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'price') selected @endif>
                                                    Prix</option>
                                                <option value="category" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'category') selected @endif>
                                                    Catégorie</option>
                                                <option value="brand" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'brand') selected @endif>
                                                    Marque</option>
                                            </select>
                                        </div>
                                    </div>
                                    <ul class="view-mode">
                                        <li><a href="{{ route('product-grids') }}"><i class="fa fa-th-large"></i></a></li>
                                        <li class="active"><a href="javascript:void(0)"><i class="fa fa-th-list"></i></a>
                                        </li>
                                    </ul>
                                </div>

                                <!--/ End Shop Top -->
                            </div>
                        </div>
                        <div class="row">
                            @if (count($products))
                                @foreach ($products as $product)
                                    <!-- Commencer un élément de liste unique -->
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="{{ route('product-detail', $product->slug) }}">
                                                            @php
                                                                $photo = explode(',', $product->photo);
                                                            @endphp
                                                            <img class="default-img" src="{{ $photo[0] }}"
                                                                alt="{{ $photo[0] }}">
                                                            <img class="hover-img" src="{{ $photo[0] }}"
                                                                alt="{{ $photo[0] }}">
                                                        </a>
                                                        <div class="button-head">
                                                            <div class="product-action">
                                                                <a data-toggle="modal" data-target="#{{ $product->id }}"
                                                                    title="Vue rapide" href="#"><i
                                                                        class=" ti-eye"></i><span>Aperçu rapide</span></a>
                                                                <a title="Liste de souhaits"
                                                                    href="{{ route('add-to-wishlist', $product->slug) }}"
                                                                    class="wishlist" data-id="{{ $product->id }}"><i
                                                                        class=" ti-heart "></i><span>Ajouter à la liste de
                                                                        souhaits</span></a>
                                                            </div>
                                                            <div class="product-action-2">
                                                                <a title="Ajouter au panier"
                                                                    href="{{ route('add-to-cart', $product->slug) }}">Ajouter
                                                                    au panier</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-6 col-12">
                                                <div class="list-content">
                                                    <div class="product-content">
                                                        <div class="product-price">
                                                            @php
                                                                $after_discount =
                                                                    $product->price -
                                                                    ($product->price * $product->discount) / 100;
                                                            @endphp
                                                            <span>{{ number_format($after_discount, 2) }} DH</span>
                                                            @if ($product->discount > 0)
                                                                <del>{{ number_format($product->price, 2) }} DH</del>
                                                            @endif
                                                        </div>
                                                        <h3 class="title"><a
                                                                href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                                        </h3>
                                                    </div>
                                                    <p class="des pt-2">{!! html_entity_decode($product->summary) !!}</p>
                                                    <a href="javascript:void(0)" class="btn cart"
                                                        data-id="{{ $product->id }}">Acheter maintenant !</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin de l'élément de liste unique -->
                                @endforeach
                            @else
                                <h4 class="text-warning" style="margin:100px auto;">Il n'y a pas de produits.</h4>
                            @endif

                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="pagination">
                                    @php
                                        try {
                                            $products->appends(request()->query())->links('pagination::bootstrap-5');
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ End Product Style 1  -->
    </form>
    <!-- Modal -->
    @if ($products)
        @foreach ($products as $key => $product)
            <div class="modal fade" id="{{ $product->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    class="ti-close" aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row no-gutters">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Product Slider -->
                                    <div class="product-gallery">
                                        <div class="quickview-slider-active">
                                            @php
                                                $photo = explode(',', $product->photo);
                                                // dd($photo);
                                            @endphp
                                            @foreach ($photo as $data)
                                                <div class="single-slider">
                                                    <img src="{{ $data }}" alt="{{ $data }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- End Product slider -->
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="quickview-content">
                                        <h2>{{ $product->title }}</h2>
                                        <div class="quickview-ratting-review">
                                            <div class="quickview-ratting-wrap">
                                                <div class="quickview-ratting">
                                                    {{-- <i class="yellow fa fa-star"></i>
															<i class="yellow fa fa-star"></i>
															<i class="yellow fa fa-star"></i>
															<i class="yellow fa fa-star"></i>
															<i class="fa fa-star"></i> --}}
                                                    @php
                                                        $rate = DB::table('product_reviews')
                                                            ->where('product_id', $product->id)
                                                            ->avg('rate');
                                                        $rate_count = DB::table('product_reviews')
                                                            ->where('product_id', $product->id)
                                                            ->count();
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($rate >= $i)
                                                            <i class="yellow fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <a href="#"> ({{ $rate_count }} customer review)</a>
                                            </div>
                                            <div class="quickview-stock">
                                                @if ($product->stock > 0)
                                                    <span><i class="fa fa-check-circle-o"></i> {{ $product->stock }} in
                                                        stock</span>
                                                @else
                                                    <span><i class="fa fa-times-circle-o text-danger"></i>
                                                        {{ $product->stock }} out stock</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $after_discount =
                                                $product->price - ($product->price * $product->discount) / 100;
                                        @endphp
                                        <h3><small><del
                                                    class="text-muted">${{ number_format($product->price, 2) }}</del></small>
                                            ${{ number_format($after_discount, 2) }} </h3>
                                        <div class="quickview-peragraph">
                                            <p>{!! html_entity_decode($product->summary) !!}</p>
                                        </div>
                                        @if ($product->size)
                                            <div class="size">
                                                <h4>Size</h4>
                                                <ul>
                                                    @php
                                                        $sizes = explode(',', $product->size);
                                                        // dd($sizes);
                                                    @endphp
                                                    @foreach ($sizes as $size)
                                                        <li><a href="#" class="one">{{ $size }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form action="{{ route('single-add-to-cart') }}" method="POST">
                                            @csrf
                                            <div class="quantity">
                                                <!-- Input Order -->
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number"
                                                            disabled="disabled" data-type="minus" data-field="quant[1]">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="slug" value="{{ $product->slug }}">
                                                    <input type="text" name="quant[1]" class="input-number"
                                                        data-min="1" data-max="1000" value="1">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number"
                                                            data-type="plus" data-field="quant[1]">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!--/ End Input Order -->
                                            </div>
                                            <div class="add-to-cart">
                                                <button type="submit" class="btn">Add to cart</button>
                                                <a href="{{ route('add-to-wishlist', $product->slug) }}"
                                                    class="btn min"><i class="ti-heart"></i></a>
                                            </div>
                                        </form>
                                        <div class="default-social">
                                            <!-- ShareThis BEGIN -->
                                            <div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    <!-- Modal end -->
@endsection
@push('styles')
    <style>
        .pagination {
            display: inline-flex;
        }

        .w-5,
        .pagination p {
            display: none;
        }

        .filter_button {
            /* height:20px; */
            text-align: center;
            background: #fce001;
            padding: 8px 16px;
            margin-top: 10px;
            color: white;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    {{-- <script>
        $('.cart').click(function(){
            var quantity=1;
            var pro_id=$(this).data('id');
            $.ajax({
                url:"{{route('add-to-cart')}}",
                type:"POST",
                data:{
                    _token:"{{csrf_token()}}",
                    quantity:quantity,
                    pro_id:pro_id
                },
                success:function(response){
                    console.log(response);
					if(typeof(response)!='object'){
						response=$.parseJSON(response);
					}
					if(response.status){
						swal('success',response.msg,'success').then(function(){
							document.location.href=document.location.href;
						});
					}
					else{
                        swal('error',response.msg,'error').then(function(){
							// document.location.href=document.location.href;
						});
                    }
                }
            })
        });
	</script> --}}
    <script>
        $(document).ready(function() {
            /*----------------------------------------------------*/
            /*  Jquery Ui slider js
            /*----------------------------------------------------*/
            if ($("#slider-range").length > 0) {
                const max_value = parseInt($("#slider-range").data('max')) || 500;
                const min_value = parseInt($("#slider-range").data('min')) || 0;
                const currency = $("#slider-range").data('currency') || '';
                let price_range = min_value + '-' + max_value;
                if ($("#price_range").length > 0 && $("#price_range").val()) {
                    price_range = $("#price_range").val().trim();
                }

                let price = price_range.split('-');
                $("#slider-range").slider({
                    range: true,
                    min: min_value,
                    max: max_value,
                    values: price,
                    slide: function(event, ui) {
                        $("#amount").val(currency + ui.values[0] + " -  " + currency + ui.values[1]);
                        $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                    }
                });
            }
            if ($("#amount").length > 0) {
                const m_currency = $("#slider-range").data('currency') || '';
                $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
                    "  -  " + m_currency + $("#slider-range").slider("values", 1));
            }
        })
    </script>
@endpush
