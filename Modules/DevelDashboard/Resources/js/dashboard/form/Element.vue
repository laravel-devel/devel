<template>
    <div class="form-group" :class="{ 'inline': inline }">
        <v-fel-input v-if="inputTypes.indexOf(field.type) >= 0"
            :attrs="attrs"
            :value="value"
            @input="onInput"></v-fel-input>

        <v-fel-checkbox v-else-if="field.type === 'checkbox'"
            :attrs="attrs"
            :value="value"
            @input="onInput"></v-fel-checkbox>

        <v-fel-link v-else-if="field.type === 'link'"
            :attrs="attrs"></v-fel-link>

        <v-fel-select v-else-if="field.type === 'multiselect'"
            :attrs="attrs"
            :value="value"
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
import Link from './elements/Link';
import Select from './elements/Select';

export default {
    components: {
        'v-fel-input': Input,
        'v-fel-checkbox': Checkbox,
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
    },

    data() {
        return {
            inputTypes: [
                'text',
                'email',
                'password',
                'hidden',
            ],
            attrs: {},
        };
    },

    computed: {
        errors() {
            return this.attrs.name
                ? this.$parent.errors[this.attrs.name]
                : undefined;
        },

        value() {
            if (this.attrs.value) {
                return this.attrs.value;
            }

            return this.attrs.name
                ? this.$parent.values[this.attrs.name]
                : undefined;
        },
    },

    created() {
        this.attrs = Object.assign({}, this.field);
        this.attrs.label = this.showLabel ? this.field.label : undefined;

        if (this.field.type === 'checkbox') {
            this.attrs.checked = this.attrs.checked === undefined
                ? this.value
                : this.attrs.checked;
        }

        this.attrs.disabled = (this.attrs.disabled == true) ? true : false;
    },

    methods: {
        onInput(input) {
            this.$emit('input', input);
        }
    }
}
</script>
