/**
 * Vue
 */
import Vue from 'vue/dist/vue.esm.js';
window.Vue = Vue;
Vue.use(VueRouter);

/**
 * VueRouter
 */
import VueRouter from 'vue-router';

/**
 * Axios
 */
import axios from 'axios';
window.axios = axios;
window.axios.defaults.baseURL = 'https://jsonplaceholder.typicode.com';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
