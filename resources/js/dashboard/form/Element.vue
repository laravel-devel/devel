<template>
    <div class="form-group">
        <v-fel-input v-if="inputTypes.indexOf(field.type) >= 0"
            :attrs="field"></v-fel-input>

        <v-fel-checkbox v-else-if="field.type === 'checkbox'"
            :attrs="field"></v-fel-checkbox>

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

    props: ['field', 'value', 'errors'],

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

    created() {
        this.attrs = Object.assign(this.field, {
            value: this.value ? this.value : null,
        });
    }
}
</script>
