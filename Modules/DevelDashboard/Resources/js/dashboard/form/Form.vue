<template>
    <form class="form"
        :action="action"
        :method="method"
        novalidate
        @submit.prevent="onSubmit"
        ref="form"
    >
        <div v-if="type === 'default'">
            <v-form-el v-for="(field, index) in fields"
                :key="index"
                :field="field"
                :errors="errors[field.name] ? errors[field.name] : []"
                :value="(values && values[field.name]) ? values[field.name] : []"
                class="pb-1">
            </v-form-el>
        </div>

        <div v-else-if="type === 'table'">
            <table>
                <tr v-for="(field, index) in fields"
                    :key="index"
                >
                    <td class="pb-1" v-text="field.label"></td>

                    <td class="pb-1">
                        <v-form-el :field="field"
                            :errors="errors[field.name] ? errors[field.name] : []"
                            :value="(values && values[field.name]) ? values[field.name] : []"
                            :show-label="false"
                            :inline="true">
                        </v-form-el>
                    </td>
                </tr>
            </table>
        </div>

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
    props: {
        action: String,

        method: {
            type: String,
            default: 'GET',
        },

        fields: Array,
        
        values: Object,

        button: Object,

        success: String,

        type: {
            type: String,
            default: 'default',
        }
    },

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
