<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Coddoc">
  <meta name="author" content="alepurre">
  <meta name="keyword" content="SECCO, plantas de compresión de gas">
  <!-- VerifyCsrfToken middleware -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name')}}</title>
  <link rel="icon" href="{{asset('img/folder.png')}}">
  {{-- <link rel="stylesheet" href="{{asset('css/styles.css')}}"> --}}
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">
   <!-- sweetalert2 -->
  <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<div id="app" class="wrapper"> <!-- div corre vue.js -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
       <h5 class="nav-link">Sistema de Unificado de Facturacion</h5>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name}}
          {{-- $_SERVER['PHP_AUTH_USER']--}}
       </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
      </div>
    </li>
      <!-- Navbar Search -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">{{ config('app.name')}} </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library --> 
         {{--  @hasanyrole('Administracion-ADMIN') --}}
          <li class="nav-item menu-open"> 
            <a href="#" class="nav-link ">
              <p>
                FACTURACION
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li @click="menu=5" class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <p>Nueva Facturacion</p>
                  </a>
              </li>
              {{-- <li @click="menu=4" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-toolbox nav-icon"></i>
                  <p>Especialidad</p>
                </a>
              </li> --}}
              <li @click="menu=3" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-folder-minus nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>
              {{-- <li @click="menu=2" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-bezier-curve nav-icon"></i>
                  <p>Sector/Esp/TipoDoc</p>
                </a>
              </li>
              <li @click="menu=11" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Cliente</p>
                </a>
              </li> --}}     
            </ul>
          </li> 
          <li class="nav-item menu-open"> 
            <a href="#" class="nav-link ">
              <p>
                PRODUCTOS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li @click="menu=5" class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-toolbox nav-icon"></i>
                    <p>Nueva Producto</p>
                  </a>
              </li>
              {{-- <li @click="menu=4" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-toolbox nav-icon"></i>
                  <p>Especialidad</p>
                </a>
              </li> --}}
              <li @click="menu=3" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-folder-minus nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>    
            </ul>
          </li> 
   {{--        @endhasanyrole 
          @hasanyrole('Documentos-ADMIN|Documentos-GESTOR|Documentos-CONSULTA') --}}
          <li class="nav-item menu-open">
            <a href="#" class="nav-link ">
              <p>
                GRUPOS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li @click="menu=6"  class="nav-item">
                <a href="#" class="nav-link ">
                  <i class="fas fa-bezier-curve nav-icon"></i>
                 {{--  @can('edit documento')
                  <p>{{dd($roles)}}</p><br>
                  @endcan --}}
                  <p>Nuevo Grupo</p>
                </a>
              </li>
            </ul>
          </li>
      {{--     @endhasanyrole
          @hasanyrole('Usuarios-ADMIN') --}}
          <li class="nav-item menu-open">
            <a href="#" class="nav-link ">
              <p>
                USUARIOS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li @click="menu=11"  class="nav-item">
                <a href="#" class="nav-link ">
                  <i class="fas fa-user-shield"></i>
                  <p>Nuevo Usuario</p>
                </a>
              </li> 
            </ul>
            <ul class="nav nav-treeview">
              <li @click="menu=10"  class="nav-item">
                <a href="#" class="nav-link ">
                  <i class="fas fa-list"></i>
                  <p>Listado</p>
                </a>
              </li> 
            </ul>
          </li>
        {{--   @endhasanyrole --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->

    <!-- Main content -->
   {{--  <div class="content">
      <div class="container-fluid"> --}}
       <div class="row">
            <!-- Main content -->
    <section class="content col-lg-12">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>150</h3>
  
                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>53<sup style="font-size: 20px">%</sup></h3>
  
                  <p>Bounce Rate</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>44</h3>
  
                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>65</h3>
  
                  <p>Unique Visitors</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
        </div>  
    </section>
          {{-- <div class="col-lg-8"> --}}
          <!-- Contenido Principal -->
          

          <template v-if="menu==10"> 
            <user-component :path="path"></user-component>
          </template> 
          <template v-if="menu==11"> 
              <nuevo-user-component  :path="path"></nuevo-user-component>
          </template>
           
            <template v-if="menu==5"> 
              <facturacion-component  :path="path"></facturacion-component>
            </template>
          {{--    
            <template v-if="menu==4"> 
              <speciality-component  :path="path"></speciality-component>
            </template> 
            <template v-if="menu==5"> 
              <sector-component  :path="path"></sector-component> 
            </template>  
            <template v-if="menu==6"> 
              <document-component :path="path"></document-component>
            </template> 
            <template v-if="menu==7"> 
              <roles-component  :path="path"></roles-component>
            </template>
            <template v-if="menu==8"> 
              <permisos-component  :path="path"></permisos-component>
            </template> 
            <template v-if="menu==9"> 
              <rolpermiso-component  :path="path"></rolpermiso-component>
            </template> 
            <template v-if="menu==11"> 
              <client-component :path="path"></client-component>
            </template>  --}}
           {{-- @include('home') --}}
          <!-- /Fin del contenido principal -->
          {{-- </div> --}}
          <!-- /.col-md-6 -->
        </div> 
        <!-- /.row -->
    <!--  </div> /.container-fluid -->
   {{--  </div> --}}
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    {{-- <div class="float-right d-none d-sm-inline">
      Anything you want
    </div> --}}
    <!-- Default to the left -->
    <strong>Copyright &copy; 2021-2022 <a href="#">SUF</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
 <script src="{{asset('js/jquery.min.js')}}"></script> 
<!-- Bootstrap 4 -->
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
 <!-- JS APP -->
<script src="{{asset('js/app.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.js')}}"></script>
 <!-- sweetalert2 -->
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
</body>
</html>
