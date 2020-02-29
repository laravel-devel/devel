<template>
    <div>
        <label class="switch" :class="{ 'disabled': attrs.disabled }">
            <input class="form-element" 
                type="checkbox"
                :name="attrs.name"
                autocomplete="off"
                :checked="attrs.checked"
                :value="value"
                :disabled="attrs.disabled"
                @change="$emit('input', $event.target.checked)">

            <span class="slider"></span>
        </label>
    </div>
</template>

<script>
export default {
    props: ['attrs', 'value'],
}
</script>
