@extends('admin_layout')

@section('title') Lexicon Admin @stop

@section('content')
    <div id="vue-app">
        <router-view></router-view>
    </div>
    <br><br><br>

    @verbatim
        <script type="text/x-template" id="TableComp">
            <div>
                <h1>{{route_title}}</h1>
                <button style="float:right;" class="btn btn-primary" @click="open_editor('new')">Add New</button>
                <vuetable ref="vuetable"
                          :api-url="'/admin2/lexicon/api/'+route_name"
                          @vuetable:pagination-data="onPaginationData"
                          :fields="fields"
                >
                    <div slot="tools" slot-scope="props">
                        <div style="display:flex;justify-content:flex-end;">
                            <button class="btn btn-primary" @click="open_editor(props.rowData.id)">Edit / Delete</button>
                        </div>
                    </div>

                    <div slot="language_name" slot-scope="props">
                        <div>{{props.rowData.language.name}}</div>
                    </div>

                    <div slot="language_family_name" slot-scope="props">
                        <div>{{props.rowData.language_family.name}}</div>
                    </div>

                    <div slot="language_sub_family_name" slot-scope="props">
                        <div>{{props.rowData.language_sub_family.name}} → {{props.rowData.language_sub_family.language_family.name}}</div>
                    </div>

                    <div slot="semantic_category_text" slot-scope="props">
                        <div>{{props.rowData.semantic_category.text}}</div>
                    </div>

                    <div slot="reflex_languagename_gloss" slot-scope="props">
                        <div>{{props.rowData.reflex.language.name}} → {{props.rowData.reflex.gloss}}</div>
                    </div>
                </vuetable>
                <vuetable-pagination v-show="enable_pagination"
                                     ref="pagination"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
        </script>

        <script type="text/x-template" id="EditorComp">
            <div>
                <form>
                    <div class="container">
                    <input type="hidden" name="id" :value="item.id">
                    <div v-for="field in fields">
                        <div class="row" v-if="field.notes">
                            <div class="col-md-2"></div>
                            <div class="col-md-10">{{field.notes}}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="display:flex;justify-content:flex-end;"><h4>{{field.label}}</h4></div>
                            <div class="col-md-10">
                                <div v-if="field.type=='text'">
                                    <input type="text" style="width:75%" :name="field.name" v-model="item[field.name]">
                                </div>
                                <div v-if="field.type=='relation'">
                                    <div v-if="typeof(lookups[field.relation])=='undefined' || lookups[field.relation].length==0">
                                        Please wait...
                                    </div>
                                    <div v-else>
                                        <select :name="field.name" v-model="item[field.name]">
                                            <option v-for="lookup in lookups[field.relation]" :value="lookup.id">{{field.view_fn(lookup)}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="field.type=='info'">
                                    {{field.view_fn(item)}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <button type="button" @click="cancel_change()" class="btn btn-default">Cancel</button>
                    <button type="button" @click="edit_change()" class="btn btn-primary">Save</button>
                    <button type="button" @click="delete_change()" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </script>

        <script>
            var TableComp = {
                template: '#TableComp',
                components: {
                    Vuetable,
                    VuetablePagination
                },
                methods: {
                    onPaginationData: function (paginationData) {
                        this.$refs.pagination.setPaginationData(paginationData);
                    },
                    onChangePage: function (page) {
                        this.$refs.vuetable.changePage(page);
                        window.scrollTo(0,0);
                    },
                    open_editor: function(id) {
                        document.location.hash = this.route_name+'_editor/'+id;
                    }
                },
            };

            var editor_comp_generator = function(route_name, fields) { return {
                template: '#EditorComp',
                props: ['id'],
                data: function() { return {
                    route_name: route_name,
                    fields: fields,
                    lookups: {},
                    item: {}
                }},
                methods: {
                    cancel_change: function() {
                        this.$router.push('/'+this.route_name);
                    },
                    edit_change: function() {
                        var comp = this;
                        axios.post('/admin2/lexicon/api/action/edit?type='+this.route_name+'&id='+this.id,{
                            item:comp.item
                        }).then(function (response) {
                            alert("Edits successful!");
                            comp.$router.push('/'+comp.route_name);
                        }).catch(function(response) {
                            alert("ERROR: Unable to save.  Please try again or contact a developer.");
                        });
                    },
                    delete_change: function() {
                        if (!confirm("Are you sure you want to delete this?")) {
                            return;
                        }
                        var comp = this;
                        axios.post('/admin2/lexicon/api/action/delete?type='+this.route_name+'&id='+this.id,{
                            item:comp.item
                        }).then(function (response) {
                            alert("Delete successful!");
                            comp.$router.push('/'+comp.route_name);
                        }).catch(function(response) {
                            alert("ERROR: Unable to delete.  Please try again or contact a developer.");
                        });
                    }
                },
                mounted: function() {
                    var comp = this;
                    axios.get('/admin2/lexicon/api/action/get?type='+this.route_name+'&id='+this.id)
                        .then(function(response) {
                            comp.item = response.data.item;
                        }).catch(function(response) {
                            alert("ERROR: Unable to load.  Please try again or contact a developer.");
                        });
                    this.fields.forEach(function (f) {
                        if (f.type==='relation') {
                            axios.get('/admin2/lexicon/api/'+f.relation+"?skip_pagination=true")
                                .then(function(response) {
                                    Vue.set(comp.lookups, f.relation, response.data.data);
                                }).catch(function(response) {
                                    alert("ERROR: Unable to load.  Please try again or contact a developer.");
                                });
                        }
                    });
                }
            }};
        </script>

        <script>
            var EtymaComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var ReflexComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var ReflexEntryComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var ReflexPosComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var SemCategoryComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var SemFieldComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var LangFamComp = Object.assign({
                data: function() {return {
                    route_title: 'Language Families',
                    route_name: 'lang_fam',
                    enable_pagination: false,
                    fields: [
                        'id',
                        'name',
                        'order',
                        {name:'__slot:tools',title:''}
                    ]
                }}
            }, TableComp);

            var LangSubfamComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var LangComp = Object.assign({
                data: function() {return {
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
                }}
            }, TableComp);

            var SourceComp = Object.assign({
                data: function() {return {
                    route_title: 'Sources',
                    route_name: 'source',
                    enable_pagination: false,
                    fields: [
                        'id',
                        'code',
                        'display',
                        {name:'__slot:tools',title:''}
                    ]
                }}
            }, TableComp);

            var PosComp = Object.assign({
                data: function() {return {
                    route_title: 'Parts of Speech',
                    route_name: 'pos',
                    enable_pagination: false,
                    fields: [
                        'id',
                        'code',
                        'display',
                        {name:'__slot:tools',title:''}
                    ]
                }}
            }, TableComp);

            var EtymaEditorComp = editor_comp_generator(
                'etyma',
                [
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
                ]);

            var ReflexEditorComp = editor_comp_generator(
                'reflex',
                [
                    {name:'gloss',label:'Gloss', type:'text'},
                    {name:'etymas',label:'Etymas', type:'info',view_fn:function(reflex) {return reflex.etymas.map(function(etyma){return etyma.entry}).join(", ");}},
                    {name:'language_id',label:'Language',type:'relation',relation:'lang',view_fn:function(lang) {return lang.name;}},
                    {name:'sources',label:'Sources',type:'info',view_fn:function(reflex) {return reflex.sources.map(function(source){return source.code}).join(", ");}},
                    {name:'lang_attribute',label:'Lang Attribute',type:'text'},
                    {name:'class_attribute',label:'Class Attribute',type:'text'}
                ]);

            var ReflexEntryEditorComp = editor_comp_generator(
                'reflex_entry',
                [
                    {name:'entry',label:'Entry', type:'text'},
                    {name:'order',label:'Order', type:'text'},
                    {name:'reflex_id',label:'Reflex ID',type:'text'},
                ]);

            var ReflexPosEditorComp = editor_comp_generator(
                'reflex_pos',
                [
                    {name:'text',label:'Text', type:'text', notes:"This must match entries in Lexicon Parts of Speech. You can join multiple ones together with a period. Test the public page after you update this. If this doesn't match the Parts of Speech, you'll get an error."},
                    {name:'order',label:'Order', type:'text'},
                    {name:'reflex_id',label:'Reflex ID',type:'text'},
                ]);

            var SemCategoryEditorComp = editor_comp_generator(
                'sem_cat',
                [
                    {name:'number',label:'Number', type:'text'},
                    {name:'text',label:'Text', type:'text'},
                    {name:'abbr',label:'Abbr', type:'text'}
                ]);

            var SemFieldEditorComp = editor_comp_generator(
                'sem_field',
                [
                    {name:'number',label:'Number', type:'text'},
                    {name:'text',label:'Text', type:'text'},
                    {name:'abbr',label:'Abbr', type:'text'},
                    {name:'semantic_category_id',label:'Semantic Category',type:'relation',relation:'sem_cat',view_fn:function(sem_cat) {return sem_cat.text;}}
                ]);

            var LangFamEditorComp = editor_comp_generator(
                'lang_fam',
                [
                    {name:'name',label:'Name', type:'text'},
                    {name:'order',label:'Order', type:'text'}
                ]);

            var LangSubfamEditorComp = editor_comp_generator(
                'lang_subfam',
                [
                    {name:'name',label:'Name', type:'text'},
                    {name:'order',label:'Order', type:'text'},
                    {name:'family_id',label:'Family', type:'relation',relation:'lang_fam',view_fn:function(lang_fam) {return lang_fam.name;}}
                ]);

            var LangEditorComp = editor_comp_generator(
                'lang',
                [
                    {name:'name',label:'Name', type:'text'},
                    {name:'order',label:'Order', type:'text'},
                    {name:'abbr',label:'Abbr', type:'text'},
                    {name:'sub_family_id',label:'SubFamily', type:'relation',relation:'lang_subfam',view_fn:function(lang_subfam) {return lang_subfam.name + ' → ' + lang_subfam.language_family.name;}},
                    {name:'aka',label:'AKA', type:'text'},
                    {name:'override_family',label:'Override Family', type:'text', notes:"This is for the reflex page. This value will show instead of the Family that this Language belongs to"},
                    {name:'custom_sort',label:'Custom Sort', type:'text', notes:"This is used to set the sort order for the lex_lang_reflexes page and should be a comma separated list of characters in the order they should be sorter. Do not use unicode code points, just paste in unicode characters. Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z If character aren't separated by a comma, they are considered equal. In the next example, p,P and π are considered the same. Example: aAÄ,bB,cC,dD,eE,fF,gG,hH,iI,Jj,Kk,Ll,Mm,Nn,Oo,Ppπ,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz"}
                ]);

            var SourceEditorComp = editor_comp_generator(
                'source',
                [
                    {name:'code',label:'Code', type:'text'},
                    {name:'display',label:'Display', type:'text'}
                ]);

            var PosEditorComp = editor_comp_generator(
                'pos',
                [
                    {name:'code',label:'Code', type:'text'},
                    {name:'display',label:'Display', type:'text'}
                ]);

            var routes = [
                {path: '/etyma', component: EtymaComp},
                {path: '/etyma_editor/:id', component: EtymaEditorComp, props:true},
                {path: '/reflex', component: ReflexComp},
                {path: '/reflex_editor/:id', component: ReflexEditorComp, props:true},
                {path: '/reflex_entry', component: ReflexEntryComp},
                {path: '/reflex_entry_editor/:id', component: ReflexEntryEditorComp, props:true},
                {path: '/reflex_pos', component: ReflexPosComp},
                {path: '/reflex_pos_editor/:id', component: ReflexPosEditorComp, props:true},
                {path: '/sem_cat', component: SemCategoryComp},
                {path: '/sem_cat_editor/:id', component: SemCategoryEditorComp, props:true},
                {path: '/sem_field', component: SemFieldComp},
                {path: '/sem_field_editor/:id', component: SemFieldEditorComp, props:true},
                {path: '/lang_fam', component: LangFamComp},
                {path: '/lang_fam_editor/:id', component: LangFamEditorComp, props:true},
                {path: '/lang_subfam', component: LangSubfamComp},
                {path: '/lang_subfam_editor/:id', component: LangSubfamEditorComp, props:true},
                {path: '/lang', component: LangComp},
                {path: '/lang_editor/:id', component: LangEditorComp, props:true},
                {path: '/source', component: SourceComp},
                {path: '/source_editor/:id', component: SourceEditorComp, props:true},
                {path: '/pos', component: PosComp},
                {path: '/pos_editor/:id', component:PosEditorComp, props:true}
            ];

            var router = new VueRouter({routes});

            var vm = new Vue({
                el: '#vue-app',
                router,
                data: {}
            });

            window.onhashchange = function() {
                // nav links to get here aren't expressed via the Vue <router-link>
                // tag; handle hash changes manually.
                console.log(location.hash);
                vm.$router.push(location.hash.substring(1));
            };
        </script>
    @endverbatim
@stop
