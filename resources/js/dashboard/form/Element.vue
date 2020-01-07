<template>
    <div class="form-group">
        <v-fel-input v-if="inputTypes.indexOf(field.type) >= 0"
            :attrs="field"></v-fel-input>

        <v-fel-checkbox v-else-if="field.type === 'checkbox'"
            :attrs="field"></v-fel-checkbox>
    </div>
</template>

<script>
import Input from './elements/Input';
import Checkbox from './elements/Checkbox';

export default {
    components: {
        'v-fel-input': Input,
        'v-fel-checkbox': Checkbox,
    },

    props: ['field'],

    data() {
        return {
            inputTypes: [
                'text',
                'email',
                'password',
            ],
        };
    },
}
</script>
