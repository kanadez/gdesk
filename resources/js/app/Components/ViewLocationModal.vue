<template>
    <div ref="component-wrapper">
        <div class="modal-backdrop modal-full" id="view-lacation">
            <div class="modal">
                <ContentLoader
                    v-if="isLoading"
                    viewBox="0 0 400 240"
                    :speed="2"
                    primaryColor="#f3f3f3"
                    secondaryColor="#bfbfbf"
                >
                    <rect x="2" y="9" rx="2" ry="2" width="468" height="229"/>
                </ContentLoader>
                <div v-else class="slider">
                    <div class="owl-carousel img-slider location-header-slider">
                        <div v-for="image in images" class="slider__item toggler" data-target="slider"><img
                            :src="image.image_path">
                        </div>
                    </div>
                </div>
                <div class="modal__body">
                    <!--<div class="header__content">
                        <div class="header__center">
                            <div class="title">Название</div>
                        </div>
                    </div>-->
                    <div class="modal__close relative" @click="onModalClose" onclick="closeModal('view-lacation')">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12s5.373 12 12 12 12-5.373 12-12zm-8.293-3.707a1 1 0 0 1 0 1.414L13.414 12l2.293 2.293a1 1 0 0 1-1.414 1.414L12 13.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L10.586 12 8.293 9.707a1 1 0 0 1 1.414-1.414L12 10.586l2.293-2.293a1 1 0 0 1 1.414 0z" fill="currentColor"></path></svg>
                    </div>
                    <div class="location">
                        <div class="location__name">{{ title }}</div>
                        <div class="location__category">{{ category }}</div>
                        <!--<div class="rating">
                            <div class="star-row"><img src="images/icon/star.svg"><img src="images/icon/star.svg"><img
                                src="images/icon/star.svg"><img src="images/icon/star.svg"><img
                                src="images/icon/star0.svg">
                            </div>
                            <span>4.0</span>
                        </div>-->
                        <div class="location__distance">
                            <!--<img src="images/icon/i-run.svg">-->
                            <span>2,6 км (30 мин. пешком )</span>
                        </div>
                        <div class="text-title">
                            Описание
                            <span v-if="!isTextPlaying">(<a href="javascript:void(0)" @click="playText(description)">Озвучить голосом</a>)</span>
                            <span v-if="isTextPlaying">(<a href="javascript:void(0)" @click="pauseText">Стоп</a>)</span>
                        </div>
                        <div class="text">{{ description }}</div>
                        <div v-if="routes.length > 0" class="text-title">Маршруты</div>
                        <div v-if="routes.length > 0" class="text">
                            <span style="margin-right: 5px; position: relative" v-for="route in routes">{{ route.name }}</span>
                        </div>
                        <div class="text hashtags" style="word-wrap: break-word">
                            <span style="margin-right: 5px; position: relative" v-for="tag in tags">{{ tag.name }}</span>
                        </div>
                        <a class="btn btn-primary" :href="ymapsRouteUrl">Построить сюда маршрут от меня</a>
                        <a class="btn" href="#" @click="openEditLocationModal">Редактировать локацию</a>
                        <a class="btn" onclick="openModal('add-location-to-route-modal')" href="#">Добавить в маршрут</a>
                        <a class="btn" onclick="closeModal('view-lacation')" href="#">Закрыть</a>
                        <!--<div class="location__info">
                            <div class="rating-info"><span>4.0</span>
                                <div>
                                    <div class="star-row"><img src="images/icon/star.svg"><img
                                        src="images/icon/star.svg"><img src="images/icon/star.svg"><img
                                        src="images/icon/star.svg"><img src="images/icon/star0.svg">
                                    </div>
                                    <small>10 оценок</small>
                                </div>
                            </div>
                            <div class="estimate"><small>Были здесь? поставь оценку</small>
                                <div class="estimate__star"><a href="#"><img src="images/icon/star0.svg"></a><a
                                    href="#"><img src="images/icon/star0.svg"></a><a href="#"><img
                                    src="images/icon/star0.svg"></a><a href="#"><img src="images/icon/star0.svg"></a><a
                                    href="#"><img src="images/icon/star0.svg"></a></div>
                            </div>
                        </div>
                        <div class="btn toggler" data-target="comment-lacation"><img src="images/icon/i-comment.svg">
                            Написать отзыв
                        </div>
                        <div class="btn toggler" data-target="delete-lacation"><img src="images/icon/i-trash.svg">
                            Удалить локацию
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="mask-modal -show"></div>
        </div>

        <AddLocationToRouteModal
            id="add-location-to-route-modal"
            :location_title=title
            @submit="addToRoute"
        ></AddLocationToRouteModal>

        <ErrorModal
            id="add-error-modal"
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
import AddLocationToRouteModal from "../Components/AddLocationToRouteModal";
import SuccessModal from "../Components/SuccessModal";
import ErrorModal from "../Components/ErrorModal";
import InfoModal from "../Components/InfoModal";
import {NetworkStatusMixin} from "../Mixins/network-status-mixin";
import {TextToSpeechMixin} from "../Mixins/text-to-speech-mixin";
import Loading from 'vue-loading-overlay';
import {ContentLoader} from 'vue-content-loader'
//import 'vue-loading-overlay/dist/css/index.css';

const MAX_IMAGES_UPLOAD = 6;
const MAX_FILE_SIZE = 5242880; // 5 MB, 1048576 * 5

export default {
    name: "ViewLocationModal",
    components: {
        Errors,
        AddLocationToRouteModal,
        SuccessModal,
        InfoModal,
        ErrorModal,
        Loading,
        ContentLoader
    },
    mixins: [
        NetworkStatusMixin,
        TextToSpeechMixin
    ],
    props: {
        categories: Array
    },
    computed: {
        isLoading() {
            return this.$store.getters['locations/loading'] || this.$store.getters['locationsImages/loading'];
        },
    },
    mounted() {
        const observer = new MutationObserver((mutationsList, observer) => {
            mutationsList.forEach(record => {
                // In each iteration an individual mutation can be accessed.
                //console.log(record);

                // In this case if the type of mutation is of attribute run the following block.
                // A mutation could have several types.
                if (record.type === 'attributes' && record.attributeName === 'class') {
                    const changedAttrName = record.attributeName;
                    const newValue = record.target.getAttribute(changedAttrName);

                    if (newValue.includes('_active')) {
                        this.clearForm();
                        this.locationId = window.current_opened_location_id;
                        this.showItem();
                    } else {
                        this.locationId = window.current_opened_location_id = null;
                    }
                }
            });
        });

        // A list to be used as a target below.
        const list = document.querySelector('#view-lacation');

        // This is the code that tells MutationObserver what to keep an eye on.
        // In this case it is the list targeted earlier and
        // it will only observe changes to attributes of the elements such as class, id or any other attribute.
        observer.observe(list, {
            attributes: true
        });
    },
    data() {
        return {
            isSending: false,

            locationId: null,
            title: '',
            description: '',
            category: '',
            routes: [],
            images: [],
            tags: [],
            ymapsRouteUrl: '',

            errors: null,
            errorModalData: {
                title: '',
                message: ''
            }
        }
    },
    methods: {
        showItem() {
            this.errors = null;
            this.$store.dispatch('locations/show', {id: this.locationId}).then(
                success => {
                    this.fillForm();
                },
                error => {
                    this.errors = error.response.data;
                    this.$notify({
                        title: "Error",
                        type: 'error',
                        text: this.handleError(error),
                    });
                    console.log(this.errors);
                }
            ).catch(error => {

            });
        },

        openEditLocationModal() {
            //closeModal('view-lacation');
            openModal('edit-lacation');
        },

        fillForm() {
            let location_data = this.$store.getters['locations/locationShowData'];
            this.title = location_data.title;
            this.description = location_data.description;
            this.category = location_data.category;
            this.routes = location_data.routes;
            this.images = location_data.images;
            this.tags = location_data.tags;
            ymapsrouting.buildRouteUrl([location_data.ymaps_marker.lat,location_data.ymaps_marker.lng])
            .then(response => {
                this.ymapsRouteUrl = response;
            })
            .catch(error => {
                console.log(error)
                this.errors = error;
                this.errorModalData = {title: 'Ошибка', message: 'Что-то пошло не так, попробуйте другую локацию.'};
                openModal('error-modal');
            });
        },

        clearForm() {
            this.title = '';
            this.description = '';
            this.category = '';
            this.routes = '';
            this.images = [];
            this.tags = [];
        },

        addToRoute(route_id) {
            let data = {
                location_id: this.locationId,
                route_id: route_id
            };

            this.$store.dispatch(`locations/addToRoute`, data).then(
                success => {
                    this.clearForm();
                    this.showItem();
                    closeModal('add-location-to-route-modal');
                },
                error => {
                    this.errors = error; // TODO обработать через миксим
                    this.errorModalData = this.handleError(error);
                    openModal('add-error-modal');
                }
            ).catch(error => {
                this.errors = error;
                this.errorModalData = this.handleError(error);
                openModal('add-error-modal');
            }).finally(() => {
                this.isSending = false;
            });
        },

        onModalClose() {

        }

    },

    created() {

    },

    updated: function () {
        this.$nextTick(function () {
            initSliders()
        })
    }
}
</script>

<style scoped>

</style>
