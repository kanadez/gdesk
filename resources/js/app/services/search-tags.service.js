const TAGS_API_URL = '/api/tags';

class SearchTagsService {

    find(params) {
        return axios.post(TAGS_API_URL + '/find', params).then(
            (response) => {
                return Promise.resolve(response.data);
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

}

export default new SearchTagsService();
