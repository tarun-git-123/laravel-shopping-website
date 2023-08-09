@extends('front.layout.app')
@section('content')
    <section class="container">
        <div class="col-md-12 text-center">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <h4>{{ Session::get('success') }}</h4>
                </div>
            @endif
            <h3 class="p-4">Thank You!</h3>
            <p>Your Order id is {{ $id }}</p>
        </div>
    </section>
@endsection

@section('customJs')

@endsection