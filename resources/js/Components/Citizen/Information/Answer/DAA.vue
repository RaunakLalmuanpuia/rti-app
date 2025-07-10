<template>
    <q-card class="mt-4">
        <q-card-section>
            <div class="text-h6 text-purple-600">First Appeal Answer</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="info.first_appeal_daa_answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Answered on: {{ info.first_appeal_daa_out }}</p>
            <p class="text-subtitle2 q-mt-sm">
                DAA: {{ info. daa_name}}<br />
                DAA Contact: {{info.daa_contact}}
            </p>


            <q-separator class="q-my-md" />

            <template v-if="!info.second_appeal_citizen_question">
                <p class="text-subtitle2">Department chhanna hi i lungawi lo em?</p>

                <q-btn
                    color="orange"
                    label="Do you want to apply for 2nd Appeal?"
                    @click="applySecondAppeal"
                />

            </template>

        </q-card-section>
    </q-card>
</template>
<script setup>
const props = defineProps(['info']);

const applySecondAppeal = () => {
    console.log('Apply for 1st Appeal')
}

</script>


<style scoped>

</style>
