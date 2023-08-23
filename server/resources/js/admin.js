require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);
window.axios = require('axios').default;

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import VueBootstrapAutocomplete from '@vue-bootstrap-components/vue-bootstrap-autocomplete'
Vue.component('vue-bootstrap-typeahead', VueBootstrapAutocomplete);
import VueTagsInput from '@johmun/vue-tags-input';
Vue.component('tags-input', VueTagsInput);

window.jQuery = window.$ = require('jquery');

import LessonEditor from './components/LessonEditor'
Vue.component('lesson-editor', LessonEditor);
import IssueCreate from './components/IssueCreate'
import IssueDisplay from './components/IssueDisplay'
import RelatedLanguagesSelect from './components/RelatedLanguagesSelect'
Vue.component('related-languages-select', RelatedLanguagesSelect)

Vue.component('basic-select', VueSearchSelect.BasicSelect); // FIXME this is available on npm - get it from there

import store from './admin-store';

var routes = [
    {path: '/issues/new', component:IssueCreate},
    {path: '/issue/:id', component:IssueDisplay, props:true}
];

var router = new VueRouter({routes});

const admin_app = new Vue({
    el: '#admin_app',
    router,
    store
});

store.dispatch('init');

window.onhashchange = function() {
    // nav links to get here aren't expressed via the Vue <router-link>
    // tag yet; handle hash changes manually.
    admin_app.$router.push(location.hash.substring(1));
};
