<template>
    <form class="form"
        :action="action"
        :method="method"
        novalidate
        @submit.prevent="onSubmit"
        ref="form"
    >
        <div v-if="tabs.length > 1" class="form-tabs">
            <div v-for="(tab, index) in tabs"
                :key="index"
                class="form-tab"
                :class="{ 'active': tab.name === activeTab }"
                v-text="tab.name"
                @click="showTab(tab.name)"
            ></div>
        </div>

        <slot v-bind:errors="errors" v-bind:values="values">
            <v-form-tab name="Main"
                :type="type"
                :fields="fields"
                :values="values"
                :errors="errors"></v-form-tab>
        </slot>

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
        
        values: {
            type: Object,
            default: () => {
                return {};
            },
        },

        button: Object,

        success: String,

        type: {
            type: String,
            default: 'default',
        }
    },

    data() {
        return {
            tabs: [],
            activeTab: null,
            processing: false,
            errors: {},
            message: '',
            messageClass: 'success',
        };
    },

    mounted() {
        if (this.$children !== undefined) {
            this.tabs = this.$children.map(item => {
                return {
                    el: item.$el,
                    name: item.$attrs.name,
                };
            });

            if (!this.activeTab) {
                this.activeTab = this.tabs[0].name;
            }

            this.showTab(this.activeTab);
        }
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
        },

        showTab(name) {
            const tab = this.tabs.find(item => item.name === name);

            if (!tab) {
                return;
            }

            for (let item of this.tabs) {
                item.el.style.display = 'none';
            }

            tab.el.style.display = 'block';
            this.activeTab = name;
        }
    },
}
</script>
