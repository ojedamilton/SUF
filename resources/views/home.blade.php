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
  <!-- Custom styles for this template -->
  <link href="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/sp-2.2.0/sl-1.7.0/datatables.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css"/>

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
  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

@endsection

<!-- Scripts Render after html -->
@push('scripts')
  <script>
  // por que recargamos al dashboard ¿? Porque esta el Dashboar con las graficos!
  // window.location.href = 'http://localhost:8000/home#/dashboard';
  document.addEventListener('DOMContentLoaded', function() {
    var dashboardLink = document.getElementById('dashboard-link');
    dashboardLink.dispatchEvent(new Event('click'));
});

  </script>
@endpush


 
