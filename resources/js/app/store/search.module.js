import SearchService from '../services/search.service';

const init = {
    loading: false,
    search: null,
}

export const search = {
    namespaced: true,
    state: init,
    actions: {
        find({commit}, params) {
            commit('startLoading');
            return SearchService.find(params).then(
                data => {
                    commit('stopLoading');
                    commit('findSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading', false);
                    commit('findFailure');
                    return Promise.reject(error);
                }
            );
        },


    },
    mutations: {
        startLoading(state) {
            state.loading = true;
        },
        stopLoading(state) {
            state.loading = false;
        },
        findSuccess(state, search) {
            state.search = search;
        },
        findFailure(state) {
            state.search = null;
        },
    },
    getters: {
        search: (state) => {
            return state.search;
        },
        loading: (state) => {
            return state.loading;
        },
    },
}
