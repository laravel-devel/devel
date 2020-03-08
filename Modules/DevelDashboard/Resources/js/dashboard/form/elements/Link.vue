<template>
    <div>
        <a :href="attrs.attrs.url" v-text="attrs.label"></a>
    </div>
</template>

<script>
export default {
    props: ['attrs'],
}
</script>
