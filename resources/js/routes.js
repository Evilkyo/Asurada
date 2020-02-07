import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        name: 'home',
        component: require('./views/Home').default
    },
    {
        path: '/about',
        name: 'about',
        component: require('./views/About').default
    }
];

export default new VueRouter({
    routes,
    linkExactActiveClass: 'is-active',
})
