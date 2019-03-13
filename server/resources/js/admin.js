require('./bootstrap');
window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);
window.axios = require('axios').default;
window.jQuery = window.$ = require('jquery');

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('basic-select', VueSearchSelect.BasicSelect); // FIXME this is available on npm - get it from there

// FIXME haven't gotten around to building page-level components yet; all this window.admin_app_* stuff
// can move down into those page-level components once they're written
const admin_app = new Vue({
    el: '#admin_app',
    data() {
        if (window.admin_app_initial_state) {
            return window.admin_app_initial_state;
        } else {
            return {};
        }
    },
    computed: window.admin_app_computed || {},
    methods: window.admin_app_methods || {}
});
