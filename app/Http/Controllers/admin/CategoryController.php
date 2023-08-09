<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list',compact('categories'));
    }

    public function list(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10);
        return response()->json([
            'message'=>'Category list',
            'data'=> $categories,
            'status'=>true
        ]);
    }

    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=> 'required|unique:categories',
        ]);

        if($validator->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->image = 'Null';
            $category->showHome = $request->showHome;
            $category->save();

            // save image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArr = explode('.',$tempImage->name);
                $ext = last($extArr);
                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // generate image thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('success','Category Created Successfully');
            return response()->json([
                'status'=> true,
                'messages'=>'Category Created Successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit(Request $request,$categoryId){
        $category = Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }
    public function update(Request $request,$categoryId){
        $category = Category::find($categoryId);
        if(empty($category)){
            return response()->json([
                'status'=>true,
                'notFound'=>true,
                'message'=>'Category Not found'
            ]);
        }
        $oldImage = $category->image;

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=> 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // save image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArr = explode('.',$tempImage->name);
                $ext = last($extArr);
                $newImageName = $category->id.'_'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // generate image thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            $request->session()->flash('success','Category Updated Successfully');
            return response()->json([
                'status'=> true,
                'messages'=>'Category Updated Successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destroy(Request $request,$categoryId){
        $category = Category::find($categoryId);

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();
        $request->session()->flash('success','Category Deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Category Deleted successfully'
        ]);

    }
}
