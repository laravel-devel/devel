<template>
    <div>
        <label v-if="attrs.label" v-text="attrs.label"></label>

        <input class="form-element"
            :type="attrs.type"
            :name="attrs.name"
            autocomplete="off"
            :value="value"
            @input="$emit('input', $event.target.value)">
    </div>
</template>

<script>
export default {
    props: ['attrs', 'value'],
}
</script>
