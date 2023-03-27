import RoutesService from '../services/routes.service';

const init = {
    loading: false,
    routes: null,
    storedRouteId: null,
    categories: [],
    routeData: {},
}

export const routes = {
    namespaced: true,
    state: init,
    actions: {
        getRoutes({commit}, params) {
            commit('startLoading');
            return RoutesService.getRoutes(params).then(
                data => {
                    commit('stopLoading');
                    commit('getRoutesSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading', false);
                    commit('getRoutesFailure');
                    return Promise.reject(error);
                }
            );
        },

        create({commit}) {
            commit('startLoading');
            return RoutesService.createRoute().then(
                data => {
                    commit('stopLoading');
                    commit('createRouteSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('createRouteFailure');
                    return Promise.reject(error);
                }
            );
        },

        store({commit}, params) {
            commit('startLoading');
            return RoutesService.storeRoute(params).then(
                data => {
                    commit('stopLoading');
                    commit('storeRouteSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('storeRouteFailure');
                    return Promise.reject(error);
                }
            );
        },

        edit({commit}, params) {
            commit('startLoading');
            return RoutesService.editRoute(params).then(
                data => {
                    commit('stopLoading');
                    commit('editRouteSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('editRouteFailure');
                    return Promise.reject(error);
                }
            );
        },

        update({commit}, params) {
            commit('startLoading');
            return RoutesService.updateRoute(params).then(
                data => {
                    commit('stopLoading');
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    return Promise.reject(error);
                }
            );
        },

        destroy({commit}, params) {
            commit('startLoading');
            return RoutesService.destroyRoute(params).then(
                data => {
                    commit('stopLoading');
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
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
        getRoutesSuccess(state, routes) {
            state.routes = routes;
        },
        getRoutesFailure(state) {
            state.routes = null;
        },
        editRouteSuccess(state,  data) {
            state.routeData = data.route.data;
        },
        editRouteFailure(state) {
            state.routeData = null;
        },
        createRouteSuccess(state, data) {
            state.categories = data.categories;
        },
        createRouteFailure(state) {
            state.userCreateFormData = null;
        },
        storeRouteSuccess(state, data) {
            state.storedRouteId = data.route_id;
        },
        storeRouteFailure(state, data) {

        },
    },
    getters: {
        routes: (state) => {
            return state.routes;
        },
        routeData: (state) => {
            return state.routeData;
        },
        storedRouteId: (state) => {
            return state.storedRouteId;
        },
        loading: (state) => {
            return state.loading;
        },
    },
}
