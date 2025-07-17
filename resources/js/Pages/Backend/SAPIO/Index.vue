<script setup>
import { ref } from 'vue';
import { QTabs, QTab, QTabPanels, QTabPanel } from 'quasar';

import BackendLayout from "@/Layouts/BackendLayout.vue";
import Application from "@/Components/SAPIO/Information/Application.vue";
import Commented from "@/Components/SAPIO/Information/Commented.vue";

defineOptions({ layout: BackendLayout });

const tab = ref('application');
</script>

<template>
    <div class="q-pa-md">
        <div class="text-h6 mb-4">SAPIO INFORMATION INDEX</div>

        <!-- Tabs -->
        <q-tabs
            v-model="tab"
            dense
            align="left"
            active-color="primary"
            indicator-color="primary"
            class="q-mb-md"
        >
            <q-tab name="application" label="Application" />
            <q-tab name="commented" label="Commented" />
        </q-tabs>

        <!-- Tab Panels -->
        <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="application">
                <Application />
            </q-tab-panel>

            <q-tab-panel name="commented">
                <Commented />
            </q-tab-panel>
        </q-tab-panels>
    </div>
</template>

<style scoped>
.text-h6 {
    font-size: 1.25rem;
    font-weight: 600;
}
</style>
