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
                        @keydown="queryTypingStarted"
                        class="input-text"
                        type="text" placeholder='Например, "инстаместа" или "куда сводить девушку"'
                    >
                </div>
                <div class="label">Или выберите категорию:</div>
                <div class="slider">
                    <div class="owl-carousel tag-slider">
                        <a
                            @click="clearSearchForm"
                            class="tag" href="#"
                        >Популярное</a>
                        <a
                            v-for="category in categories"
                            @click="selectCategory(category.id)"
                            class="tag" href="#"
                        >{{ category.name }}</a>
                    </div>
                </div>

                <div v-if="!isQueryAnswering && isQueryAnswered && resultsNotEmpty" class="label">Найдено по вашему запросу:</div>
                <div v-if="!isQueryAnswering && isQueryAnswered && resultsNotEmpty" class="category">
                    <div class="category__list">
                        <div
                            v-for="route in $store.getters['search/finded']"
                            @click="openRouteOnMap(route.id)"
                            class="category__item"
                        >
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
                        <div
                            v-for="route in routes"
                            @click="openRouteOnMap(route.id)"
                            class="category__item"
                        >
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

        <ErrorModal
            id="error-modal"
            :title=errorModalData.title
            :message=errorModalData.message
        ></ErrorModal>

        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>

import Errors from "../UI/Errors";
import ErrorModal from "../Components/ErrorModal";
import Loading from 'vue-loading-overlay';
import {NetworkStatusMixin} from "../Mixins/network-status-mixin";

export default {
    name: "SearchModal",
    components: {
        Errors,
        ErrorModal,
        Loading
    },
    mixins: [
        NetworkStatusMixin
    ],
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
            return this.$store.getters['search/finded'] != null && Object.keys(this.$store.getters['search/finded']).length > 0;
        },
        resultsEmpty() {
            return this.$store.getters['search/finded'] === null || Object.keys(this.$store.getters['search/finded']).length === 0;
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

            errors: null,

            errorModalData: {
                title: '',
                message: ''
            }
        }
    },
    methods: {

        queryTypingStarted() {
            this.selectedCategory = '';
            this.searchPerformed = false;
        },

        submitQuery() {
            this.params = {
                query: this.searchQueryInputValue
            };

            this.$store.dispatch(`search/findByQuery`, this.params).then(
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

        selectCategory(category_id) {
            this.selectedCategory = category_id;
            this.submitCategory();
        },

        submitCategory(category_id) {
            this.params = {
                category: this.selectedCategory
            };

            this.$store.dispatch(`search/findByCategory`, this.params).then(
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
        },

        openRouteOnMap(route_id) {
            this.$store.commit('search/startLoading');

            ymapsmarkers.addMarkersGroup(route_id)
            .then(response => {
                this.$store.commit('search/stopLoading');
                closeModal('search-location');
            })
            .catch(error => {
                console.log(error)
                this.$store.commit('search/stopLoading');
                this.errors = error;
                this.errorModalData = {title: 'Ошибка', message: 'Что-то пошло не так, попробуйте другой маршрут.'};
                openModal('error-modal');
            }).finally(() => {
                this.$store.commit('search/stopLoading');
            });
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
