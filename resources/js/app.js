import './bootstrap';

// import router from './routes';

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('thenavigation', require('./components/TheNavigation.vue').default);

new Vue({
    el: '#app',
    delimiters: ['${', '}']
    // router
});
