import EmpresaComponent from "../components/EmpresaComponent.vue";
import ListadoEmpresa from "../components/ListadoEmpresaComponent.vue";
import FacturacionComponent from "../components/FacturacionComponent.vue";
import ListadoFactura from "../components/ListadoFactura.vue";
import ComprasComponent from "../components/ComprasComponent.vue";
import ListadoCompras from "../components/ListadoComprasComponent.vue";
import NuevoUsuario from "../components/NuevoUserComponent.vue";
import ListadoUsuario from "../components/ListadoUserComponent.vue";
import NuevoCliente from "../components/NuevoClienteComponent.vue";
import ListadoCliente from "../components/ListadoClienteComponent.vue";
import NuevoProveedor from "../components/NuevoProveedorComponent.vue";
import ListadoProveedor from "../components/ListadoProveedorComponent.vue";
import NuevaCategoria from "../components/NuevaCategoriaComponent.vue";
import ListadoCategoria from "../components/ListadoCategoriaComponent.vue";
import NuevoMedioPago from "../components/NuevoMedioPagoComponent.vue";
import ListadoMedioPago from "../components/ListadoMedioPagoComponent.vue";
import GraficosView from "../components/GraficosComponent.vue";
import InventarioStock from "../components/InventarioStockComponent.vue";
import NuevoArticulo from "../components/NuevoArticuloComponent.vue";
import ListadoArticulo from "../components/ListadoArticuloComponent.vue";
import EstadisticasVentas from "../components/EstadisticasVentasComponent.vue";
import GruposComponent from "../components/GruposComponent.vue";
import AccionesComponent from "../components/AccionesComponent.vue";
import GruposAccionesComponent from "../components/GruposAccionesComponent.vue";
import NotFoundComponent from "../components/NotFoundComponent.vue";

const routes = [
    {
        path: "/dashboard",
        name: "dashboard",
        component: GraficosView,
    },
    {
        path: "/empresa",
        name: "empresa",
        component: EmpresaComponent,
    },
    {
        path: "/listadoEmpresa",
        name: "listadoEmpresa",
        component: ListadoEmpresa,
    },
    {
        path: "/facturacion",
        name: "facturacion",
        component: FacturacionComponent,
    },
    {
        path: "/listadofacturacion",
        name: "listadofacturacion",
        component: ListadoFactura,
    },
    {
        path: "/compras",
        name: "compras",
        component: ComprasComponent,
    },
    {
        path: "/listadoCompras",
        name: "listadoCompras",
        component: ListadoCompras,
    },
    {
        path: "/nuevoUsuario",
        name: "nuevoUsuario",
        component: NuevoUsuario,
    },
    {
        path: "/listadoUsuario",
        name: "listadoUsuario",
        component: ListadoUsuario,
    },
    {
        path: "/nuevoCliente",
        name: "nuevoCliente",
        component: NuevoCliente,
    },
    {
        path: "/listadoCliente",
        name: "listadoCliente",
        component: ListadoCliente,
    },
    {
        path: "/nuevoProveedor",
        name: "nuevoProveedor",
        component: NuevoProveedor,
    },
    {
        path: "/listadoProveedor",
        name: "listadoProveedor",
        component: ListadoProveedor,
    },
    {
        path: "/nuevaCategoria",
        name: "nuevaCategoria",
        component: NuevaCategoria,
    },
    {
        path: "/listadoCategoria",
        name: "listadoCategoria",
        component: ListadoCategoria,
    },
    {
        path: "/nuevoArticulo",
        name: "nuevoArticulo",
        component: NuevoArticulo,
    },
    {
        path: "/listadoArticulo",
        name: "listadoArticulo",
        component: ListadoArticulo,
    },
    {
        path: "/nuevoMedioPago",
        name: "nuevoMedioPago",
        component: NuevoMedioPago,
    },
    {
        path: "/listadoMedioPago",
        name: "listadoMedioPago",
        component: ListadoMedioPago,
    },
    {
        path: "/inventarioStock",
        name: "inventarioStock",
        component: InventarioStock,
    },
    {
        path: "/estadisticasVentas",
        name: "estadisticasVentas",
        component: EstadisticasVentas,
    },
    {
        path: "/grupos",
        name: "grupos",
        component: GruposComponent,
    },
    {
        path: "/acciones",
        name: "acciones",
        component: AccionesComponent,
    },
    {
        path: "/gruposAcciones",
        name: "gruposAcciones",
        component: GruposAccionesComponent,
    },
    {
        path: "/notfound",
        name: "notFound",
        component: NotFoundComponent,
    },
];

export default routes;
