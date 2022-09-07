window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

import Gdesk from "./app/Gdesk";
import {createApp} from "vue";
import router from "./app/router";
import store from "./app/store";

const app = createApp(Gdesk)
    .use(router)
    .use(store).mount('#app')
window.vueapp = app;
