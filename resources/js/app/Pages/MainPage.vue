<template>

    <div class="container">
        <div class="main">
            <YMap></YMap>
            <div class="circle-drop">
                <a class="circle-drop__link js-circle-drop" href="javascript:;"><img src="images/icon/i-add.svg"></a>
                <div class="circle-drop__menu">
                    <a href="#"><img src="images/icon/i-route.svg"></a>
                    <a href="#"><img src="images/icon/i-markers.svg"></a>
                    <a class="toggler" href="javascript:;" data-target="add-lacation"><img src="images/icon/i-marker.svg"></a>
                </div>
            </div>
        </div>

        <SearchModal></SearchModal>

        <AddLocationModal
            :categories="this.$store.state.locations.categories"
        ></AddLocationModal>

        <ViewLocationModal
            :categories="this.$store.state.locations.categories"
        ></ViewLocationModal>

        <loading v-model:active="loading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>

import Input from "../UI/Input";
import Button from "../UI/Button";
import Errors from "../UI/Errors";
import YMap from "../Components/YMap";
import SearchModal from "../Components/SearchModal";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

import AddLocationModal from "../Components/AddLocationModal";
import ViewLocationModal from "../Components/ViewLocationModal";

export default {
    name: "MainPage",
    components: {
        ViewLocationModal,
        Errors,
        Button,
        Input,
        YMap,
        SearchModal,
        AddLocationModal,
        Loading
    },
    mixins: [],
    computed: {

    },
    created() {
        this.$store.dispatch("locations/create", {});
    },
    data() {
        return {
            loading: false,
            errors: null,
        }
    },
    methods: {

    },
    mounted() {
        this.$nextTick(function () {

        })
    }
}
</script>

<style scoped>

</style>
