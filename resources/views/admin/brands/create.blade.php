@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Brands</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('brands.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form method="post" id="brandForm" name="brandForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>	
                                <p></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>		
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" type="submit" id="createBtn">Create</button>
                <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')
    <script>
        $("#brandForm").on('submit',function(event){
            event.preventDefault();
            $("#createBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("brands.store")}}',
                type:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#createBtn").prop('disabled',false)
                        window.location.href="{{route('brands.index') }}";
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
    </script>
@endsection