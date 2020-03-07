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

        <slot v-bind:errors="errors" v-bind:values="values" v-bind:read-only="readOnly">
            <v-form-tab v-for="(key, index) in Object.keys(fields)"
                :key="index"
                :show="slug(key) === activeTab"
                :name="key"
                :type="type"
                :fields="fields[key]"
                :collections="collections"
                :values="values"
                :errors="errors"
                :read-only="readOnly"></v-form-tab>
        </slot>

        <div class="message" :class="messageClass" v-text="message"></div>

        <template v-if="!readOnly">
            <button v-if="button"
                type="submit"
                class="btn"
                :disabled="this.processing"
                v-text="button.text"></button>

            <button v-else
                type="submit"
                class="btn"
                :disabled="this.processing">Save</button>
        </template>
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

        fields: {
            type: Object,
            default: () => {
                return {};
            },
        },
        
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
        },

        readOnly: {
            type: Boolean,
            default: false,
        },
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

    created() {
        if (Object.keys(this.fields).length) {
            for (let name of Object.keys(this.fields)) {
                const key = this.slug(name);

                this.tabs.push({
                    el: this.$refs[`tab-content-${key}`],
                    key: key,
                    name: name,
                });
            }
        }
    },

    mounted() {
        if (!Object.keys(this.fields).length && this.$children !== undefined) {
            this.tabs = this.$children.map(item => {
                return {
                    el: item.$el,
                    key: this.slug(item.$attrs.name),
                    name: item.$attrs.name,
                };
            });
        }

        if (location.hash.substr(0, 5) === '#tab-') {
            this.activeTab = location.hash.substr(5);
        } else {
            this.activeTab = this.tabs[0].key;
        }

        this.showTab(this.activeTab);
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

                if (data.notification) {
                    this.$notify(
                        data.notification.message,
                        data.notification.type ? data.notification.type : null
                    );
                }

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

                this.$notify(this.message, 'error');

                this.processing = false;
            });
        },

        clearErrors() {
            this.errors = {};
        },

        showTab(key, secondCall = false) {
            const tab = this.tabs.find(item => item.key === key);

            if (!tab) {
                if (!secondCall) {
                    this.showTab(this.tabs[0].key, true);
                }

                return;
            }

            for (let item of this.tabs) {
                if (item.el) {
                    item.el.classList.add('hidden');
                }
            }

            if (tab.el) {
                tab.el.classList.remove('hidden');
            }

            this.activeTab = key;

            // Change the window location hash
            if (this.tabs.length > 0) {
                const hash = '#tab-' + key;

                if (history.pushState) {
                    history.replaceState(null, null, hash);
                } else {
                    location.hash = hash;
                }
            }
        },
        
        slug(str) {
            return str.toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
        }
    },
}
</script>
