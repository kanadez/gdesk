<template>
    <div class="modal-backdrop modal-full" id="search-location">
        <div class="modal">
            <div class="modal__body">
                <div class="modal__header">
                    <div :onclick="`closeModal('search-location')`" class="modal__close">
                        <img src="images/icon/i-close.svg">
                    </div>
                </div>
                <div class="label">Введите поисковый запрос</div>
                <div class="form-row">
                    <input v-on:keydown.enter.prevent="submitQuery" v-model="searchQueryInputValue" class="input-text"
                           type="text" placeholder='Например, "инстаместа" или "куда сводить девушку"'>
                </div>
                <div class="label">Или выберите категорию:</div>
                <div class="slider">
                    <div class="owl-carousel tag-slider">
                        <a v-for="category in categories" class="tag" href="#">{{ category.name }}</a>
                    </div>
                </div>
                <div class="label">Самые популярные маршруты:</div>
                <div class="category">
                    <div class="category__list">
                        <div v-for="route in routes" class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">{{ route.name }}</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                    </div>
                </div>
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
        categories: Array,
        routes: Array
    },
    computed: {},
    data() {
        return {
            isSending: false,

            searchQueryInputValue: '',
            selectedCategory: '',

            params: {
                query: '',
                category: ''
            },
            errors: null,
        }
    },
    methods: {

        submitQuery() {
            this.params.query = this.searchQueryInputValue;
            this.params.category = this.selectedCategory;

            this.$store.dispatch(`search/find`, this.params).then(
                success => {

                },
                error => {
                    this.errors = error; // TODO обработать через миксим
                    this.errorModalData = this.handleError(error);
                    openModal('error-modal');
                }
            ).catch(error => {
                this.errors = error;
                this.errorModalData = this.handleError(error);
                openModal('error-modal');
            }).finally(() => {
                this.isSending = false;
            });
        },

    },

    created() {

    }
}
</script>

<style scoped>

</style>
