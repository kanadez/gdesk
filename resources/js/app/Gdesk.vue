<template>
    <LayoutApp v-if="isReady" />
</template>

<script>
import LayoutApp from "./Layouts/LayoutApp";

export default {
    name: "Gdesk",
    components: {LayoutApp},
    mixins: [

    ],
    computed: {

    },
    data() {
        return {

        }
    },
    async created() {

    }
}
</script>

<style scoped>

</style>
