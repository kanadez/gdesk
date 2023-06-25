<template>
    <div>

        <header :class="`header ${isHeaderCollapsed ? 'collapsed' : ''}`">
            <div class="container">
                <div class="header__content">
                    <div class="header__left">
                        <a class="a-header" href="javascript:;"><img src="images/icon/logo.png"></a>
                    </div>
                    <div class="header__center">
                        <div class="title no-padding">Стрелка</div>
                    </div>
                    <div class="header__right">
                        <a class="a-header toggler" href="javascript:;" data-target="search-location"><img src="images/icon/i-search.svg"></a>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <RouterView  />
        </main>
        <footer></footer>

    </div>
</template>

<script>

export default {
    name: "LayoutApp",
    components: {

    },
    computed: {
        isHeaderCollapsed () {
            return this.$store.getters['app/headerCollapsed'];
        },
    },
    data() {
        return {

        }
    },
    methods: {

    }
}
</script>
