@extends('front.layout.app')
@section('content')  
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ substr($product->title,0,10) }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">
                            @foreach ($product->product_images as $key=>$product_image)
                                <div class="carousel-item {{ $key==0?'active':''}} ">
                                    <img class="w-100 h-100" src="{{ asset('uploads/product/large/'.$product_image->image) }}" alt="{{ $product_image->image }}">
                                </div>
                            @endforeach
                            {{-- <div class="carousel-item active">
                                <img class="w-100 h-100" src="images/product-2.jpg" alt="Image">
                            </div>
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="images/product-3.jpg" alt="Image">
                            </div>
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="images/product-4.jpg" alt="Image">
                            </div> --}}
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="text-primary mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star-half-alt"></small>
                                <small class="far fa-star"></small>
                            </div>
                            <small class="pt-1">(99 Reviews)</small>
                        </div>
                        <h2 class="price text-secondary"><del>Rs.{{ $product->compare_price }}</del></h2>
                        <h2 class="price ">Rs.{{ $product->price }}</h2>
                        @if (!empty($product->short_description))
                            <p>{!!$product->short_description !!}</p>
                        @else
                            <p>{!!$product->description !!}</p>
                        @endif
                        <a href="javascript:void(0);" onclick="addToCart({{$product->id}})" class="btn btn-dark"><i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                <p>
                                    {!!$product->description !!}
                                </p>
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            <p> {!!$product->shipping_returns !!}</p>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            </div>
                        </div>
                    </div>
                </div> 
            </div>           
        </div>
    </section>

    @if(!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>Related Products</h2>
                </div> 
                <div class="col-md-12">
                    <div id="related-products" class="carousel">
                        @foreach ($relatedProducts as $product)
                            @php
                                $productImage = $product->product_images->first();
                            @endphp
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
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('customJs')
@endsection

