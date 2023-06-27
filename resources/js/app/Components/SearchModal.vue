<template>
    <div class="modal-backdrop modal-full" id="search-location">
        <div class="modal">
            <div class="modal__body">
                <div class="modal__header">
                    <div :onclick="`closeModal('search-location')`" class="modal__close">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12s5.373 12 12 12 12-5.373 12-12zm-8.293-3.707a1 1 0 0 1 0 1.414L13.414 12l2.293 2.293a1 1 0 0 1-1.414 1.414L12 13.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L10.586 12 8.293 9.707a1 1 0 0 1 1.414-1.414L12 10.586l2.293-2.293a1 1 0 0 1 1.414 0z" fill="currentColor"></path></svg>
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
                <!--<div class="label">Или выберите категорию:</div>-->
                <div class="slider categories">
                    <div class="owl-carousel tag-slider">
                        <a
                            @click="clearSearchForm"
                            :class="`tag ${categoryTagClass('')}`" href="#"
                        >Популярное</a>
                        <a
                            v-for="category in categories"
                            @click="selectCategory(category.id)"
                            :class="`tag ${categoryTagClass(category.id)}`" href="#"
                        >{{ category.name }}</a>
                    </div>
                </div>

                <div v-if="foundSomething" class="label">Найдено по вашему запросу:</div>
                <div v-if="foundSomething" class="category">
                    <div class="category__list">
                        <div
                            v-for="route in foundRoutes"
                            @click="openRouteOnMap(route.id)"
                            class="category__item"
                        >
                            <div class="category__img">
                                <img v-if="(route.image_path !== '')" :src="route.image_path" />
                                <img v-else class="dummy" src="images/route.png" />
                            </div>
                            <div class="category__text">
                                <div class="category__title">{{ route.name }}</div>
                                <span>{{ route.description }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="nothingFound" class="label no-search-results">
                    По вашему запросу ничего не найдено!
                    <br><a href="javascript:void(0)" @click="clearSearchForm">Показать самые популярные маршруты</a>
                </div>

                <div v-if="nothingWasSearched" class="label">Самые популярные маршруты:</div>
                <div v-if="nothingWasSearched" class="category">
                    <div class="category__list">
                        <div
                            v-for="route in popularRoutes"
                            @click="openRouteOnMap(route.id)"
                            class="category__item"
                        >
                            <div class="category__img">
                                <img v-if="(route.image_path !== '')" :src="route.image_path" />
                                <img v-else class="dummy" src="images/route.png" />
                            </div>
                            <div class="category__text">
                                <div class="category__title">{{ route.name }}</div>
                                <span>{{ route.description }}</span>
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

const MAX_ROUTE_DESC_LEN = 150;

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
        foundSomething() {
            return !this.isQueryAnswering && this.isQueryAnswered && this.resultsNotEmpty;
        },
        nothingFound() {
            return !this.isQueryAnswering && this.isQueryAnswered && this.resultsEmpty;
        },
        nothingWasSearched() {
            return !this.isQueryAnswering && !this.isQueryAnswered;
        },
        foundRoutes() {
            return this.$store.getters['search/finded'].routes.map(route => {
                let route_locations = this.$store.getters['search/finded'].routes_locations
                    .filter(location => location.routes
                        .find(location_route => location_route.id === route.id) !== undefined);
                let locations_titles = route_locations.map(location => {
                    return location.title;
                });

                let reduceRouteDescription = function(description) {
                    if (description.length > MAX_ROUTE_DESC_LEN) return `${description.substr(0, MAX_ROUTE_DESC_LEN)} ...`;
                    else return description;
                };

                return {
                    id: route.id,
                    image_path: route.image_path,
                    name: route.name,
                    description: reduceRouteDescription(locations_titles.join(', '))
                };
            });
        },
        popularRoutes() {
            return this.routes.map(route => {
                let locations_titles = route.locations.map(location => {
                    return location.title;
                });

                let reduceRouteDescription = function(description) {
                    if (description.length > MAX_ROUTE_DESC_LEN) return `${description.substr(0, MAX_ROUTE_DESC_LEN)} ...`;
                    else return description;
                };

                return {
                    id: route.id,
                    image_path: route.image_path,
                    name: route.name,
                    description: reduceRouteDescription(locations_titles.join(', '))
                };
            });
        },

        isQueryAnswering() {
            return (this.searchQueryInputValue !== '' || this.selectedCategory !== '') && this.searchPerformed === false;
        },
        isQueryAnswered() {
            return (this.searchQueryInputValue !== '' || this.selectedCategory !== '') && this.searchPerformed === true;
        },
        resultsNotEmpty() {
            return this.$store.getters['search/finded'].routes != null && Object.keys(this.$store.getters['search/finded'].routes).length > 0;
        },
        resultsEmpty() {
            return this.$store.getters['search/finded'].routes === null || Object.keys(this.$store.getters['search/finded'].routes).length === 0;
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

        categoryTagClass(category_id) {
            if (this.nothingWasSearched && !this.nothingFound && category_id === '') {
                return 'active';
            } else if (category_id !== '') {
                return this.selectedCategory === category_id ? 'active' : '';
            }
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
            this.$store.commit('app/collapseHeader');

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
