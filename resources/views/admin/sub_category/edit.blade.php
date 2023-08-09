@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->

    <div class="container-fluid">
        <form method="post" id="subCategoryForm" name="subCategoryForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">-Select Category-</option>
                                    @if($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                        <option {{ ($subCategories->category_id==$category->id)?'selected':'' }} value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{ $subCategories->name }}" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" value="{{ $subCategories->slug }}" name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $subCategories->status==1?'selected':'' }}>Active</option>
                                    <option value="0" {{ $subCategories->status==0?'selected':'' }}>Deactive</option>
                                </select>
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showHome">Show on Home</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option value="Yes" {{ $subCategories->showHome=='Yes'?'selected':'' }}>Yes</option>
                                    <option value="No" {{ $subCategories->showHome=='No'?'selected':'' }}>No</option>
                                </select>
                            </div>
                        </div>							
                    </div>
                </div>							
            </div>
            
            <div class="pb-5 pt-3">
                <button class="btn btn-success" id="createBtn">Update</button>
                <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')
    <script>
        $("#subCategoryForm").on('submit',function(event){
            event.preventDefault();
            $("#createBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("sub-categories.update",$subCategories->id)}}',
                type:'put',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#createBtn").prop('disabled',false)
                        window.location.href="{{route('sub-categories.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        $("#category_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                    }else{
                        var errors = res['errors'];
                        if(errors['name']){
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name'])
                        }else{
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors['slug']){
                            $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug'])
                        }else{
                            $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors['category_id']){
                            $("#category_id").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category_id'])
                        }else{
                            $("#category_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }
                    }
                    
                },error:function(jqXHR,exception){
                    console.log("something went wrong");
                }
            })
        })

        $("#name").on('change',function(){
            $("#createBtn").prop('disabled',true)
            var name = $(this).val();
            $.ajax({
                url:'{{ route("getslug")}}',
                type:'get',
                data:{title:name},
                dataType:'json',
                success:function(res){
                    if(res.status==true){
                        $("#createBtn").prop('disabled',false)
                        $("#slug").val(res.slug)
                    }
                }
            })
        })
        
    </script>
@endsection