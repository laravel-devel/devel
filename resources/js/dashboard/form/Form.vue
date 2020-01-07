<template>
    <form :action="action" :method="method" novalidate>
        <v-form-el v-for="(field, index) in fields"
            :key="index"
            :field="field"></v-form-el>

        <button v-if="button"
            type="submit"
            class="btn"
            v-text="button.text"></button>

        <button v-else
            type="submit"
            class="btn">Save</button>
    </form>
</template>

<script>
export default {
    props: [
        'action',
        'method',
        'fields',
        'button',
    ],

    data() {
        return {
            processing: false,
            formData: {},
            errors: {},
        };
    },
}
</script>
