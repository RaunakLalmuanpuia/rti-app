<template>
    <div class="q-pa-md">
        <div class="text-h6 mb-4">SPIO INFORMATION INDEX</div>

        <!-- Tabs -->
        <q-tabs
            v-model="tab"
            dense
            align="left"
            active-color="primary"
            indicator-color="primary"
            class="q-mb-md"
        >
            <q-tab name="pending" label="Pending" />
            <q-tab name="answered" label="Answered" />
            <q-tab name="computerGenerated" label="Computer Generated" />

        </q-tabs>

        <!-- Tab Panels -->
        <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="pending">
                <Pending />
            </q-tab-panel>

            <q-tab-panel name="answered">
                <Answered />
            </q-tab-panel>

            <q-tab-panel name="computerGenerated">
                <ComputerGenerated />
            </q-tab-panel>

        </q-tab-panels>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { QTabs, QTab, QTabPanels, QTabPanel } from 'quasar';

import BackendLayout from "@/Layouts/BackendLayout.vue";

import Pending from "@/Components/SPIO/Information/Pending.vue";
import Answered from "@/Components/SPIO/Information/Answered.vue";
import ComputerGenerated from "@/Components/SPIO/Information/ComputerGenerated.vue";


defineOptions({ layout: BackendLayout });

const tab = ref('pending');
</script>

<style scoped>
.text-h6 {
    font-size: 1.25rem;
    font-weight: 600;
}
</style>
