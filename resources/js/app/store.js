import {createStore} from "vuex";
import {locations} from "./store/locations.module";
import {routes} from "./store/routes.module";
import {locations_images} from "./store/locations-images.module";

const store = createStore({
    modules: {
        locations,
        routes,
        locations_images
    }
});

export default store;
