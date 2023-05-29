<template>
    <div ref="component-wrapper">
        <div class="modal-backdrop modal-full" id="view-lacation">
            <div class="modal">
                <div class="modal__body">
                    <!--<div class="header__content">
                        <div class="header__center">
                            <div class="title">Название</div>
                        </div>
                    </div>-->
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
                        <div class="owl-carousel img-slider">
                            <div v-for="image in images" class="slider__item toggler" data-target="slider"><img
                                :src="image.image_path">
                            </div>
                        </div>
                    </div>
                    <div class="modal__close relative" @click="onModalClose" onclick="closeModal('view-lacation')"><img
                        src="images/icon/i-close.svg">
                    </div>
                    <div class="location">
                        <div class="location__name">{{ title }}</div>
                        <div class="location__category">{{ category }}</div>
                        <div class="rating">
                            <div class="star-row"><img src="images/icon/star.svg"><img src="images/icon/star.svg"><img
                                src="images/icon/star.svg"><img src="images/icon/star.svg"><img
                                src="images/icon/star0.svg">
                            </div>
                            <span>4.0</span>
                        </div>
                        <div class="location__distance"><img src="images/icon/i-run.svg"><span>30 мин 2,6км</span>
                        </div>
                        <div class="text-title">Описание</div>
                        <div class="text">{{ description }}</div>
                        <div v-if="route != ''" class="text-title">Маршрут</div>
                        <div v-if="route != ''" class="text">{{ route }}</div>
                        <div class="text">
                            <span style="margin-right: 5px" v-for="tag in tags">{{ tag.name }}</span>
                        </div>
                        <a class="btn" :href="ymapsRouteUrl">Построить сюда маршрут от меня</a>
                        <a class="btn" href="#">Редактировать локацию</a>
                        <a class="btn" href="#">Добавить в группу / маршрут</a>
                        <a class="btn" href="#">Закрыть</a>
                        <div class="location__info">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="mask-modal -show"></div>
        </div>

        <SuccessModal
            id="add-location-success-modal"
            title="Запрос отправлен"
            message="Запрос на создание локации успешно отправлен. Мы посмотрим и добавим локацию на карту, если всё хорошо."
        ></SuccessModal>

        <ErrorModal
            id="error-modal"
            :title=errorModalData.title
            :message=errorModalData.message
        ></ErrorModal>

        <InfoModal
            id="add-location-upload-count-limit-warning-modal"
            title="Предупреждение"
            message="Можно загрузить только 6 изображений!"
            secondary="true"
        ></InfoModal>

        <InfoModal
            id="add-location-upload-size-limit-warning-modal"
            title="Предупреждение"
            message="Изображение должно быть не больше 5 мегабайт! Выберите поменьше."
            secondary="true"
        ></InfoModal>

        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>

    </div>
</template>

<script>

import Errors from "../UI/Errors";
import SuccessModal from "../Components/SuccessModal";
import ErrorModal from "../Components/ErrorModal";
import InfoModal from "../Components/InfoModal";
import {NetworkStatusMixin} from "../Mixins/network-status-mixin";
import Loading from 'vue-loading-overlay';
import {ContentLoader} from 'vue-content-loader'
//import 'vue-loading-overlay/dist/css/index.css';

const MAX_IMAGES_UPLOAD = 6;
const MAX_FILE_SIZE = 5242880; // 5 MB, 1048576 * 5

export default {
    name: "ViewLocationModal",
    components: {
        Errors,
        SuccessModal,
        InfoModal,
        ErrorModal,
        Loading,
        ContentLoader
    },
    mixins: [
        NetworkStatusMixin
    ],
    props: {
        categories: Array
    },
    computed: {
        isLoading() {
            return this.$store.getters['locations/loading'] || this.$store.getters['locations_images/loading'];
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
                        this.editItem();
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
            route: '',
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
        editItem() {
            this.errors = null;
            this.$store.dispatch('locations/edit', {id: this.locationId}).then(
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

        fillForm() {
            let location_data = this.$store.getters['locations/locationData'];
            this.title = location_data.title;
            this.description = location_data.description;
            this.category = location_data.category;
            this.route = location_data.route;
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
            this.route = '';
            this.images = [];
            this.tags = [];
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
