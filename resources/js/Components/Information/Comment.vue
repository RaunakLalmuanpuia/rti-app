<script setup>
const props = defineProps(['info']);
</script>

<template>
    <q-card>
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-purple-600">SAPIO Comment</div>
        </q-card-section>

        <q-card-section v-if="info.aspio_answer">
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="info.aspio_answer"
                class="bg-grey-2"
                autogrow
            />

            <p class="text-subtitle2 q-mt-sm">
                SPIO: {{ info.aspio_name }}<br />
                SPIO Contact: {{info.aspio_contact}}
            </p>

        </q-card-section>

        <q-card-section v-if="!info.aspio_answer">
            <q-input
                filled
                readonly
                type="textarea"
                label="No Comment"
                v-model="info.aspio_answer"
                class="bg-grey-2"
                rows="6"
            />
        </q-card-section>
    </q-card>
</template>

<style scoped>

</style>
