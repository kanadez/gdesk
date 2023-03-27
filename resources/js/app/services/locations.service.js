const LOCATIONS_API_URL = '/api/locations';

class LocationsService {

    getLocations(params) {
        return axios.post(LOCATIONS_API_URL, params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    createLocation() {
        return axios.get(LOCATIONS_API_URL + '/create').then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    storeLocation(params) {
        return axios.post(LOCATIONS_API_URL + '/store', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    editLocation(params) {
        return axios.get(LOCATIONS_API_URL + `/${params.id}/edit`, {}).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    updateLocation(params) {
        return axios.post(LOCATIONS_API_URL + `/${params.id}/update`, params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    deleteLocation(params) {
        return axios.post(LOCATIONS_API_URL + '/delete', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }


}

export default new LocationsService();
