import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const state = {
    issues: []
};

const getters = {
    getIssues: (state) => () => {
        return state.issues;
    },
    getIssuesByStatus: (state) => (status) => {
        return state.issues.filter(issue => {return issue.status === status});
    }
};

const mutations = {
    setIssues(state, issues) {
        state.issues = issues;
    }
};

const actions = {
    init(context) {
        actions.loadIssues(context);
    },
    loadIssues({commit}) {
        return window.axios.get('/admin/api/v1/issue')
            .then(response => {
                commit('setIssues', response.data.issues);
            });
    }
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
