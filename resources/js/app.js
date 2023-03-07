/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import EmpresaComponent from './components/EmpresaComponent.vue';
import ListadoEmpresa from './components/ListadoEmpresaComponent.vue';
import FacturacionComponent from './components/FacturacionComponent.vue';
import ListadoFactura from './components/ListadoFactura.vue';
import ComprasComponent from './components/ComprasComponent.vue';
import ListadoCompras from './components/ListadoComprasComponent.vue';
import NuevoUsuario from './components/NuevoUserComponent.vue';
import ListadoUsuario from './components/ListadoUserComponent.vue';
import NuevoCliente from './components/NuevoClienteComponent.vue';
import ListadoCliente from './components/ListadoClienteComponent.vue';
import NuevoProveedor from './components/NuevoProveedorComponent.vue';
import ListadoProveedor from './components/ListadoProveedorComponent.vue';
import NuevoMedioPago from './components/NuevoMedioPagoComponent.vue';
import ListadoMedioPago from './components/ListadoMedioPagoComponent.vue';
import GraficosView from './components/GraficosComponent.vue';

  const routes = [

    {
        path:'/dashboard',
        name:'dashboard',
        component:GraficosView
    },
    {
        path:'/empresa',
        name:'empresa',
        component:EmpresaComponent
    },
    {
        path:'/listadoEmpresa',
        name:'listadoEmpresa',
        component:ListadoEmpresa
    },
    {
        path:'/facturacion',
        name:'facturacion',
        component:FacturacionComponent
    },
    {
        path:'/listadofacturacion',
        name:'listadofacturacion',
        component:ListadoFactura
    },
    {
        path:'/compras',
        name:'compras',
        component:ComprasComponent
    },
    {
        path:'/listadoCompras',
        name:'listadoCompras',
        component:ListadoCompras
    },
    {
        path:'/nuevoUsuario',
        name:'nuevoUsuario',
        component:NuevoUsuario
    },
    {
        path:'/listadoUsuario',
        name:'listadoUsuario',
        component:ListadoUsuario
    },
    {
        path:'/nuevoCliente',
        name:'nuevoCliente',
        component:NuevoCliente
    },
    {
        path:'/listadoCliente',
        name:'listadoCliente',
        component:ListadoCliente
    },
    {
        path:'/nuevoProveedor',
        name:'nuevoProveedor',
        component:NuevoProveedor
    },
    {
        path:'/listadoProveedor',
        name:'listadoProveedor',
        component:ListadoProveedor
    },
    {
        path:'/nuevoMedioPago',
        name:'nuevoMedioPago',
        component:NuevoMedioPago
    },
    {
        path:'/listadoMedioPago',
        name:'listadoMedioPago',
        component:ListadoMedioPago
    }
];

const router =  new VueRouter({routes});

var pathServer = window.location.hostname;
const app = new Vue({
    el: '#app',
    data:{
        menu:0,
        path:'http://'+pathServer+':8000',
    },
    router
});
