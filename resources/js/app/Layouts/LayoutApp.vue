<template>
    <div>

        <header class="header">
            <div class="container">
                <div class="header__content">
                    <div class="header__left">
                        <a class="a-header" href="javascript:;"><img src="images/icon/i-marker.svg"></a>
                    </div>
                    <div class="header__center">
                        <div class="title">Название</div>
                    </div>
                    <div class="header__right">
                        <a class="a-header toggler" href="javascript:;" data-target="search"><img src="images/icon/i-search.svg"></a>
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
    data() {
        return {

        }
    },
    methods: {

    }
}
</script>
