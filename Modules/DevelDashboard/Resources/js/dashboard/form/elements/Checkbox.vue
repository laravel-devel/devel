<template>
    <div>
        <label class="checkbox" :class="{ 'disabled': attrs.disabled }">
            <span v-if="attrs.label">{{ attrs.label }}</span>

            <input class="form-element" 
                :type="attrs.type"
                :name="attrs.name"
                autocomplete="off"
                :checked="attrs.checked"
                :value="value"
                :disabled="attrs.disabled"
                @change="$emit('input', $event.target.checked)">

            <span class="checkmark"></span>
        </label>
    </div>
</template>

<script>
export default {
    props: ['attrs', 'value'],
}
</script>
