<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::latest('id');
        if(!empty($request->keyword)){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list',compact('brands'));
    }
    public function create(){
        return view('admin.brands.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands'
        ]);

        if($validator->passes()){
            $brands = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();
            
            $request->session()->flash('success','Brands Created successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Brands Created successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        $brands = New Brand();

    }

    public function edit(Request $request,$brand_id){
        $brand = Brand::find($brand_id);
        if(empty($brand)){
            $request->session()->flash('error','Brands not found');
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit',compact('brand'));
    }


    public function update(Request $request,$brand_id){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$brand_id.',id'
        ]);

        if($validator->passes()){
            $brands = Brand::find($brand_id);
            if(empty($brands)){
                return redirect()->route('brands.index');
            }
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();
            
            $request->session()->flash('success','Brands updated successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Brands updated successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        $brands = New Brand();
    }

    public function delete(Request $request, $brands_id){
        $brand = Brand::find($brands_id);
        $brand->delete();
        $request->session()->flash('success','Brands deleted successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Brands Deleted successfully'
        ]);
    }
}
