<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
defineOptions({ layout: BackendLayout });

const props = defineProps({
    info: Object,
})

</script>

<template>
 {{info}}
</template>

<style scoped>

</style>
