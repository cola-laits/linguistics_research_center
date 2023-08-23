require('./bootstrap');
import Vue from 'vue';
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
Vue.component('issue-create', IssueCreate);
import IssueDisplay from './components/IssueDisplay'
Vue.component('issue-display', IssueDisplay);
import RelatedLanguagesSelect from './components/RelatedLanguagesSelect'
Vue.component('related-languages-select', RelatedLanguagesSelect)

Vue.component('basic-select', VueSearchSelect.BasicSelect); // FIXME this is available on npm - get it from there

const admin_app = new Vue({
    el: '#admin_app'
});
