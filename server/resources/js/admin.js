require('./bootstrap');
window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);
window.axios = require('axios').default;
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead);

window.jQuery = window.$ = require('jquery');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('basic-select', VueSearchSelect.BasicSelect); // FIXME this is available on npm - get it from there

import store from './admin-store';

let LexTable = Vue.component('LexTable');
let LexEditor = Vue.component('LexEditor');

var routes = [
    {path: '/etyma', component: LexTable, props: {
            route_title: 'Etymas',
            route_name: 'etyma',
            enable_pagination: false,
            fields: [
                'id',
                'old_id',
                'order',
                'page_number',
                'entry',
                'gloss',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/etyma_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'etyma',
            'fields':[
                {name:'old_id',label:'Old Id', type:'text'},
                {name:'order',label:'Order', type:'text'},
                {name:'entry',label:'Entry', type:'text'},
                {name:'gloss',label:'Gloss', type:'text'},
                {name:'semantic_fields',label:'Semantic Fields', type:'info',view_fn:function(etyma) {return etyma.semantic_fields.map(function(sf) {return sf.text;}).join(' | ');}},
                {name:'cross_references',label:'Cross References', type:'info',view_fn:function(etyma) {return etyma.cross_references.map(function(cr) {return cr.entry;}).join(', ');}},
                {name:'page_number',label:'Page Number', type:'text'},
                {name:'reflexes',label:'Reflexes', type:'info',view_fn:function(etyma) {return etyma.reflexes.map(
                        function(cr) {return cr.language.name + ': ' + cr.entries.map(function (en) {return en.entry;}).join(',')}).join(' | ');}
                }
            ]})},

    {path: '/reflex', component: LexTable, props: {
            route_title: 'Reflexes',
            route_name: 'reflex',
            enable_pagination: true,
            fields: [
                'id',
                'gloss',
                {name:'__slot:language_name',title:'Language'},
                'lang_attribute',
                'class_attribute',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/reflex_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'reflex',
            'fields':[
                {name:'gloss',label:'Gloss', type:'text'},
                {name:'etymas',label:'Etymas', type:'info',view_fn:function(reflex) {return reflex.etymas ? reflex.etymas.map(function(etyma){return etyma.entry}).join(", ") : "";}},
                {name:'language_id',label:'Language',type:'relation',relation:'lang',view_fn:function(lang) {return lang.name;}},
                {name:'sources',label:'Sources',type:'info',view_fn:function(reflex) {return reflex.sources ? reflex.sources.map(function(source){return source.code}).join(", ") : "";}},
                {name:'lang_attribute',label:'Lang Attribute',type:'text'},
                {name:'class_attribute',label:'Class Attribute',type:'text'}
            ]})},

    {path: '/reflex_entry', component: LexTable, props: {
            route_title: 'Reflex Entries',
            route_name: 'reflex_entry',
            enable_pagination: true,
            fields: [
                'id',
                'reflex_id',
                {name:'__slot:reflex_languagename_gloss',title:'Reflex'},
                'entry',
                'order',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/reflex_entry_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'reflex_entry',
            'fields':[
                {name:'entry',label:'Entry', type:'text'},
                {name:'order',label:'Order', type:'text'},
                {name:'reflex_id',label:'Reflex ID',type:'text'},
            ]})},

    {path: '/reflex_pos', component: LexTable, props:{
            route_title: 'Reflex → Part of Speech',
            route_name: 'reflex_pos',
            enable_pagination: true,
            fields: [
                'id',
                'reflex_id',
                {name:'__slot:reflex_languagename_gloss',title:'Reflex'},
                'text',
                'order',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/reflex_pos_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'reflex_pos',
            'fields':[
                {name:'text',label:'Text', type:'text', notes:"This must match entries in Lexicon Parts of Speech. You can join multiple ones together with a period. Test the public page after you update this. If this doesn't match the Parts of Speech, you'll get an error."},
                {name:'order',label:'Order', type:'text'},
                {name:'reflex_id',label:'Reflex ID',type:'text'},
            ]})},

    {path: '/sem_cat', component: LexTable, props:{
            route_title: 'Semantic Categories',
            route_name: 'sem_cat',
            enable_pagination: false,
            fields: [
                'id',
                'text',
                'number',
                'abbr',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/sem_cat_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'sem_cat',
            'fields':[
                {name:'number',label:'Number', type:'text'},
                {name:'text',label:'Text', type:'text'},
                {name:'abbr',label:'Abbr', type:'text'}
            ]})},

    {path: '/sem_field', component: LexTable, props:{
            route_title: 'Semantic Fields',
            route_name: 'sem_field',
            enable_pagination: false,
            fields: [
                'id',
                'text',
                'number',
                'abbr',
                {name:'__slot:semantic_category_text',title:'Semantic Category'},
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/sem_field_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'sem_field',
            'fields':[
                {name:'number',label:'Number', type:'text'},
                {name:'text',label:'Text', type:'text'},
                {name:'abbr',label:'Abbr', type:'text'},
                {name:'semantic_category_id',label:'Semantic Category',type:'relation',relation:'sem_cat',view_fn:function(sem_cat) {return sem_cat.text;}}
            ]})},

    {path: '/lang_fam', component: LexTable, props:{
            route_title: 'Language Families',
            route_name: 'lang_fam',
            enable_pagination: false,
            fields: [
                'id',
                'name',
                'order',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/lang_fam_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'lang_fam',
            'fields':[
                {name:'name',label:'Name', type:'text'},
                {name:'order',label:'Order', type:'text'}
            ]})},

    {path: '/lang_subfam', component: LexTable, props:{
            route_title: 'Language Subfamilies',
            route_name: 'lang_subfam',
            enable_pagination: false,
            fields: [
                'id',
                'name',
                'order',
                {name:'__slot:language_family_name',title:'Family'},
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/lang_subfam_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'lang_subfam',
            'fields':[
                {name:'name',label:'Name', type:'text'},
                {name:'order',label:'Order', type:'text'},
                {name:'family_id',label:'Family', type:'relation',relation:'lang_fam',view_fn:function(lang_fam) {return lang_fam.name;}}
            ]})},

    {path: '/lang', component: LexTable, props:{
            route_title: 'Languages',
            route_name: 'lang',
            enable_pagination: false,
            fields: [
                'id',
                'name',
                {name:'__slot:language_sub_family_name',title:'Sub Family → Family'},
                'order',
                'abbr',
                'aka',
                'override_family',
                'custom_sort',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/lang_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,    'route_name':'lang',
            'fields':[
                {name:'name',label:'Name', type:'text'},
                {name:'order',label:'Order', type:'text'},
                {name:'abbr',label:'Abbr', type:'text'},
                {name:'sub_family_id',label:'SubFamily', type:'relation',relation:'lang_subfam',view_fn:function(lang_subfam) {return lang_subfam.name + ' → ' + lang_subfam.language_family.name;}},
                {name:'aka',label:'AKA', type:'text'},
                {name:'override_family',label:'Override Family', type:'text', notes:"This is for the reflex page. This value will show instead of the Family that this Language belongs to"},
                {name:'custom_sort',label:'Custom Sort', type:'text', notes:"This is used to set the sort order for the lex_lang_reflexes page and should be a comma separated list of characters in the order they should be sorter. Do not use unicode code points, just paste in unicode characters. Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z If character aren't separated by a comma, they are considered equal. In the next example, p,P and π are considered the same. Example: aAÄ,bB,cC,dD,eE,fF,gG,hH,iI,Jj,Kk,Ll,Mm,Nn,Oo,Ppπ,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz"}
            ]})},

    {path: '/source', component: LexTable, props:{
            route_title: 'Sources',
            route_name: 'source',
            enable_pagination: false,
            fields: [
                'id',
                'code',
                'display',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/source_editor/:id', component: LexEditor, props:(route)=>({id:route.params.id,
            'route_name':'source',
            'fields':[
                {name:'code',label:'Code', type:'text'},
                {name:'display',label:'Display', type:'text'}
            ]
        })},

    {path: '/pos', component: LexTable, props:{
            route_title: 'Parts of Speech',
            route_name: 'pos',
            enable_pagination: false,
            fields: [
                'id',
                'code',
                'display',
                {name:'__slot:tools',title:''}
            ]
        }},
    {path: '/pos_editor/:id', component:LexEditor, props: (route)=>({id:route.params.id,    'route_name':'pos',
            'fields':[
                {name:'code',label:'Code', type:'text'},
                {name:'display',label:'Display', type:'text'}
            ]})}
];

var router = new VueRouter({routes});

// FIXME haven't gotten around to building page-level components yet; all this window.admin_app_* stuff
// can move down into those page-level components once they're written
const admin_app = new Vue({
    el: '#admin_app',
    router,
    store,
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

window.onhashchange = function() {
    // nav links to get here aren't expressed via the Vue <router-link>
    // tag yet; handle hash changes manually.
    admin_app.$router.push(location.hash.substring(1));
};
