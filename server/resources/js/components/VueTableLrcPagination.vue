<template>
    <div v-show="tablePagination && tablePagination.last_page > 1" :class="css.wrapperClass">
        <a @click="loadPage(1)"
           :class="['btn-nav', css.linkClass, isOnFirstPage ? css.disabledClass : '']">
            <i v-if="css.icons.first != ''" :class="[css.icons.first]"></i>
            <span v-else>&laquo;</span>
        </a>
        <a @click="loadPage('prev')"
           :class="['btn-nav', css.linkClass, isOnFirstPage ? css.disabledClass : '']">
            <i v-if="css.icons.next != ''" :class="[css.icons.prev]"></i>
            <span v-else>&nbsp;&lsaquo;</span>
        </a>
        <template v-if="notEnoughPages">
            <template v-for="n in totalPage">
                <a @click="loadPage(n)"
                   :class="[css.pageClass, isCurrentPage(n) ? css.activeClass : '']"
                   v-html="n">
                </a>
            </template>
        </template>
        <template v-else>
            <template v-for="n in windowSize">
                <a @click="loadPage(windowStart+n-1)"
                   :class="[css.pageClass, isCurrentPage(windowStart+n-1) ? css.activeClass : '']"
                   v-html="windowStart+n-1">
                </a>
            </template>
        </template>
        <a @click="loadPage('next')"
           :class="['btn-nav', css.linkClass, isOnLastPage ? css.disabledClass : '']">
            <i v-if="css.icons.next != ''" :class="[css.icons.next]"></i>
            <span v-else>&rsaquo;&nbsp;</span>
        </a>
        <a @click="loadPage(totalPage)"
           :class="['btn-nav', css.linkClass, isOnLastPage ? css.disabledClass : '']">
            <i v-if="css.icons.last != ''" :class="[css.icons.last]"></i>
            <span v-else>&raquo;</span>
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            css: {
                type: Object,
                default () {
                    return {
                        wrapperClass: 'vuetable-pagination vuetable-pagination-right',
                        activeClass: 'vuetable-pagination-active',
                        disabledClass: 'vuetable-pagination-disabled',
                        pageClass: 'vuetable-pagination-item',
                        linkClass: 'vuetable-pagination-icon vuetable-pagination-item',
                        paginationClass: 'ui bottom attached segment grid',
                        paginationInfoClass: 'left floated left aligned six wide column',
                        dropdownClass: 'ui search dropdown',
                        icons: {
                            first: '',
                            prev: '',
                            next: '',
                            last: '',
                        }
                    }
                }
            },
            onEachSide: {
                type: Number,
                default () {
                    return 2
                }
            },
        },
        data: function() {
            return {
                eventPrefix: 'vuetable-pagination:',
                tablePagination: null
            }
        },
        computed: {
            totalPage () {
                return this.tablePagination === null
                    ? 0
                    : this.tablePagination.last_page
            },
            isOnFirstPage () {
                return this.tablePagination === null
                    ? false
                    : this.tablePagination.current_page === 1
            },
            isOnLastPage () {
                return this.tablePagination === null
                    ? false
                    : this.tablePagination.current_page === this.tablePagination.last_page
            },
            notEnoughPages () {
                return this.totalPage < (this.onEachSide * 2) + 4
            },
            windowSize () {
                return this.onEachSide * 2 +1;
            },
            windowStart () {
                if (!this.tablePagination || this.tablePagination.current_page <= this.onEachSide) {
                    return 1
                } else if (this.tablePagination.current_page >= (this.totalPage - this.onEachSide)) {
                    return this.totalPage - this.onEachSide*2
                }
                return this.tablePagination.current_page - this.onEachSide
            },
        },
        methods: {
            loadPage (page) {
                this.$emit(this.eventPrefix+'change-page', page)
            },
            isCurrentPage (page) {
                return page === this.tablePagination.current_page
            },
            setPaginationData (tablePagination) {
                this.tablePagination = tablePagination
            },
            resetData () {
                this.tablePagination = null
            }
        }
    }
</script>

<style scoped>
    .vuetable-pagination {
        font-size:150%;
    }
    .vuetable-pagination-info {
        margin-top: auto;
        margin-bottom: auto;
    }
    .vuetable-pagination-right {
        float:right;
    }
    .vuetable-pagination-item {
        border:solid 1px darkgray;
        padding:10px;
        margin:5px;
    }
    .vuetable-pagination-active {
        background: #c4ffc4;
    }
</style>
