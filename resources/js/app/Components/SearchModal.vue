<template>
    <div class="modal-backdrop modal-full" id="search-location">
        <div class="modal">
            <div class="modal__body">
                <div class="modal__header">
                    <div :onclick="`closeModal('search-location')`" class="modal__close">
                        <img src="images/icon/i-close.svg">
                    </div>
                </div>
                <div class="label">Введите поисковый запрос:</div>
                <div class="form-row">
                    <input
                        v-on:keydown.enter.prevent="submitQuery"
                        v-model="searchQueryInputValue"
                        @keyup="queryTypingStarted"
                        class="input-text"
                        type="text" placeholder='Например, "инстаместа" или "куда сводить девушку"'
                    >
                </div>
                <div class="label">Или выберите категорию:</div>
                <div class="slider">
                    <div class="owl-carousel tag-slider">
                        <a v-for="category in categories" class="tag" href="#">{{ category.name }}</a>
                    </div>
                </div>

                <div v-if="!isQueryAnswering && isQueryAnswered && resultsNotEmpty" class="label">Найдено по вашему запросу:</div>
                <div v-if="!isQueryAnswering && isQueryAnswered && resultsNotEmpty" class="category">
                    <div class="category__list">
                        <div v-for="route in $store.getters['search/finded']" class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">{{ route.name }}</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!isQueryAnswering && isQueryAnswered && resultsEmpty" class="label no-search-results">
                    По вашему запросу ничего не найдено!
                    <br><a href="javascript:void(0)" @click="clearSearchForm">Показать самые популярные маршруты</a>
                </div>

                <div v-if="!isQueryAnswering && !isQueryAnswered" class="label">Самые популярные маршруты:</div>
                <div v-if="!isQueryAnswering && !isQueryAnswered" class="category">
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

        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>

import Errors from "../UI/Errors";
import Loading from 'vue-loading-overlay';

export default {
    name: "SearchModal",
    components: {
        Errors,
        Loading
    },
    props: {
        categories: Array,
        routes: Array
    },
    computed: {
        isQueryAnswering() {
            return (this.searchQueryInputValue !== '' || this.selectedCategory !== '') && this.searchPerformed === false;
        },
        isQueryAnswered() {
            return (this.searchQueryInputValue !== '' || this.selectedCategory !== '') && this.searchPerformed === true;
        },
        resultsNotEmpty() {
            return this.$store.getters['search/finded'] != null && this.$store.getters['search/finded'].length > 0;
        },
        resultsEmpty() {
            return this.$store.getters['search/finded'] == null || this.$store.getters['search/finded'].length === 0;
        },
        isLoading() {
            return this.$store.getters['search/loading'];
        }
    },
    data() {
        return {
            isSending: false,

            searchQueryInputValue: '',
            selectedCategory: '',
            searchPerformed: false,

            params: {
                query: '',
                category: ''
            },
            errors: null,
        }
    },
    methods: {

        queryTypingStarted() {
            this.searchPerformed = false;
        },

        submitQuery() {
            this.params.query = this.searchQueryInputValue;
            this.params.category = this.selectedCategory;

            this.$store.dispatch(`search/find`, this.params).then(
                success => {
                    this.searchPerformed = true;
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

        clearSearchForm() {
            this.searchQueryInputValue = '';
            this.selectedCategory = '';
            this.searchPerformed = false;
        }

    },

    created() {

    }
}
</script>

<style scoped>

.no-search-results {
    text-align: center;
    padding: 20px;
}

</style>
