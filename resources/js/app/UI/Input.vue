<template>
    <div>
        <input :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
        v-bind="$attrs" />
    </div>
</template>

<script>
export default {
    name: "Input",
    emits: ['update:modelValue'],
    props: {
        type: String,
        modelValue: String,
    },
}
</script>
