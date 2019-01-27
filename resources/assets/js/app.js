
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform'

import swal from 'sweetalert2'

window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueRouter from 'vue-router'

Vue.use(VueRouter)

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    {
      path: '/profile',
      name: 'Profile',
      component: require('./components/Profile.vue'),
      props: true
    },
    { path: '/user', component: require('./components/User.vue') },
    { path: '/course', component: require('./components/Course.vue') },
    { path: '/enrollment', component: require('./components/Enrollment.vue') }
  ]

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
  })
  
  Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
  })

  Vue.filter('viewDate', function (date) {
    return moment(date).format('MMMM Do YYYY');
  })

  window.Fire = new Vue();
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});