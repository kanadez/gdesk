<template>
    <div class="modal-backdrop" :id="id">
        <div class="modal">
            <div class="modal__body">
                <div class="header__center">
                    <div class="title"></div>
                    <div :onclick="`closeModal('${id}')`" class="modal__close"><img src="images/icon/i-close.svg">
                    </div>
                </div>
                <form>
                    <div class="text-title">{{ title }}</div>
                    <div class="label">{{ message }}</div>
                    <button v-if="secondary" class="btn secondary" :onclick="`closeModal('${id}')`" type="button">ОК</button>
                    <button v-else class="btn" :onclick="`closeModal('${id}')`" type="button">ОК</button>
                </form>
            </div>
        </div>
        <div class="mask-modal -show"></div>
    </div>
</template>

<script>

import Errors from "../UI/Errors";

export default {
    name: "InfoModal",
    components: {Errors},
    props: {
        id: String,
        title: String,
        message: String,
        secondary: Boolean
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
