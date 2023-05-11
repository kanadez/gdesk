import SearchService from '../services/search.service';

const init = {
    loading: false,
    finded: [],
}

export const search = {
    namespaced: true,
    state: init,
    actions: {
        findByQuery({commit}, params) {
            commit('startLoading');
            return SearchService.findByQuery(params).then(
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

        findByCategory({commit}, params) {
            commit('startLoading');
            return SearchService.findByCategory(params).then(
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
        findSuccess(state, finded) {
            state.finded = finded.results;
            console.log(state.finded)
        },
        findFailure(state) {
            state.finded = [];
        },
    },
    getters: {
        finded: (state) => {
            return state.finded;
        },
        loading: (state) => {
            return state.loading;
        },
    },
}
