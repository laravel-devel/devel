<template>
    <form class="form"
        :action="action"
        :method="method"
        novalidate
        @submit.prevent="onSubmit"
        ref="form"
    >
        <v-form-el v-for="(field, index) in fields"
            :key="index"
            :field="field"
            :errors="errors[field.name] ? errors[field.name] : []"
            :value="(values && values[field.name]) ? values[field.name] : []"></v-form-el>

        <div class="message" :class="messageClass" v-text="message"></div>

        <button v-if="button"
            type="submit"
            class="btn"
            :disabled="this.processing"
            v-text="button.text"></button>

        <button v-else
            type="submit"
            class="btn"
            :disabled="this.processing">Save</button>
    </form>
</template>

<script>
export default {
    props: [
        'action',
        'method',
        'fields',
        'values',
        'button',
        'success',
    ],

    data() {
        return {
            processing: false,
            errors: {},
            message: '',
            messageClass: 'success',
        };
    },

    methods: {
        onSubmit() {
            const formData = new FormData(this.$refs.form);
            this.processing = true;

            axios({
                method: this.method,
                url: this.action,
                data: formData,
            })
            .then(({ data }) => {
                this.clearErrors();

                this.message = data.message ? data.message : '';
                this.messageClass = 'success';

                if (this.success) {
                    window.location = this.success;
                } else {
                    this.processing = false;
                }
            })
            .catch(({ response }) => {
                if (response.status === 422) {
                    this.message = response.data.message ? response.data.message : '';
                    this.messageClass = 'danger';

                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }

                    this.processing = false;
                }
            });
        },

        clearErrors() {
            this.errors = {};
        }
    },
}
</script>
