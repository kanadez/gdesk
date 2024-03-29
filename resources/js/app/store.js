import {createStore} from "vuex";
import {app} from "./store/app.module";
import {locations} from "./store/locations.module";
import {searchTags} from "./store/search-tags.module";
import {routes} from "./store/routes.module";
import {locationsImages} from "./store/locations-images.module";
import {search} from "./store/search.module";

const store = createStore({
    modules: {
        app,
        locations,
        routes,
        locationsImages,
        search,
        searchTags
    }
});

export default store;
