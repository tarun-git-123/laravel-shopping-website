@extends('front.layout.app')
@section('content')  
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">            
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $key=>$category)
                                        <div class="accordion-item">
                                            @if ($category->sub_category->isNotEmpty())
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                                                        {{$category->name}}
                                                    </button>
                                                </h2>
                                            @else
                                            <a href="{{ route("front.shop",$category->slug) }}" class="nav-item nav-link {{ $categorySelected==$category->id?'text-primary':''}}">{{$category->name}}</a> 
                                            @endif
                                            @if($category->sub_category->isNotEmpty())
                                                <div id="collapse{{$key}}" class="accordion-collapse collapse {{ $categorySelected==$category->id?'show':''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach ($category->sub_category as $sub_catgory)
                                                            <a href="{{ route("front.shop",[$category->slug,$sub_catgory->slug]) }}" class="nav-item nav-link {{ $subCategorySelected==$sub_catgory->id?'text-primary':''}}">{{$sub_catgory->name}}</a>
                                                            @endforeach                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif  
                                        </div>
                                    @endforeach                
                                @endif                  
                            </div>
                        </div>
                    </div>
                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            @if ($brands->isNotEmpty())
                                @foreach ($brands as $key=>$brand)
                                <div class="form-check mb-2">
                                    <input class="form-check-input brand-label" type="checkbox" value="{{$brand->id}}" id="brand-{{$brand->id}}" {{ in_array($brand->id,$brandsArray)?'checked':''; }}>
                                    <label class="form-check-label" for="brand-{{$brand->id}}">
                                        {{$brand->name}}
                                    </label>
                                </div> 
                                @endforeach                
                            @endif                  
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <input type="text" class="js-range-slider" name="my_range" value="" />
                            {{-- <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    $0-$100
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    $100-$200
                                </label>
                            </div>                 
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    $200-$500
                                </label>
                            </div> 
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    $500+
                                </label>
                            </div>                  --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    {{-- <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Latest</a>
                                            <a class="dropdown-item" href="#">Price High</a>
                                            <a class="dropdown-item" href="#">Price Low</a>
                                        </div>
                                    </div>                                     --}}
                                    <select name="sort" id="sort">
                                        <option value="latest" {{ $sort=='latest'?'selected':'' }}>Latest</option>
                                        <option value="price_desc" {{ $sort=='price_desc'?'selected':'' }}>Price High</option>
                                        <option value="price_asc" {{ $sort=='price_asc'?'selected':'' }}>Price Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                @php
                                    $productImage = $product->product_images->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="{{ route('front.product',$product->slug) }}" class="img-responsive">
                                                @if (!empty($productImage))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image)}}" alt="{{$productImage}}">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('front-assets/image/no_image.jpg')}}" alt="{{$productImage}}">
                                                @endif
                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javsacript:void(0);" onclick="addToCart({{$product->id}})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>                            
                                            </div>
                                        </div>                        
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="{{ route('front.product',$product->slug) }}">{{ substr($product->title,0,60).'...' }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>Rs.{{ $product->price }}</strong></span>
                                                <span class="h6 text-underline"><del>Rs.{{ $product->compare_price }}</del></span>
                                            </div>
                                        </div>                        
                                    </div>                                               
                                </div>
                            @endforeach
                            <div class="col-md-12 pt-5">
                                <nav aria-label="Page navigation example">
                                    {{ $products->withQueryString()->links() }}
                                </nav>
                            </div>
                        @else
                            <h3>Product Not found</h3>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $('.brand-label').on('change',function(){
            apply_filter();
        })

        $("#sort").on('change',function(){
            apply_filter();
        });

        rangeSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: 50000,
            from: {{ $priceMin }},
            step: 100,
            to: {{ $priceMax }},
            grid: true,
            prefix: "Rs.",
            max_postfix: "+",
            skin: "round",
            onFinish: function () {
                apply_filter()
            }
        });
        var slider = $(".js-range-slider").data('ionRangeSlider');

        function apply_filter(){
            var brands = [];
            $('.brand-label').each(function(key,item){
                if($(this).is(":checked")==true){
                    brands.push($(this).val());
                }
            });
            var url = '{{ url()->current() }}?';
            // brands filter
            if(brands.length>0){
                url+='&brand='+brands.toString();
            }

            //price filter
            url+= '&price_min='+slider.result.from+'&price_max='+slider.result.to;

            // sorting filter
            url+='&sort='+$("#sort").val();
            
            window.location.href=url;
        }

    </script>
@endsection