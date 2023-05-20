@extends('layouts.app')

@section('styles')
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">
  <!-- sweetalert2 -->
  <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
  <!-- Styles -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

@endsection

<!-- Navbar -->
@section('navbar')

  @include('adminlte.navbar')

@endsection
    
<!-- Sidebar -->
@section('sidebar')

  @include('adminlte.sidebar')

@endsection

<!-- Main content -->
@section('content')
 
  <div class="content-wrapper">

    <div class="row">

      <!-- Componentes Vue -->
      <router-view></router-view>

    </div>

  </div>

@endsection

<!-- Footer -->
@section('footer')

  @include('partials.footer')

@endsection

<!-- Scripts Render inside Body -->
@section('pre-scripts')
  <!-- jQuery -->
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>  
  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.js')}}"></script>
  <!-- sweetalert2 -->
  <script src="{{asset('js/sweetalert2.min.js')}}"></script>
  <!-- Toast JS -->
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@endsection

<!-- Scripts Render after html -->
@push('scripts')
  <script>
    window.location.href = 'http://localhost:8000/home#/dashboard';
  </script>
@endpush


 
