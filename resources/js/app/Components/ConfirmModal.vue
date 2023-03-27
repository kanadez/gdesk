<template>
    <div class="modal-backdrop modal-bottom" id="search">
        <div class="modal">
            <div class="modal__body">
                <form>
                    <div class="form-row">
                        <input class="input-text" type="text">
                        <button class="btn__icon" type="submit"><img src="images/icon/i-search.svg">
                        </button>
                    </div>
                    <div class="row-tags">
                        <div class="tag">тег 1</div>
                        <div class="tag">тег 2</div>
                        <div class="tag">тег 3</div>
                        <div class="tag">тег 1 тег 3 тег 3</div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mask-modal -show"></div>
    </div>
</template>

<script>

import Errors from "../UI/Errors";

export default {
    name: "SearchModal",
    components: {Errors},
    props: {

    },
    computed: {},
    data() {
        return {
            isSending: false,
            params: {

            },
            errors: null,
        }
    },
    methods: {


    },

    created() {

    }
}
</script>

<style scoped>

</style>
