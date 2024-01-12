@extends('layout.partials.app', [
    'main_app' => true
])

@section('title', 'متجر الصيانة')

@section('dashbord_content')
    <div class="page-content">
        @if (session('status'))
            <div class="alert alert-success fs-5 text-center">
                {{ session('status') }}
            </div>
        @endif
        <!-- container-fluid -->

        <div class="container-fluid">
            <div class="">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>
    <!-- End Page-content -->
@endsection
