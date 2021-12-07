require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'

//Routes
import { routes } from './routes';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import common from './common'
import store from "./store";
Vue.prototype.$store = store;
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueAxios, axios)
Vue.use(ViewUI)
Vue.mixin(common)

//Register Routes
export const router = new VueRouter({
    base: '/',
    mode: 'history',
    routes,
    store

});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 */

Vue.component('app-header', require('./components/HeaderComponent.vue').default);
// Vue.component('app-footer', require('./components/FooterComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router
}).$mount('#app')
