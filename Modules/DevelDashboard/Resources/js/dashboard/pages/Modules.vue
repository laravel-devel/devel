<template>
    <div class="pages-modules">
        <p v-show="changesMade" class="mb-1 text-danger text-center">
            Refresh the page to see changes
        </p>

        <div class="card">
            <div v-for="(key, index) in Object.keys(modules)"
                :key="index"
                class="module"
            >
                <div class="flex-1">
                    <p class="name">
                        {{ modules[key].displayName }} ({{ modules[key].name }})
                    </p>

                    <p>
                        {{ modules[key].description }}
                    </p>
                </div>

                <div>
                    <v-form-el :field="{
                        type: 'switch',
                        value: key,
                        checked: values[key],
                        disabled: processing,
                    }"
                    v-model="values[key]"
                    @input="onEnabledToggled(key, ...arguments)"></v-form-el>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modules: {
            type: Object,

            default: () => {
                return {};
            },
        },

        baseUrl: {
            type: String,
            default: '',
        },
    },

    data() {
        const values = {};

        for (let key of Object.keys(this.modules)) {
            values[key] = this.modules[key].enabled;
        }

        return {
            changesMade: false,
            values: values,
            processing: false,
        };
    },
    
    methods: {
        onEnabledToggled(key, enabled) {
            this.processing = true;

            const endpoint = `${this.baseUrl}/${this.modules[key].alias}`;
            let moduleName = key;

            if (this.modules[key].displayName) {
                moduleName = `${this.modules[key].displayName} (${key})`;
            }

            axios.post(endpoint)
                .then((response) => {
                    let msg = enabled ? 'Enabled ' : 'Disabled ';
                    msg += `module "${moduleName}"`;

                    this.$notify(msg, enabled ? 'success' : 'info');

                    this.changesMade = true;

                    this.processing = false;
                })
                .catch(({ response }) => {
                    let msg;

                    if (response.status == 422 || response.status == 404) {
                        msg = response.data.message;
                    } else {
                        msg = `Something went wrong! Module "${moduleName}" has not been ` + (enabled ? 'disabled.' : 'enabled.');
                    }

                    this.$notify(msg, 'error');

                    this.$set(this.values, key, !enabled);

                    this.processing = false;
                });
        }
    },
}
</script>
