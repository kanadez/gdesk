<template>
    <div id="map"></div>
</template>

<script>

export default {
    name: "YMap",
    components: {},
    data() {
        return {

        }
    },
    props: {

    },
    mounted() {
        this.$nextTick(function () {
            this.initYMap();
        })
    },
    computed: {

    },
    methods: {

        initYMap() {
            ymapsjq.init();
            window.testJQuery = this.testJQuery; // вызывать прямо процедурно из любого места testJQuery()
        },

        testJQuery() {
            alert('jquery is ok')
        }
    }
}
</script>

<style scoped>

#map {
    width: 100%; height: 90vh; padding: 0; margin: 0;
}

</style>
