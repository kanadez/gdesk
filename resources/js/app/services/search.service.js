const SEARCH_API_URL = '/api/search';

class SearchService {

    findByQuery(params) {
        return axios.post(SEARCH_API_URL + '/query', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    findByCategory(params) {
        return axios.post(SEARCH_API_URL + '/category', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

}

export default new SearchService();
