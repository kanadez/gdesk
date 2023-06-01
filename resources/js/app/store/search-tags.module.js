import SearchTagsService from '../services/search-tags.service';

const init = {
    loading: false,
    finded: [],
}

export const searchTags = {
    namespaced: true,
    state: init,
    actions: {
        find({commit}, params) {
            return SearchTagsService.find(params).then(
                data => {
                    commit('findSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
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
        },
        findFailure(state) {
            state.finded = [];
        },
        clearFinded(state) {
            state.finded = [];
        }
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
