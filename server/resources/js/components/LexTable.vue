<template>
    <div>
        <h1>{{route_title}}</h1>
        <button style="float:right;" class="btn btn-primary" @click="open_editor('new')">Add New</button>
        <vue-table-lrc ref="vuetable"
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
        </vue-table-lrc>
        <vue-table-lrc-pagination v-show="enable_pagination"
                                  ref="pagination"
                                  @vuetable-pagination:change-page="onChangePage"
        ></vue-table-lrc-pagination>
    </div>
</template>

<script>
    export default {
        props: ['route_title','route_name','enable_pagination','fields'],
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
        }
    }
</script>
