<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Detail from "@/Components/Citizen/Information/Detail.vue";
import Answer from "@/Components/Citizen/Information/Answer.vue";
defineOptions({ layout: BackendLayout });

</script>

<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">
                <Answer />
            </div>
        </div>
    </q-page>
</template>

<style scoped>

</style>
