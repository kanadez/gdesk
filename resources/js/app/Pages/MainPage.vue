<template>
    <div class="">
        <YMap></YMap>
    </div>
</template>

<script>

import Input from "../UI/Input";
import Button from "../UI/Button";
import Errors from "../UI/Errors";
import YMap from "../Components/YMap";

export default {
    name: "MainPage",
    components: {Errors, Button, Input, YMap},
    mixins: [],
    computed: {

    },
    created() {

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
