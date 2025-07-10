<template>
    <q-card class="mt-4" >
        <q-card-section>
            <div class="text-h6 text-purple-600">Second Appeal Answer</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="info.second_appeal_cic_answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Answered on: {{ info.second_appeal_cic_out }}</p>


            <q-separator class="q-my-md" />


        </q-card-section>
    </q-card>
</template>

<script setup>
const props = defineProps(['info']);
</script>

<style scoped>

</style>
