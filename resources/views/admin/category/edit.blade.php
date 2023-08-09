@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form method="post" id="categoryForm" name="categoryForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{ $category->name }}" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" value="{{ $category->slug }}" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" id="image_id" name="image_id">
                                <label for="status">Image</label>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                            
                            @if (!empty($category->image))
                                <div>
                                    <img src="{{ asset('/uploads/category/thumb/'.$category->image) }}" alt="{{ $category->image }}" width="200">
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $category->status==1?'selected':'' }}>Active</option>
                                    <option value="0" {{ $category->status==0?'selected':'' }}>Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showHome">Show on Home</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option value="Yes" {{ $category->showHome=='Yes'?'selected':'' }}>Yes</option>
                                    <option value="No" {{ $category->showHome=='No'?'selected':'' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>		
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-success" type="submit" id="createBtn">Update</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')
    <script>
        $("#categoryForm").on('submit',function(event){
            event.preventDefault();
            $("#createBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("categories.update",$category->id)}}',
                type:'put',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#createBtn").prop('disabled',false)
                        window.location.href="{{route('categories.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
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

        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        }); 
        
    </script>
@endsection