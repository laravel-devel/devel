<template>
    <div>
        <label v-if="attrs.label" v-text="attrs.label"></label>

        <input class="form-element"
            :type="attrs.type"
            :name="attrs.name">
    </div>
</template>

<script>
export default {
    props: ['attrs'],
}
</script>
