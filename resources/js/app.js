/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router';
import routes from './router/index';

Vue.use(VueRouter);
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
