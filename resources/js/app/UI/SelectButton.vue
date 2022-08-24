<template>
    <div :class="getClass" @click="selectMe">
        <div v-if="icon">
        </div>
        <div>
        {{ title }}
        </div>
        <span class="bg-sky-50 border-sky-400 hover:bg-sky-100 bg-emerald-50 border-emerald-400 hover:bg-emerald-100"></span>
    </div>
</template>

<script>
export default {
    name: "SelectButton",
    computed: {
        getClass() {
            let classes = ('border border-gray-100 rounded-lg p-1 pointer cursor-pointer text-center text-xs font-bold hover:bg-' + this.color + '-100 w-full').split(' ');
            if(this.selected) {
                classes.push('bg-' + this.color + '-50 border-' + this.color + '-400');
            }
            return classes.join(' ');
        }
    },
    props: {
        id: {
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            required: false,
        },
        color: {
            type: String,
            required: false,
            default: 'emerald'
        },
        selected: Boolean
    },
    methods: {
        selectMe() {
            this.$emit('select', this.id);
        }
    }
}
</script>

<style scoped>

</style>
