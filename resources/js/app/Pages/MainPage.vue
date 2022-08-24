<template>
    <div class="">

    </div>
</template>

<script>
import Input from "../UI/Input";
import Button from "../UI/Button";
import Errors from "../UI/Errors";

export default {
    name: "MainPage",
    components: {Errors, Button, Input},
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

    }
}
</script>

<style scoped>

</style>
