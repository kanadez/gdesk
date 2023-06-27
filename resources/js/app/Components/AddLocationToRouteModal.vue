<template>
    <div class="modal-backdrop" :id="id">
        <div class="modal">
            <div class="modal__body">
                <div class="header__center">
                    <div class="title"></div>
                    <div :onclick="`closeModal('${id}')`" class="modal__close">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12s5.373 12 12 12 12-5.373 12-12zm-8.293-3.707a1 1 0 0 1 0 1.414L13.414 12l2.293 2.293a1 1 0 0 1-1.414 1.414L12 13.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L10.586 12 8.293 9.707a1 1 0 0 1 1.414-1.414L12 10.586l2.293-2.293a1 1 0 0 1 1.414 0z" fill="currentColor"></path></svg>
                    </div>
                </div>
                <form @submit.prevent="submit">
                    <div class="title">Добавить в маршрут</div>
                    <div class="label">Выберите маршрут для локации "{{ location_title }}":</div>
                    <div v-if="routes.length > 0" class="form-row">
                        <select class="input-text" v-model="route">
                            <option value="">Выбрать из списка</option>
                            <option v-for="route in routes" :value="route.id">{{ route.name }}</option>
                        </select>
                    </div>
                    <button class="btn secondary btn-primary" @click="submit" type="button">Добавить</button>
                </form>
            </div>
        </div>
        <div class="mask-modal -show"></div>
    </div>
</template>

<script>

import Errors from "../UI/Errors";

export default {
    name: "AddLocationToRouteModal",
    components: {Errors},
    props: {
        id: String,
        location_title: String,
    },
    data() {
        return {
            route: '',
        }
    },
    computed: {
        routes() {
            return this.$store.getters['routes/routes'];
        }
    },
    methods: {
        submit(){
            this.$emit('submit', this.route);
            this.route = '';
        }
    },

    created() {

    }
}
</script>

<style scoped>

</style>
