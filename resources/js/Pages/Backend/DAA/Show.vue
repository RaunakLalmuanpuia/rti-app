<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Detail from "@/Components/DAA/Detail.vue";
import Answer from "@/Components/DAA/Answer.vue";
defineOptions({ layout: BackendLayout });

const props = defineProps({
    info: Object,
})

</script>

<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail :info="info" />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">
                <Answer :info="info"/>
            </div>
        </div>
    </q-page>

</template>

<style scoped>

</style>
