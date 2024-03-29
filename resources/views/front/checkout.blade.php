@extends('front.layout.app')
@section('content')  
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" name="orderForm" id="orderForm" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name">
                                            <p class="error"></p>
                                        </div>            
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                                            <p class="error"></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach 
                                                @endif
                                            </select>
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control"></textarea>
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="appartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)">
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City">
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State">
                                            <p class="error"></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip">
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone No.">
                                            <p class="error"></p>
                                        </div>            
                                    </div>
                                    

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="notes" id="notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                            <p class="error"></p>
                                        </div>            
                                    </div>

                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>                    
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                        <div class="h6">Rs.{{$item->price * $item->qty}}</div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{Cart::subtotal()}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong>$20</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong>${{Cart::subtotal()}}</strong></div>
                                </div>                            
                            </div>
                        </div>   
                        
                        <div class="card payment-form "> 
                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                            <div>
                                <input type="radio" name="payment_method" value="cod" id="payment_method_one" checked>
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>  
                            <div>
                                <input type="radio" name="payment_method" value="stripe" id="payment_method_two">
                                <label for="payment_method_two" class="form-check-label">Stripe</label>
                            </div>

                            <div class="card-body p-0 d-none" id="card-payment-form">
                                <div class="sub-title mt-3">
                                    <h5>Payment With Card</h5>
                                </div>
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100" id="payNowBtn">Pay Now</button>
                            </div>                       
                        </div>

                            
                        <!-- CREDIT CARD FORM ENDS HERE -->
                        
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $('input[name="payment_method"]').on('click',function(){
            if($(this).val()=='cod'){
                $("#card-payment-form").addClass('d-none');
            }else{
                $("#card-payment-form").removeClass('d-none');
            }
        });

        $("#orderForm").on('submit',function(event){
            event.preventDefault();
            $("#payNowBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("front.processCheckout") }}',
                type:'POST',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#payNowBtn").prop('disabled',false)
                        window.location.href="{{ url('/thank-you/') }}/"+res['order_id'];
                    }else{
                        $("#payNowBtn").prop('disabled',false)

                        $(".error").removeClass('invalid-feedback').html("").siblings('.form-control').removeClass("is-invalid");
    
                        var errors = res['errors'];
                        $.each(errors,function(key,value){
                            $("#"+key).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value)
                        });
                    }
                }
            })
        });
    </script>
@endsection