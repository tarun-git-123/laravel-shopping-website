<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Country;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);
        if($product==null){
            return response()->json([
                'status'=>false,
                'message'=>'Product not found'
            ]);
        }
        if(Cart::count()>0){
            // product found in cart
            // check if this product is alredy in cart
            // retrun a message that your product is already added in your cart
            // if product not found in cart then add that product to a cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if($item->id == $product->id){
                    $productAlreadyExist = true;
                }
            }
            if($productAlreadyExist == false){
                $productImage = (!empty($product->product_images))?$product->product_images->first():'';
                Cart::add($product->id, $product->title, 1, $product->price,['productImage'=>$productImage]);
                $status= true;
                $message = $product->title." added in cart";
            }else{
                $status= false;
                $message = $product->title." is already added in your cart";
            }
        }else{
            $productImage = (!empty($product->product_images))?$product->product_images->first():'';
            Cart::add($product->id, $product->title, 1, $product->price,['productImage'=>$productImage]);
            $status= true;
            $message = $product->title." added in cart";
        }

        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
    }

    public function cart(){
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        // Cart::destroy();
        return view('front.cart',$data);
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        // check qty available in stock
        $cartInfo = Cart::get($rowId);

        $product = Product::find($cartInfo->id);
        
        if($product->track_qty=='Yes'){
            if($qty <= $product->qty){
                Cart::update($rowId,$qty);
                $status = true;
                $message = 'Cart updated successfully';
                session()->flash('success',$message);
            }else{
                $status = false;
                $message = 'Requested qty ('.$qty.') is not available in stock';
                session()->flash('error',$message);
            }
        }else{
            Cart::update($rowId,$qty);
            $status = true;
            $message = 'Cart updated successfully';
            session()->flash('success',$message);
        }

        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
    }

    public function deleteitem(Request $request){
        Cart::remove($request->rowId);
        session()->flash('success','Item Removed from this cart');
        return response()->json([
            'status'=>true,
            'message'=>"Item Removed"
        ]);
    }

    public function checkout(){
        //-- if cart is empty then redirct to cart
        if(Cart::count()==0){
            return redirect()->route('front.cart');
        }
        // --if user not logged id then redirect to login
        if(Auth::check() == false){
            if(!session()->has('url.intended')){
                session(['url.intended' =>url()->current()]);
            }  
            return redirect()->route('account.login');
        }
        session()->forget('url.intended');

        $countries = Country::orderBy('name','ASC')->get();
        return view('front.checkout',['countries'=>$countries]);
    }

    public function processCheckout(Request $request){
        // step-1 make validation
        $validator = Validator::make($request->all(),[
            'first_name' =>'required|min:5',
            'last_name' =>'required',
            'email' =>'required|email',
            'phone' =>'required',
            'country' =>'required',
            'address' =>'required|min:10',
            'city' =>'required',
            'state' =>'required',
            'zip' =>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Please fix the erors',
                'errors'=>$validator->errors()
            ]);
        }

        // step-2 save customer address
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->appartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'phone' => $request->phone,
            ]
        );
        
        // step-3 save data in orders table

        if($request->payment_method=='cod'){
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            $grandTotal = $subTotal+$shipping-$discount;
            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;

            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->appartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->save();

            // step-4 store order item in order_items table
            foreach (Cart::content() as $item) {
                $order_item = new OrderItem();
                $order_item->product_id = $item->id;
                $order_item->order_id = $order->id;
                $order_item->name = $item->name;
                $order_item->qty = $item->qty;
                $order_item->price = $item->price;
                $order_item->total = $item->price*$item->qty;
                $order_item->save();
            }
            session()->flash('success','You have successfully placed your order');
            Cart::destroy();
            return response()->json([
                'status'=>true,
                'order_id'=>$order->id,
                'message'=>'Order saved successfully'
            ]);
        }else{
            // 
        }
    }

    public function thankyou($id){
        return view('front.thank-you',['id'=>$id]);
    }
}
