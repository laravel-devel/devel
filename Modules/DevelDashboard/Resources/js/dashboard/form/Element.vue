<template>
    <div class="form-group" :class="{ 'inline': inline }">
        <v-fel-input v-if="inputTypes.indexOf(field.type) >= 0"
            :attrs="attrs"
            :value="val"
            @input="onInput"></v-fel-input>

        <v-fel-checkbox v-else-if="field.type === 'checkbox'"
            :attrs="attrs"
            :value="val"
            @input="onInput"></v-fel-checkbox>

        <v-fel-switch v-else-if="field.type === 'switch'"
            :attrs="attrs"
            :value="val"
            @input="onInput"></v-fel-switch>

        <v-fel-link v-else-if="field.type === 'link'"
            :attrs="attrs"></v-fel-link>

        <v-fel-select v-else-if="field.type === 'select'"
            :attrs="attrs"
            :value="val"
            :collections="collections"
            @input="onInput"></v-fel-select>

        <div v-if="errors" class="hint danger">
            {{ errors[0] }}
        </div>
    </div>
</template>

<script>
import Input from './elements/Input';
import Checkbox from './elements/Checkbox';
import Switch from './elements/Switch';
import Link from './elements/Link';
import Select from './elements/Select';

export default {
    components: {
        'v-fel-input': Input,
        'v-fel-checkbox': Checkbox,
        'v-fel-switch': Switch,
        'v-fel-link': Link,
        'v-fel-select': Select,
    },

    props: {
        field: {},

        collections: {
            type: Object,
            default: () => {
                return {};
            },
        },

        inline: {
            type: Boolean,
            default: false,
        },

        showLabel: {
            type: Boolean,
            default: true,
        },

        value: {},
    },

    data() {
        return {
            inputTypes: [
                'text',
                'hidden',
                'email',
                'password',
                'number',
                'color',
                'range',
            ],
            attrs: {},
            readOnly: this.$parent.tabReadOly,
        };
    },

    computed: {
        errors() {
            if (!this.$parent.errors) {
                return undefined;
            }

            return this.attrs.name
                ? this.$parent.errors[this.attrs.name]
                : undefined;
        },

        val() {
            if (this.attrs.value) {
                return this.attrs.value;
            }

            if (!this.$parent.values) {
                return undefined;
            }

            return this.attrs.name
                ? this.$parent.values[this.attrs.name]
                : undefined;
        },
    },

    created() {
        this.attrs = Object.assign({}, this.field, this.field.attrs);
        this.attrs.label = this.showLabel ? this.field.label : undefined;
        this.attrs.disabled = (
            this.readOnly || ((this.attrs.disabled == true) ? true : false)
        );

        this.setChecked();
    },

    watch: {
        value() {
            this.updateChecked();
        }
    },

    methods: {
        onInput(input) {
            this.$emit('input', input);
        },

        // Set the "check" attr of the checkbox-like fields
        setChecked() {
            if (this.field.type !== 'checkbox' && this.field.type !== 'switch') {
                return;
            }

            this.attrs.checked = this.attrs.checked === undefined
                ? this.value
                : this.attrs.checked;
        },

        // Update the "check" attr of the checkbox-like fields
        updateChecked() {
            if (this.field.type !== 'checkbox' && this.field.type !== 'switch') {
                return;
            }

            this.attrs.checked = this.value;
        },
    }
}
</script>
