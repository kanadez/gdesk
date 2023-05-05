import {createStore} from "vuex";
import {locations} from "./store/locations.module";
import {routes} from "./store/routes.module";
import {locations_images} from "./store/locations-images.module";
import {search} from "./store/search.module";

const store = createStore({
    modules: {
        locations,
        routes,
        locations_images,
        search
    }
});

export default store;
