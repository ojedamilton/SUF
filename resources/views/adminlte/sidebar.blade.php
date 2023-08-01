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
            <!-- EMPRESA solo grupo Admin -->
              @canany(['isAdmin'])
                <li class="nav-item">
                  <a href="#" class="nav-link ">
                    <p>
                      EMPRESA
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    {{-- Accion para creación --}}
                    @can('createAdmin')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/empresa'>
                            <i class="fas fa-building nav-icon"></i>
                            <p>Registrar Empresa</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('createAdmin')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                            <i class="fas fa-building nav-icon"></i>
                            <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                    {{-- Accion para visualización --}}
                    @can('viewAdmin')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/listadoEmpresa'>
                          <i class="fas fa-list nav-icon"></i>
                          <p>Listado</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('viewAdmin')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fas fa-list nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                  </ul>
                </li>
              @endcanany
              <!-- FACTURACION grupo admin y vendedor -->
              @canany(['isAdmin','isSeller','editVendedor','viewVendedor'])
                <li class="nav-item">
                  <a  href="#" class="nav-link ">
                    <p>
                      FACTURACION
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    {{-- Accion para crear --}}
                    @can('editVendedor')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/facturacion'>
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>Nueva Facturacion</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('editVendedor')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                            <i class="fas fa-file-alt nav-icon"></i>
                            <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                    {{-- Accion para visualización --}}
                    @can('viewVendedor')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/listadoFacturacion'>
                          <i class="fas fa-list nav-icon"></i>
                          <p>Listado</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('viewVendedor')
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fas fa-list nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                  </ul>
                </li>
              @endcanany
              <!-- COMPRAS grupo admin y comprador -->
              @canany(['isAdmin','isBuyer','editComprador','viewComprador'])
                <li class="nav-item ">
                  <a href="#" class="nav-link ">
                    <p>
                      COMPRAS
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                  {{-- Permiso creación comprador --}}
                    @can('editComprador')
                      <li class="nav-item">
                        <router-link  class="nav-link" to='/compras'>
                          <i class="fas fa-toolbox nav-icon"></i>
                          <p>Nueva Compra</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('editComprador')
                      <li class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fas fa-toolbox nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                    {{-- Permiso visualización comprador --}}
                    @can('viewComprador')
                      <li class="nav-item">
                        <router-link  class="nav-link" to='/listadoCompras'>
                          <i class="fas fa-list nav-icon"></i>
                          <p>Listado</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('viewComprador')
                      <li class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fas fa-list nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                  </ul>
                </li>
              @endcanany
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
                    {{-- Accion para creación --}}
                    @can('createAdmin')
                      <li   class="nav-item">
                        <router-link  class="nav-link" to='/nuevoUsuario'>
                          <i class="fas fa-user-shield nav-icon"></i>
                          <p>Nuevo Usuario</p>
                        </router-link>
                      </li>
                    @endcan
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
                <li class="nav-item">
                  <a href="#" class="nav-link ">
                    <p>
                      GRUPOS Y ACCIONES
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  {{-- Accion para visualización --}}
                  @can('viewAdmin')
                    <ul class="nav nav-treeview">
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/grupos'>
                          <i class="fa fa-users nav-icon"></i>
                          <p>Grupos</p>
                        </router-link>
                      </li>
                    </ul>
                    <ul class="nav nav-treeview">
                      <li   class="nav-item">
                        <router-link  class="nav-link" to='/acciones'>
                          <i class="fa fa-cogs nav-icon"></i>
                          <p>Acciones</p>
                        </router-link>
                      </li>
                    </ul>
                  @endcan
                  @cannot('viewAdmin')
                    <ul class="nav nav-treeview">
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fa fa-users nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    </ul>
                    <ul class="nav nav-treeview">
                      <li   class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fa fa-cogs nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                        </li>
                    </ul>
                  @endcannot
                  {{-- Accion para edicion --}}
                  @can('editAdmin')
                    <ul class="nav nav-treeview">
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/gruposAcciones'>
                          <i class="fa fa-link nav-icon"></i>
                          <p>Grupos-Acciones</p>
                        </router-link>
                      </li>
                    </ul>
                  @endcan
                  @cannot('editAdmin')
                    <ul class="nav nav-treeview">
                      <li  class="nav-item">
                        <router-link  class="nav-link" to='/notfound'>
                          <i class="fa fa-link nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    </ul>
                  @endcannot
                </li>
              @endcanany
              @canany(['isAdmin','isSeller'])
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
              @endcanany
              @canany(['isAdmin','isBuyer'])
                <!-- PROVEEDORES -->
                <li class="nav-item ">
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
                      <router-link  class="nav-link" to='/listadoProveedor'>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Listado</p>
                      </router-link>
                    </li>
                  </ul>
                </li>
              @endcanany
              @canany(['isAdmin','isSeller','isBuyer'])
                <!-- PRODUCTOS -->
                <li class="nav-item ">
                  <a href="#" class="nav-link ">
                    <p>
                      ARTICULOS
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <router-link  class="nav-link" to='/nuevaCategoria'>
                        <i class="fas fa-cubes nav-icon"></i>
                        <p>Nueva Categoria</p>
                      </router-link>
                    </li>
                    <li  class="nav-item">
                      <router-link  class="nav-link" to='/listadoCategoria'>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Listado Categoria</p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link  class="nav-link" to='/nuevoArticulo'>
                        <i class="fas fa-cube nav-icon"></i>
                        <p>Nuevo Articulo</p>
                      </router-link>
                    </li>
                    <li  class="nav-item">
                      <router-link  class="nav-link" to='/listadoArticulo'>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Listado Articulos</p>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <!-- STOCKS -->
                <li class="nav-item">
                  <a href="" class="nav-link">
                    <p>
                      STOCKS
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li  class="nav-item">
                      <router-link  class="nav-link" to='/inventarioStock'>
                        <i class="fa fa-database"></i>
                        <p>Inventario</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endcanany
              @canany(['isAdmin','isSeller','isBuyer'])
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
              @endcanany
              @canany(['isAdmin','isSeller'])
                <!-- INFORMES -->
                <li class="nav-item">
                  <a href="#" class="nav-link ">
                    <p>
                      INFORMES
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li   class="nav-item">
                      <router-link  class="nav-link" id="dashboard-link" to='/dashboard'>
                        <i class="far fa-chart-bar nav-icon"></i>
                        <p>Subdiario de ventas</p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    @can('reportVendedor')
                      <li   class="nav-item">
                        <router-link  class="nav-link" id="dashboard-link" to='/estadisticasVentas'>
                          <i class="fa fa-book nav-icon"></i>
                          <p>Reportes Ventas</p>
                        </router-link>
                      </li>
                    @endcan
                    @cannot('reportVendedor')
                      <li class="nav-item">
                        <router-link  class="nav-link" id="dashboard-link" to='/notfound'>
                          <i class="fa fa-book nav-icon"></i>
                          <span class="badge badge-danger">Solicite Permiso</span>
                        </router-link>
                      </li>
                    @endcannot
                  </ul>
                </li>
              @endcanany
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>