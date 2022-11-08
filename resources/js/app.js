/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
 
import VueRouter from 'vue-router';
Vue.use(VueRouter); 
  

Vue.component('compras-component',require('./components/ComprasComponent.vue').default)
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('listado-user-component', require('./components/ListadoUserComponent.vue').default);
Vue.component('nuevo-user-component', require('./components/NuevoUserComponent.vue').default);
Vue.component('facturacion-component', require('./components/FacturacionComponent.vue').default);

import ComprasComponent from './components/ComprasComponent.vue';
import FacturacionComponent from './components/FacturacionComponent.vue';
import ListadoFactura from './components/ListadoFactura.vue';
import NuevoUsuario from './components/NuevoUserComponent.vue';
import ListadoUsuario from './components/ListadoUserComponent.vue';

  const routes = [
    {
        path:'/compras',
        name:'compras',
        component:ComprasComponent
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
        path:'/nuevoUsuario',
        name:'nuevoUsuario',
        component:NuevoUsuario
    },
    {
        path:'/listadoUsuario',
        name:'listadoUsuario',
        component:ListadoUsuario
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
