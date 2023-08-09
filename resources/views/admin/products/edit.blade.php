@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form method="post" name="productForm" id="productForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" value="{{ $product->title }}" name="title" id="title" class="form-control" placeholder="Title">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $product->slug }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Short Description</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="">{{ $product->short_description }}</textarea>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Shipping and Returns</label>
                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="">{{ $product->shipping_returns }}</textarea>
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>								
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">    
                                    <br>Drop files here or click to upload.<br><br>                                            
                                </div>
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="row" id="product-gallery">
                        @if ($productImages->isNotEmpty())
                            @foreach ($productImages as $image)
                                <div class="col-md-2" id="image-row-{{$image->id}}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                        <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="card-img-top" alt="{{ $image->image }}">
                                        <div class="card-body text-center">
                                            <a href="javascript:void(0)" onclick="deleteImage({{$image->id}})" class="btn btn-danger btn-sm">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $product->compare_price }}">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>	
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{ $product->sku }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}">	
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" value="Yes" name="track_qty" {{ $product->track_qty=='Yes'?'checked':'' }}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>                                         
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Related Products</h2>
                                <div class="mb-3">
                                    <select multiple name="related_products[]" id="related_products" class="form-control related_product w-100">                                               
                                        @if (!empty($relatedProducts))
                                            @foreach ($relatedProducts as $relProducts)
                                                <option selected value="{{ $relProducts->id }}">{{ $relProducts->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $product->status==1?'selected':'' }}>Active</option>
                                    <option value="0" {{ $product->status==0?'selected':'' }}>Block</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id==$category->id?'selected':'' }}>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Select Sub Category</option>
                                    @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $product->sub_category_id==$subCategory->id?'selected':'' }}>{{ $subCategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Select Brands</option>
                                    @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="No" {{ $product->is_featured=='No'?'selected':'' }}>No</option>
                                    <option value="Yes" {{ $product->is_featured=='Yes'?'selected':'' }}>Yes</option>                                                
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>                                 
                </div>
            </div>
            
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-success" id="createBtn">Update</button>
                <a href="{{ route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')
    <script>
        $('.related_product').select2({
            ajax: {
                url: '{{ route("products.getProducts") }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                        results: data.tags
                    };
                }
            }
        }); 

        $("#productForm").on('submit',function(event){
            event.preventDefault();
            $("#createBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("products.update",$product->id)}}',
                type:'put',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#createBtn").prop('disabled',false)
                        window.location.href="{{route('products.index') }}";
                    }else{
                        $("#createBtn").prop('disabled',false)

                        $(".error").removeClass('invalid-feedback').html("").siblings('.form-control').removeClass("is-invalid");
    
                        var errors = res['errors'];
                        $.each(errors,function(key,value){
                            $("#"+key).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value)
                        });
                    }
                    
                },error:function(jqXHR,exception){
                    $("#createBtn").prop('disabled',false)
                    console.log("something went wrong");
                }
            })
        })

        $("#category").change(function(){
            var category_id = $(this).val();
            $.ajax({
                url:'{{ route("product-subcategories.index")}}',
                type:'get',
                data:{category_id:category_id},
                dataType:'json',
                success:function(res){
                    if(res.status==true){
                        $("#sub_category").find('option').not(':first').remove();
                        $.each(res['subcategory'],function(key,item){
                            $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`)
                        })
                    }
                }
            })
        });

        $("#title").on('change',function(){
            $("#createBtn").prop('disabled',true)
            var title = $(this).val();
            $.ajax({
                url:'{{ route("getslug")}}',
                type:'get',
                data:{title:title},
                dataType:'json',
                success:function(res){
                    if(res.status==true){
                        $("#createBtn").prop('disabled',false)
                        $("#slug").val(res.slug)
                    }
                }
            })
        })

        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            url:  "{{ route('product-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params:{'product_id':'{{ $product->id }}'},
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                var html = `<div class="col-md-2" id="image-row-${response.image_id}"><div class="card" style="width: 18rem;">
                    <input type="hidden" name="image_array[]" value="${response.image_id}">
                    <img src="${response.ImagePath}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                    </div>
                </div></div>`;
                $("#product-gallery").append(html);
            },
            complete:function(file){
                this.removeFile(file)
            }
        });

        function deleteImage(image_id){
            if(confirm("Are you sure you want to delete this image?")){
                $.ajax({
                    url:'{{ route("product-images.destroy")}}',
                    type:'post',
                    data:{id:image_id},
                    dataType:'json',
                    success:function(res){
                        if(res.status==true){
                            // alert(res.message)
                            $("#image-row-"+image_id).fadeOut('slow');
                        }else{
                            alert(res.message)
                        }
                    }
                })
            }
            
        }
    </script>
@endsection