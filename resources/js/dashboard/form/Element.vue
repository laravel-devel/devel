<template>
    <div class="form-group" :class="{ 'inline': inline }">
        <v-fel-input v-if="inputTypes.indexOf(field.type) >= 0"
            :attrs="field"
            :value="value"
            @input="onInput"></v-fel-input>

        <v-fel-checkbox v-else-if="field.type === 'checkbox'"
            :attrs="field"
            :value="value"
            @input="onInput"></v-fel-checkbox>

        <v-fel-link v-else-if="field.type === 'link'"
            :attrs="field"></v-fel-link>

        <div v-if="errors" class="hint danger">
            {{ errors[0] }}
        </div>
    </div>
</template>

<script>
import Input from './elements/Input';
import Checkbox from './elements/Checkbox';
import Link from './elements/Link';

export default {
    components: {
        'v-fel-input': Input,
        'v-fel-checkbox': Checkbox,
        'v-fel-link': Link,
    },

    props: {
        field: {},

        value: {
            default: null,
        },

        errors: {},

        inline: {
            type: Boolean,
            default: false,
        }
    },

    data() {
        return {
            inputTypes: [
                'text',
                'email',
                'password',
                'hidden',
            ],
        };
    },

    methods: {
        onInput(input) {
            this.$emit('input', input);
        }
    }
}
</script>
