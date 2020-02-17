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
                :class="{ 'active': tab.key === activeTab }"
                v-text="tab.name"
                @click="showTab(tab.key)"
            ></div>
        </div>

        <slot v-bind:errors="errors" v-bind:values="values">
            <v-form-tab name="Main"
                :type="type"
                :fields="fields"
                :collections="collections"
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
        
        collections: {
            type: Object,
            default: () => {
                return {};
            },
        },

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
                    key: item.$attrs.name.toLowerCase(),
                    name: item.$attrs.name,
                };
            });

            if (location.hash.substr(0, 5) === '#tab-') {
                this.activeTab = location.hash.substr(5);
            } else {
                this.activeTab = this.tabs[0].key;
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
                this.messageClass = 'danger';
                
                if (response.status === 422) {
                    this.message = response.data.message ? response.data.message : '';

                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }
                } else {
                    this.message = response.data.message
                        ? response.data.message
                        : 'Something went wrong!';
                }

                this.processing = false;
            });
        },

        clearErrors() {
            this.errors = {};
        },

        showTab(key) {
            const tab = this.tabs.find(item => item.key === key);

            if (!tab) {
                return;
            }

            for (let item of this.tabs) {
                item.el.classList.add('hidden');
            }

            tab.el.classList.remove('hidden');
            this.activeTab = key;

            // Change the window location hash
            if (this.tabs.length > 1) {
                const hash = '#tab-' + key;

                if (history.pushState) {
                    history.replaceState(null, null, hash);
                } else {
                    location.hash = hash;
                }
            }
        }
    },
}
</script>
