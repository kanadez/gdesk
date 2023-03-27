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
                    <div class="label">{{ message }}:</div>
                    <div class="form-row">
                        <input class="input-text" type="text" placeholder="" v-model="route_data.name">
                    </div>
                    <button v-if="secondary" class="btn secondary" @click="action" type="button">{{ action_text }}</button>
                    <button v-else class="btn" @click="action" type="button">{{ action_text }}</button>
                </form>
            </div>
        </div>
        <div class="mask-modal -show"></div>
    </div>
</template>

<script>

import Errors from "../UI/Errors";

export default {
    name: "PromptModal",
    components: {Errors},
    props: {
        id: String,
        title: String,
        message: String,
        action_text: String,
        secondary: Boolean,
    },
    computed: {},
    data() {
        return {
            route_data: {
                name: '',
            }
        }
    },
    methods: {
        action(){
            this.$emit('action', this.route_data);
        }
    },

    created() {

    }
}
</script>

<style scoped>

</style>
