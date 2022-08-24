<template>
    <div v-if="errors" class="alert alert-error text-xs">
        <strong>{{ errors.message }}</strong>
        <ul>
            <template v-for="errorBag in errors.errors">
                <li v-for="error in errorBag">{{ error }}</li>
            </template>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Errors",
    props: {
        errors: Object
    }
}
</script>

<style scoped>

</style>
