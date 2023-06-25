const init = {
    loading: false,
    headerCollapsed: false,
}

export const app = {
    namespaced: true,
    state: init,
    actions: {

    },
    mutations: {
        collapseHeader(state) {
            state.headerCollapsed = true;
        },
        expandeHeader(state) {
            state.headerCollapsed = false;
        },
    },
    getters: {
        headerCollapsed: (state) => {
            return state.headerCollapsed;
        },
        loading: (state) => {
            return state.loading;
        },
    },
}
