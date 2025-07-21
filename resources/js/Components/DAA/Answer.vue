<template>
    <!-- SPIO already answered-->
    <q-card v-if="info.spio_answer">
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-purple-600">SPIO Answer</div>
        </q-card-section>
        <q-card-section v-if="info.spio_answer">
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="info.spio_answer"
                class="bg-grey-2"
                autogrow
            />

            <p class="text-subtitle2 q-mt-sm">Answered on: {{info.spio_out}}</p>
            <p class="text-subtitle2 q-mt-sm">
                SPIO: {{ info.spio_name }}<br />
                SPIO Contact: {{info.spio_contact}}
            </p>

            <div class="q-mt-md" v-if="info.spio_answer_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.spio_answer_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />
                    <a
                        :href="`/storage/files/${file.trim()}`"
                        class="text-blue-600 underline"
                        target="_blank"
                        download
                    >
                        {{ file.trim() }}
                    </a>
                </div>
            </div>

        </q-card-section>
    </q-card>

    <!--First appeal question-->
    <q-card class="mt-4" v-if="info.first_appeal_citizen_question">
        <q-card-section >
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

            <div class="q-mt-md" v-if="info.first_appeal_citizen_question_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.first_appeal_citizen_question_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />

                    <a
                        :href="`/storage/files/${file.trim()}`"
                        class="text-blue-600 underline"
                        target="_blank"
                        download
                    >
                        {{ file.trim() }}
                    </a>

                </div>
            </div>

            <p class="text-subtitle2 q-mt-sm">Submitted on: {{ info.first_appeal_daa_in }}</p>

        </q-card-section>
    </q-card>

    <q-card class="mt-4" v-if="info.first_appeal_daa_answer">
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

            <div class="q-mt-md" v-if="info.first_appeal_daa_answer_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.first_appeal_daa_answer_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />

                    <a
                        :href="`/storage/files/${file.trim()}`"
                        class="text-blue-600 underline"
                        target="_blank"
                        download
                    >
                        {{ file.trim() }}
                    </a>

                </div>
            </div>

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
</script>
<style scoped>

</style>
