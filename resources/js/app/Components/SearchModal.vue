<template>
    <div class="modal-backdrop modal-full" id="search">
        <div class="modal">
            <div class="modal__body">
                <div class="modal__header">
                    <div :onclick="`closeModal('${id}')`" class="modal__close"><img src="images/icon/i-close.svg">
                    </div>
                </div>
                <div class="label">Выберите категорию, затем локацию в списке</div>
                <div class="slider">
                    <div class="owl-carousel tag-slider"><a class="tag" href="#">тег 1</a><a class="tag" href="#">тег
                        2</a><a class="tag" href="#">тег 3</a><a class="tag" href="#">тег 1 тег 3 тег 3</a></div>
                </div>
                <div class="category">
                    <div class="category__list">
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
                                <span>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum</span>
                            </div>
                        </div>
                        <div class="category__item">
                            <div class="category__img"></div>
                            <div class="category__text">
                                <div class="category__title">Название</div>
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
    props: {},
    computed: {},
    data() {
        return {
            isSending: false,
            params: {},
            errors: null,
        }
    },
    methods: {},

    created() {

    }
}
</script>

<style scoped>

</style>
