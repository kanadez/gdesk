import LocationsService from '../services/locations.service';

const init = {
    loading: false,
    locations: null,
    storedLocationId: null,
    categories: [],
    locationData: {},
}

export const locations = {
    namespaced: true,
    state: init,
    actions: {
        getLocations({commit}, params) {
            commit('startLoading');
            return LocationsService.getLocations(params).then(
                data => {
                    commit('stopLoading');
                    commit('getLocationsSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading', false);
                    commit('getLocationsFailure');
                    return Promise.reject(error);
                }
            );
        },

        create({commit}) {
            commit('startLoading');
            return LocationsService.createLocation().then(
                data => {
                    commit('stopLoading');
                    commit('createLocationSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('createLocationFailure');
                    return Promise.reject(error);
                }
            );
        },

        store({commit}, params) {
            commit('startLoading');
            return LocationsService.storeLocation(params).then(
                data => {
                    commit('stopLoading');
                    commit('storeLocationSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('storeLocationFailure');
                    return Promise.reject(error);
                }
            );
        },

        edit({commit}, params) {
            commit('startLoading');
            return LocationsService.editLocation(params).then(
                data => {
                    commit('stopLoading');
                    commit('editLocationSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('editLocationFailure');
                    return Promise.reject(error);
                }
            );
        },

        update({commit}, params) {
            commit('startLoading');
            return LocationsService.updateLocation(params).then(
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
            return LocationsService.destroyLocation(params).then(
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
        getLocationsSuccess(state, locations) {
            state.locations = locations;
        },
        getLocationsFailure(state) {
            state.locations = null;
        },
        editLocationSuccess(state,  data) {
            state.locationData = data.location.data;
        },
        editLocationFailure(state) {
            state.locationData = null;
        },
        createLocationSuccess(state, data) {
            state.categories = data.categories;
        },
        createLocationFailure(state) {
            state.userCreateFormData = null;
        },
        storeLocationSuccess(state, data) {
            state.storedLocationId = data.location_id;
        },
        storeLocationFailure(state, data) {

        },
    },
    getters: {
        locations: (state) => {
            return state.locations;
        },
        locationData: (state) => {
            return state.locationData;
        },
        storedLocationId: (state) => {
            return state.storedLocationId;
        },
        loading: (state) => {
            return state.loading;
        },
    },
}
