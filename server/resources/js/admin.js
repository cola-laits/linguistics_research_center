require('./bootstrap');
window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);
window.axios = require('axios').default;

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead);
import VueTagsInput from '@johmun/vue-tags-input';
Vue.component('tags-input', VueTagsInput);

window.jQuery = window.$ = require('jquery');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('basic-select', VueSearchSelect.BasicSelect); // FIXME this is available on npm - get it from there

import store from './admin-store';

let LexTable = Vue.component('LexTable');
let LexEditor = Vue.component('LexEditor');

var routes = [
    {path: '/issues', component:Vue.component('IssueList'), props:(route)=>({pointer:route.query.pointer})},
    {path: '/issues/new', component:Vue.component('IssueCreate')},
    {path: '/issue/:id', component:Vue.component('IssueDisplay'), props:true}
];

var router = new VueRouter({routes});

// FIXME haven't gotten around to building page-level components yet; all this window.admin_app_initial_state stuff
// can move down into those page-level components once they're written
const admin_app = new Vue({
    el: '#admin_app',
    router,
    store,
    data() {
        if (window.admin_app_initial_state) {
            console.log("FIXME: initial_state is still being used.  Track it down and remove it.");
            return window.admin_app_initial_state;
        } else {
            return {};
        }
    },
    methods: {
        countOpenIssues() {
            return this.$store.getters.getIssuesByStatus('open').length;
        },
        getIssueBadgeClass() {
            if (this.countOpenIssues() === 0) {
                return 'd-none';
            }
            return 'badge-warning';
        }
    }
});

store.dispatch('init');

window.onhashchange = function() {
    // nav links to get here aren't expressed via the Vue <router-link>
    // tag yet; handle hash changes manually.
    admin_app.$router.push(location.hash.substring(1));
};
