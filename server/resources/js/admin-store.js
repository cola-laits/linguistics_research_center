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
    },
    updateIssue(issue) {
        let found_posn = this.state.issues.findIndex(this_issue => this_issue.id === issue.id);
        if (found_posn === -1) {
            this.state.issues.push(issue);
        } else {
            this.state.issues.splice(found_posn, 1, issue);
        }
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
    },
    updateIssue({commit}, issue) {
        commit('updateIssue', issue);
    }
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
