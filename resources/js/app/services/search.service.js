const SEARCH_API_URL = '/api/search';

class SearchService {

    find(params) {
        return axios.post(SEARCH_API_URL, params).then(
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
