
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import VueRouter from 'vue-router'

require('./bootstrap');
Vue.use(VueRouter);

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/', component: require('./components/PhoneListComponent.vue')},
        {path: '/phone/new', component: require('./components/NewPhoneComponent.vue')},
        {path: '/keyword/:id', component: require('./components/KeywordComponent.vue')},
        {path: '/phone/edit/:id', component: require('./components/UpdatePhoneComponent.vue')}
    ]
});

const app = new Vue({
    router
}).$mount('#app');