import LocationsImagesService from '../services/locations-images.service';

const init = {
    loading: false,
    imagesPaths: [],
}

export const locations_images = {
    namespaced: true,
    state: init,
    actions: {

        upload({commit}, data) {
            commit('startLoading');
            return LocationsImagesService.uploadImages(data).then(
                data => {
                    commit('stopLoading');
                    commit('uploadLocationImagesSuccess', data);
                    return Promise.resolve(true);
                },
                error => {
                    commit('stopLoading');
                    commit('uploadLocationImagesFailure');
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
        uploadLocationImagesSuccess(state, data) {
            state.imagesPaths = data.data.stored_images;
        },
        uploadLocationImagesFailure(state) {
            state.imagesPaths = [];
        },
    },
    getters: {
        loading: (state) => {
            return state.loading;
        },
    },
}
