@extends('front.layout.app')
@section('content')  
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">    
                <form action="" method="post" id="registrationForm">
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        <p class="error"></p>
                    </div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div> 
                    <button type="submit" class="btn btn-dark btn-block btn-lg registerBtn" value="Register">Register</button>
                </form>			
                <div class="text-center small">Already have an account? <a href="{{ route('account.login') }}">Login Now</a></div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#registrationForm").on('submit',function(event){
            event.preventDefault();
            $("#registerBtn").prop('disabled',true)
            $.ajax({
                url:'{{ route("account.processRegister") }}',
                method:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status']==true){
                        $("#registerBtn").prop('disabled',false)
                        window.location.href="{{route('account.login') }}";
                    }else{
                        $("#registerBtn").prop('disabled',false)

                        $(".error").removeClass('invalid-feedback').html("").siblings('.form-control').removeClass("is-invalid");
    
                        var errors = res['errors'];
                        $.each(errors,function(key,value){
                            $("#"+key).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value)
                        });
                    }
                },
                error:function(JQXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });
    </script>
@endsection