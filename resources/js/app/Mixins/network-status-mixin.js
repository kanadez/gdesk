
let NetworkStatusMixin = {
    data: function () {
        return {

        }
    },
    created: function () {
    },
    methods: {
        handleError(network_response) {
            let message = network_response.response.data.message;
            let errors = network_response.response.data.errors;

            if (message !== undefined && errors != undefined && Object.keys(errors).length > 0) {
                return {title: message, message: Object.values(errors).pop().pop()};
            } else if (message !== undefined) {
                return {title: '', message: message};
            } else {
                return {title: '', message: network_response};
            }

        },
    }
};

export {NetworkStatusMixin};
