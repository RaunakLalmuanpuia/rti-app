<template>
    <!-- Answer-->
    <SPIO v-if="info.spio_answer" :info="info"/>
    <!--    First Appeal Question-->
    <q-card class="mt-4" v-if="info.first_appeal_citizen_question">
        <q-card-section>
            <div class="text-h6 text-purple-600">First Appeal Question</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Question / Zawhna"
                v-model="info.first_appeal_citizen_question"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Submitted on: {{ info.first_appeal_daa_in }}</p>

        </q-card-section>
    </q-card>
    <!--    First Appeal Answer-->
    <DAA  v-if="info.first_appeal_daa_answer" :info="info"/>
    <!--    Second Appeal Question-->
    <q-card class="mt-4" v-if="info.second_appeal_citizen_question">
        <q-card-section>
            <div class="text-h6 text-purple-600">Second Appeal Question</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Question / Zawhna"
                v-model="info.second_appeal_citizen_question"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Submitted on: {{info.second_appeal_cic_in}}</p>

        </q-card-section>
    </q-card>
    <!--    Second Appeal Answer-->
    <CIC  v-if="info.second_appeal_cic_answer" :info="info"/>
</template>

<script setup>
import SPIO from "@/Components/Citizen/Information/Answer/SPIO.vue";
import DAA from "@/Components/Citizen/Information/Answer/DAA.vue";
import CIC from "@/Components/Citizen/Information/Answer/CIC.vue";


const props = defineProps(['info']);




</script>
