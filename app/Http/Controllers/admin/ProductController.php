<?php

namespace App\Http\Controllers\admin;

use Image;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::latest('id');
        if(!empty($request->get('keyword'))){
            $products = $products->where('title','like','%'.$request->get('keyword').'%');
        }
        $products = $products->with('product_images')->paginate(10);
        // dd($products);
        $data['products'] = $products;
        return view('admin.products.list',$data);
    }
    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create',$data);
    }

    public function store(Request $request){
        $rules = [
            'title'=>'required',
            'slug'=>'required|unique:products',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products',
            'track_qty'=>'required|in:Yes,No',
            'category'=>'required',
        ];
        if(!empty($request->track_qty) && $request->track_qty=='Yes'){
            $rules['qty'] = 'required'; 
        }
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products))?implode(',',$request->related_products):'';
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id  = $request->category ;
            $product->sub_category_id  = $request->sub_category ;
            $product->brand_id  = $request->brand ;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->save();

            // save gallery image
            
            // save gallery image
            if(!empty($request->image_array)){
                foreach ($request->image_array as $key => $temp_image_id) {
                    $temp_image = TempImage::find($temp_image_id);
                    $extArray = explode('.',$temp_image->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // generate thumbnail
                    
                    // large image
                    $sourcePath = public_path().'/temp/'.$temp_image->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);
                    
                    //small image
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }

            $request->session()->flash('success','Product Created Successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Product Created successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$product_id){
        $data = [];
        $product = Product::find($product_id);
        if(!empty($product->id)){
            $productImages = ProductImage::where('product_id',$product->id)->get();
            $subCategories = SubCategory::where('category_id',$product->category_id)->get();
            $categories = Category::orderBy('name','ASC')->get();
            $brands = Brand::orderBy('name','ASC')->get();

            $relatedProducts=[];
            // fetch related products
            if($product->related_products!=''){
                $productArr = explode(',',$product->related_products);
                $relatedProducts = Product::whereIn('id',$productArr)->get();
            }
            
            $data['product'] = $product;
            $data['productImages'] = $productImages;
            $data['categories'] = $categories;
            $data['brands'] = $brands;
            $data['subCategories'] = $subCategories;
            $data['relatedProducts'] = $relatedProducts;

            return view('admin.products.edit',$data);
        }else{
            return redirect()->route('products.index')->with('error','Product not found');
        }
    }

    public function update(Request $request,$product_id){
        $product = Product::find($product_id);
        $rules = [
            'title'=>'required',
            'slug'=>'required|unique:products,slug,'.$product->id.',id',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products,sku,'.$product->id.',id',
            'track_qty'=>'required|in:Yes,No',
            'category'=>'required',
        ];
        if(!empty($request->track_qty) && $request->track_qty=='Yes'){
            $rules['qty'] = 'required'; 
        }
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products))?implode(',',$request->related_products):'';
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id  = $request->category ;
            $product->sub_category_id  = $request->sub_category ;
            $product->brand_id  = $request->brand ;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->save();

            $request->session()->flash('success','Product Updated Successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Product created successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $product = Product::find($id);
        if(empty($product)){
            return redirect()->route('products.index')->with('error','Product not found');
        }
        $productImages = ProductImage::where('product_id',$id)->get();
        if(!empty($productImages)){
            foreach ($productImages as $key => $productImage) {
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }
            ProductImage::where('product_id',$id)->delete();
        }
        $product->delete();

        $request->session()->flash('success','Product Deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Product Deleted successfully'
        ]);
    }

    public function getProducts(Request $request){
        $tempProducts = [];
        if(!empty($request->term)){
            $products = Product::where('title','like','%'.$request->term.'%')->get();
            foreach ($products as $product) {
                $tempProducts[] = array('id'=>$product->id,'text'=>$product->title);
            }
        }
        return response()->json([
            'tags'=>$tempProducts,
            'status'=>true
        ]);
    }
}
