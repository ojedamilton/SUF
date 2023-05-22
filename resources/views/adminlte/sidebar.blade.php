      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <router-link  class="brand-link" to='/dashboard'>
          <span class="brand-text font-weight-light">{{ config('app.name')}} </span>
        </router-link>
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