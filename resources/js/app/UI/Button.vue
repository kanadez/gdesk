<template>
    <button :class="'btn btn-' + buttonType" v-bind="$attrs" >
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "Button",
    props: {
        type: String,
    },
    data() {
        return {
            buttonType: this.$props.type ?? 'primary',
        }
    },
}
</script>
