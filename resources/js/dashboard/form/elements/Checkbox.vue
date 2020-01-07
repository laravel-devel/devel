<template>
    <div>
        <label class="checkbox" v-if="attrs.label">
            {{ attrs.label }}

            <input class="form-element"
                :type="attrs.type"
                :name="attrs.name">

            <span class="checkmark"></span>
        </label>
    </div>
</template>

<script>
export default {
    props: ['attrs'],
}
</script>
