<template>
    <div class="modal-backdrop" :id="id">
        <div class="modal">
            <div class="modal__body">
                <div class="header__center">
                    <div class="title">Ошибка!</div>
                </div>
                <form>
                    <div class="text-title">{{ title }}</div>
                    <div class="label">{{ message }}</div>
                    <button class="btn" :onclick="`closeModal('${id}')`" type="button">ОК</button>
                </form>
            </div>
        </div>
        <div class="mask-modal -show"></div>
    </div>
</template>

<script>

import Errors from "../UI/Errors";

export default {
    name: "ErrorModal",
    components: {Errors},
    props: {
        id: String,
        title: String,
        message: String
    },
    computed: {},
    data() {
        return {

        }
    },
    methods: {},

    created() {

    }
}
</script>

<style scoped>

</style>
