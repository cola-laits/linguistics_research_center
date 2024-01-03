require('./bootstrap');
import Vue from 'vue';
window.Vue = Vue;
window.axios = require('axios').default;

import { BModal, VBModal } from 'bootstrap-vue'
Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);


import VueBootstrapAutocomplete from '@vue-bootstrap-components/vue-bootstrap-autocomplete'
Vue.component('vue-bootstrap-typeahead', VueBootstrapAutocomplete);
import VueTagsInput from '@johmun/vue-tags-input';
Vue.component('tags-input', VueTagsInput);

window.jQuery = window.$ = require('jquery');

import CKEditor from './components/CkEditor';
Vue.component('ck-editor', CKEditor);

import AudioIcon from './components/AudioIcon'
Vue.component('audio-icon', AudioIcon);
import CommentIcon from './components/CommentIcon'
Vue.component('comment-icon', CommentIcon);
import GlossEditor from './components/GlossEditor'
Vue.component('gloss-editor', GlossEditor);
import InputCustomKeyboard from './components/InputCustomKeyboard'
Vue.component('input-custom-keyboard', InputCustomKeyboard);
import TextareaCustomKeyboard from './components/TextareaCustomKeyboard'
Vue.component('textarea-custom-keyboard', TextareaCustomKeyboard);

import LessonEditor from './components/LessonEditor'
Vue.component('lesson-editor', LessonEditor);
