<template>
    <div>

        <div class="modal-backdrop modal-full" id="add-lacation">
            <div class="modal">
                <div class="modal__body">
                    <div class="header__center">
                        <div class="title">Создать локацию</div>
                        <div class="modal__close" onclick="closeModal('add-lacation')"><img src="images/icon/i-close.svg">
                        </div>
                    </div>
                    <form onsubmit="return false">
                        <div class="form-row">
                            <input class="input-text" v-model="title" maxlength="255" type="text"
                                   v-on:keydown.enter.prevent="$refs.category_select.focus()" placeholder="Название локации">
                        </div>
                        <div class="form-row">
                            <select ref="category_select" v-model="category" class="input-text">
                                <option value="">Выбрать категорию</option>
                                <option v-for="(category, index) in categories" :value="category.id">{{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-row">
                            <textarea v-model="description" class="input-text" maxlength="1000"
                                      placeholder="Описание"></textarea>
                        </div>
                        <div class="add-photo">
                            <div class="add-photo__thumb">
                                <div v-for="image in previewImages" class="add-photo__item"><img :src="image"></div>
                            </div>
                            <div class="input-file">
                                <input id="input-file" type="file" accept=".jpg,.jpeg,.png," multiple
                                       @change="uploadImages($event)">
                                <label for="input-file">Загрузить фото</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <input v-on:keydown.enter.prevent="addTag" v-on:keyup="searchTags" v-model="addTagInputValue"
                                   class="input-text" ref="add_tag_input_value"
                                   type="text" placeholder="Хештеги (введите по одному, нажимая Enter)">
                        </div>
                        <div class="form-row" v-for="tag_finded in tagsFinded" :key="tag_finded">
                            <p style="padding: 5px"><a href="javascript:void(0)" @click="insertTagSuggestion(tag_finded.tag)">{{ tag_finded.tag }}</a></p>
                        </div>
                        <div class="row-tags">
                            <div class="tag" v-for="tag in tags">{{ tag }}</div>
                        </div>
                        <div v-if="routes.length > 0" class="form-row">
                            <select class="input-text" v-model="route">
                                <option value="">Выберите маршрут для локации</option>
                                <option v-for="route in routes" :value="route.id">{{ route.name }}</option>
                            </select>
                        </div>
                        <button v-if="routes.length === 0" class="btn" onclick="openModal('create-route-and-add-location-modal')">Добавить в маршрут (не обязательно)</button>
                        <button v-if="routes.length > 0 && route === ''" class="btn" onclick="openModal('create-route-and-add-location-modal')">Создать новый маршрут</button>
                        <button class="btn" type="button" @click="submit">Создать локацию</button>
                    </form>
                </div>
            </div>
            <div class="mask-modal -show"></div>
        </div>

        <SuccessModal
            id="add-location-success-modal"
            title="Запрос отправлен"
            message="Запрос на создание локации успешно отправлен. Мы посмотрим и добавим локацию на карту, если всё хорошо."
        ></SuccessModal>

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

        <InfoModal
            id="add-location-upload-size-limit-warning-modal"
            title="Предупреждение"
            message="Изображение должно быть не больше 5 мегабайт! Выберите поменьше."
            secondary="true"
        ></InfoModal>

        <PromptModal
            id="create-route-and-add-location-modal"
            title="Создать маршрут"
            message='Введите название нового маршрута и нажмите "Добавить локацию"'
            action_text="Добавить локацию в маршрут"
            @action="submitCreatedRoute"
            secondary="true"
        ></PromptModal>

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
import SuccessModal from "../Components/SuccessModal";
import ErrorModal from "../Components/ErrorModal";
import InfoModal from "../Components/InfoModal";
import PromptModal from "../Components/PromptModal";
import {NetworkStatusMixin} from "../Mixins/network-status-mixin";
import Loading from 'vue-loading-overlay';
import {extractIdentifiers} from "@vue/compiler-sfc";
//import 'vue-loading-overlay/dist/css/index.css';

const MAX_IMAGES_UPLOAD = 6;
const MAX_FILE_SIZE = 5242880; // 5 MB, 1048576 * 5

export default {
    name: "AddLocationModal",
    components: {
        Errors,
        SuccessModal,
        InfoModal,
        PromptModal,
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
        isLoading() {
            return this.$store.getters['locations/loading']
                || this.$store.getters['locationsImages/loading']
                || this.$store.getters['routes/loading'];
        },
        areTagsFinded() {
            return this.$store.getters['searchTags/finded'].length > 0;
        },
        tagsFinded() {
            let tags_to_show_as_finded = [];

            this.$store.getters['searchTags/finded'].forEach(finded_tag => {
                let existing_tag = this.tags.find(selected_tag => selected_tag === finded_tag.tag);
                console.log(finded_tag, existing_tag)
                if (existing_tag === undefined) {
                    tags_to_show_as_finded.push(finded_tag);
                }
            });

            return tags_to_show_as_finded;
        }
    },
    data() {
        return {
            isSending: false,

            title: '',
            description: '',
            category: '',

            previewImages: [],
            imagesToUpload: [],
            imagesToUploadPaths: [],

            addTagInputValue: '',
            tags: [],

            createdRoute: '',
            route: '',

            params: {
                title: '',
                description: '',
                category: null,
                images: [],
                route: '',
                tags: [],
            },
            errors: null,

            tagSearchQuery: '',

            errorModalData: {
                title: '',
                message: ''
            }
        }
    },
    methods: {
        uploadImages(event) {
            if (this.previewImages.length >= MAX_IMAGES_UPLOAD) {
                openModal('add-location-upload-count-limit-warning-modal');

                return false;
            }

            for (let i = 0; i < event.target.files.length; i++) {
                if (this.imagesToUpload.length >= MAX_IMAGES_UPLOAD) {
                    openModal('add-location-upload-count-limit-warning-modal');

                    continue;
                }

                const image = event.target.files[i];

                if (image.size > MAX_FILE_SIZE) {
                    openModal('add-location-upload-size-limit-warning-modal');

                    continue;
                }

                this.imagesToUpload.push(image);

                const reader = new FileReader();
                reader.readAsDataURL(image);
                reader.onload = event => {
                    this.previewImages.push(event.target.result);
                };
            }

            //if (this.imagesToUpload.length === 0) return false;
            if (this.isSending) return;
            this.errors = null;
            this.isSending = true;
            let data = new FormData();

            for (let i = 0; i < this.imagesToUpload.length; i++) {
                data.append(`file${i}`, this.imagesToUpload[i]);
            }

            this.$store.dispatch(`locationsImages/upload`, data).then(
                success => {
                    this.imagesToUploadPaths = this.imagesToUploadPaths.concat(this.$store.state.locationsImages.imagesPaths);
                    this.imagesToUpload = []; // здесь так и надо очищать, даже если грузят по одному изображ., т к если не очищать будут грузиться загруженные до этого по второму разу
                },
                error => {
                    this.imagesToUpload = [];
                    this.previewImages = [];
                    this.errors = error;
                    this.errorModalData = this.handleError(error);
                    openModal('error-modal');
                }
            ).catch(error => {
                this.imagesToUpload = [];
                this.previewImages = [];
                this.errors = error;
                this.errorModalData = this.handleError(error);
                openModal('error-modal');
            }).finally(() => {
                this.isSending = false;
            });
        },

        addTag(event) {
            event.preventDefault();

            if (this.addTagInputValue.length < 2) return false;

            this.tags.push(this.addTagInputValue);
            this.addTagInputValue = '';

            this.$store.commit('searchTags/clearFinded');
        },

        searchTags() {
            if (this.addTagInputValue.length < 3) return false;

            let params = {
                query: this.addTagInputValue
            };
            this.$store.dispatch(`searchTags/find`, params);
        },

        insertTagSuggestion(tag_text) {
            this.addTagInputValue = tag_text;
            this.$store.commit('searchTags/clearFinded');
            this.$refs.add_tag_input_value.focus();
        },

        submit() {
            this.params.title = this.title;
            this.params.description = this.description;
            this.params.category = this.category;
            this.params.route = this.route;
            this.params.images = this.imagesToUploadPaths;
            this.params.tags = this.tags;

            this.$store.dispatch(`locations/store`, this.params).then(
                success => {
                    // Создаем и сохраняем маркер на карте
                    let location_id = this.$store.getters['locations/storedLocationId'];
                    ymapsmarkers.addMarker(location_id, window.ADD_LOCATION_COORDS_GLOBAL);
                    ymapsmarkers.saveMarker(location_id, window.ADD_LOCATION_COORDS_GLOBAL);

                    // Завершаем создание локации
                    this.resetData();
                    closeCurrentModal();
                    openModal('add-location-success-modal');
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

        resetData() {
            this.title = '';
            this.description = '';
            this.category = null;

            this.previewImages = [];
            this.imagesToUpload = [];
            this.imagesToUploadPaths = [];

            this.addTagInputValue = '';
            this.tags = [];
            this.createdRoute = '';
            this.route = '';

            this.params = {
                title: '',
                description: '',
                category: null,
                images: [],
                tags: [],
                route: '',
            };

            window.ADD_LOCATION_COORDS_GLOBAL = [];
        },

        submitCreatedRoute(route_name) {
            let route_data = {
                name: route_name
            };

            this.$store.dispatch(`routes/store`, route_data).then(
                success => {
                    closeModal('create-route-and-add-location-modal');
                    this.$store.dispatch("routes/getRoutes", {});
                    this.route = this.$store.getters['routes/storedRouteId'];
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
