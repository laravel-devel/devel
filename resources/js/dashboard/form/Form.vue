<template>
    <form :action="action"
        :method="method"
        novalidate
        @submit.prevent="onSubmit"
        ref="form"
    >
        <v-form-el v-for="(field, index) in fields"
            :key="index"
            :field="field"
            :errors="errors[field.name] ? errors[field.name] : []"></v-form-el>

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
        'success',
    ],

    data() {
        return {
            processing: false,
            // formData: {},
            errors: {},
        };
    },

    methods: {
        onSubmit() {
            const formData = new FormData(this.$refs.form);

            axios({
                method: this.method,
                url: this.action,
                data: formData,
            })
            .then(({ data }) => {
                this.errors = [];
                
                if (this.success) {
                    window.location = this.success;
                }
            })
            .catch(({ response }) => {
                if (response.status === 422) {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }
                }
            });
        }
    },
}
</script>
