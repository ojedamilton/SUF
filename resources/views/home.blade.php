<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Coddoc">
    <meta name="author" content="alepurre">
    <meta name="keyword" content="SUF, Sistema Unificado Facturacion">
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
          <h5 class="nav-link">Sistema Unificado de Facturacion: <strong>{{ $empresas->nombreEmpresa }}</strong></h5>
          {{--  <router-link to='/compras'>Compras</router-link> --}}
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
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
                  {{ __('Cerrar Sesión') }}
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
        <a href="/home#/dashboard" class="brand-link">
          <span class="brand-text font-weight-light">{{ config('app.name')}} </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            {{--  @hasanyrole('Administracion-ADMIN') --}}
            <!-- EMPRESA -->
              @canany(['isAdmin'])
              <li class="nav-item">
                <a href="#" class="nav-link ">
                  <p>
                    EMPRESA
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/empresa'>
                        <i class="fas fa-building nav-icon"></i>
                        <p>Registrar Empresa</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/listadoEmpresa'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              @endcanany
              <!-- FACTURACION -->
              @canany(['isAdmin','isSeller'])
              <li class="nav-item">
                <a  href="#" class="nav-link ">
                  <p>
                    FACTURACION
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/facturacion'>
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>Nueva Facturacion</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/listadofacturacion'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              @endcanany
              <!-- COMPRAS -->
              @canany(['isAdmin','isBuyer'])
              <li class="nav-item ">
                <a href="#" class="nav-link ">
                  <p>
                    COMPRAS
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link  class="nav-link" to='/compras'>
                      <i class="fas fa-toolbox nav-icon"></i>
                      <p>Nueva Compra</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              @endcanany
              <!-- PRODUCTOS -->
         {{--      <li class="nav-item ">
                <a href="#" class="nav-link ">
                  <p>
                    ARTICULOS
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="fas fa-cubes nav-icon"></i>
                      <p>Nuevo Producto</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li> --}}
              <!-- USUARIOS -->
              @canany(['isAdmin'])
              <li class="nav-item">
                <a href="#" class="nav-link ">
                  <p>
                    USUARIOS
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li   class="nav-item">
                    <router-link  class="nav-link" to='/nuevoUsuario'>
                      <i class="fas fa-user-shield nav-icon"></i>
                      <p>Nuevo Usuario</p>
                    </router-link>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/listadoUsuario'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              @endcanany
              <!-- CLIENTES -->
              <li class="nav-item ">
                <a href="#" class="nav-link ">
                  <p>
                    CLIENTES
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link  class="nav-link" to='/nuevoCliente'>
                        <i class="fas fa-user-circle nav-icon"></i>
                        <p>Nuevo Cliente</p>
                      </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/listadoCliente'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              <!-- PROVEEDORES -->
            {{--   <li class="nav-item ">
                <a href="#" class="nav-link ">
                  <p>
                    PROVEEDORES
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link  class="nav-link" to='/nuevoProveedor'>
                      <i class="far fa-user-circle nav-icon"></i>
                      <p>Nuevo Proveedor</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li> --}}
              <!-- MEDIOS DE PAGO -->
              <li class="nav-item ">
                <a href="#" class="nav-link ">
                  <p>
                    MEDIOS DE PAGO
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link class="nav-link" to='/nuevoMedioPago'>
                      <i class="fas fa-donate nav-icon"></i>
                      <p>Nuevo Medio de Pago</p>
                    </router-link>
                  </li>
                  <li  class="nav-item">
                    <router-link class="nav-link" to='/listadoMedioPago'>
                      <i class="fas fa-list nav-icon"></i>
                      <p>Listado</p>
                    </router-link>
                  </li>
                </ul>
              </li>
              <!-- LISTADOS -->
              <li class="nav-item">
                <a href="#" class="nav-link ">
                  <p>
                    LISTADOS
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li   class="nav-item">
                    <router-link  class="nav-link" to='/dashboard'>
                      <i class="far fa-chart-bar nav-icon"></i>
                      <p>Subdiario de ventas</p>
                    </a>
                  </li>
                </ul>{{-- 
                <ul class="nav nav-treeview">
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="far fa-chart-bar nav-icon"></i>
                      <p>Ventas por cliente</p>
                    </a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li  class="nav-item">
                    <router-link  class="nav-link" to='/#'>
                      <i class="far fa-chart-bar nav-icon"></i>
                      <p>Ventas por vendedor</p>
                    </a>
                  </li>
                </ul> --}}
              </li>
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
        <div class="row">
        <!-- Main content -->
          
          @yield('content')
          {{-- Renderizo Componentes de Vue --}}
            <router-view></router-view>
          {{-- Finalizacion del Renderizado --}}
        </div>
      </div>
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>Title</h5>
          <p>Sidebar content</p>
        </div>
      </aside>
      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Default to the left -->
        <strong>Copyright &copy; 2022-2023 <a href="#">SUF</a>.</strong> All rights reserved.
      </footer>
    </div>
    <script>
     window.location.href = 'http://localhost:8000/home#/dashboard';
    </script>
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
