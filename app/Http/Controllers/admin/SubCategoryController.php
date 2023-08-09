<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
                                    ->latest('sub_categories.id')
                                    ->leftJoin('categories','categories.id','sub_categories.category_id');
        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list',compact('subCategories'));
    }

    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        return view('admin.sub_category.create',$data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=> 'required|unique:sub_categories',
            'category_id'=>'required',
            'status'=>'required',
        ]);

        if($validator->passes()){
            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->showHome = $request->showHome;
            $subcategory->category_id = $request->category_id;
            
            $subcategory->save();

            $request->session()->flash('success','Sub Category Created Successfully');
            return response()->json([
                'status'=> true,
                'messages'=>'Sub Category Created Successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $subcategory_id){
        $subCategories = SubCategory::find($subcategory_id);
        if(empty($subCategories)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        return view('admin.sub_category.edit',$data);
    }

    public function update(Request $request, $subcategory_id){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=> 'required|unique:sub_categories,slug,'.$subcategory_id.',id',
            'category_id'=>'required',
            'status'=>'required',
        ]);

        if($validator->passes()){
            $subcategory = SubCategory::find($subcategory_id);
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->showHome = $request->showHome;
            $subcategory->category_id = $request->category_id;
            
            $subcategory->save();

            $request->session()->flash('success','Sub Category Updated Successfully');
            return response()->json([
                'status'=> true,
                'messages'=>'Sub Category Updated Successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $subcategory_id){
        echo $subcategory_id;
        $subCategories = SubCategory::find($subcategory_id);
        // dd($subCategories);
        $subCategories->delete();
        return redirect()->route('sub-categories.index')->with('success','Sub Category deleted successfully');
    }
}
