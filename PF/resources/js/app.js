/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('sidebar-admin', require('./components/SideBar/SidebarAdminComponent.vue').default);
Vue.component('sidebar-doctor', require('./components/SideBar/SidebarDoctorComponent.vue').default);
Vue.component('sidebar-asociado', require('./components/SideBar/SidebarSocio.vue').default);
Vue.component('sidebar-secretaria', require('./components/SideBar/SidebarSecreComponent.vue').default);
Vue.component('sidebar-component', require('./components/SideBar/SidebarComponent.vue').default);
Vue.component('nav-component', require('./components/Navbar/NavbarComponent.vue').default);


import { Datetime } from 'vue-datetime';

Vue.component('datetime', Datetime);

import router from './routes';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});