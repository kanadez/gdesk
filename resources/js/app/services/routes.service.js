const ROUTES_API_URL = '/api/routes';

class RoutesService {

    getRoutes(params) {
        return axios.post(ROUTES_API_URL, params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    createRoute() {
        return axios.get(ROUTES_API_URL + '/create').then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    storeRoute(params) {
        return axios.post(ROUTES_API_URL + '/store', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    editRoute(params) {
        return axios.get(ROUTES_API_URL + `/${params.id}/edit`, {}).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    updateRoute(params) {
        return axios.post(ROUTES_API_URL + `/${params.id}/update`, params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    deleteRoute(params) {
        return axios.post(ROUTES_API_URL + '/delete', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }


}

export default new RoutesService();
