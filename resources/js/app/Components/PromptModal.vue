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
                <form @submit.prevent="action">
                    <div class="title">{{ title }}</div>
                    <div class="label">{{ message }}:</div>
                    <div class="form-row">
                        <input class="input-text" type="text" placeholder="" v-model="prompt_input">
                    </div>
                    <button v-if="secondary" class="btn secondary btn-primary" @click="action" type="button">{{ action_text }}</button>
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
            prompt_input: '',
        }
    },
    methods: {
        action(){
            this.$emit('action', this.prompt_input);
            this.prompt_input = '';
        }
    },

    created() {

    }
}
</script>

<style scoped>

</style>
