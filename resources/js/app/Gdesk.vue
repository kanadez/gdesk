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
            isReady: true // TODO здесь сделать условие подготовки
        }
    },
    async created() {

    }
}
</script>

<style scoped>

</style>
