const LOCATIONS_API_URL = '/api/locations';

class LocationsImagesService {

    uploadImages(data) {
        let config = {
            header : {
                'Content-Type' : 'multipart/form-data'
            }
        };

        return axios.post(LOCATIONS_API_URL + '/images/upload', data, config).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }




}

export default new LocationsImagesService();
