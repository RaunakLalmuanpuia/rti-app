<template>
    <!-- SPIO  answer-->
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
    <!-- First appeal answer-->
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
        </q-card-section>
    </q-card>
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
            <div class="q-mt-md" v-if="info.second_appeal_citizen_question_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.second_appeal_citizen_question_file.split(',')"
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

            <p class="text-subtitle2 q-mt-sm">Submitted on: {{info.second_appeal_cic_in}}</p>

        </q-card-section>
    </q-card>
    <!--    Answer Second Appeal-->
    <q-card class="mt-4" v-if="!info.second_appeal_cic_answer">
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-purple-600">Reply for Second Appeal</div>
        </q-card-section>
        <q-card-section>
            <q-input
                filled
                v-model="form.reply"
                type="textarea"
                rows="6"
                label="Answer"
                placeholder="Type your answer here"
            />

            <q-file
                filled
                v-model="form.attachment"
                label="Attach Document (optional)"
                accept=".jpg, .jpeg, .png, .pdf"
                class="q-mt-md"
                multiple
            />

            <q-separator class="q-my-md" />

            <q-btn
                color="orange"
                label="Reply Applicant"
                @click="submitReply"
            />

        </q-card-section>
    </q-card>
    <!--    Second Appeal Answer-->
    <q-card class="mt-4" v-if="info.second_appeal_cic_answer" >
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


            <div class="q-mt-md" v-if="info.second_appeal_cic_answer_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.second_appeal_cic_answer_file.split(',')"
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
            <q-separator class="q-my-md" />


        </q-card-section>
    </q-card>

</template>

<script setup>
import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";


const props = defineProps(['info']);
const $q = useQuasar()
const form = useForm({
    reply: '',
    attachment: [],
})

const submitReply = () => {
    if (!form.reply) {
        $q.notify({
            type: 'warning',
            message: 'Please enter your reply.'
        })
        return
    }
    $q.dialog({
        title: 'Confirm Submission',
        message: 'Are you sure you want to submit the reply?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        const fd = new FormData()
        fd.append('reply', form.reply)

        if (form.attachment && form.attachment.length) {
            form.attachment.forEach((file, index) => {
                fd.append(`attachment[${index}]`, file)
            })
        }
        axios.post(route('cic.information.store', props.info), fd)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Reply submitted successfully.'
                })
                form.reset()
                router.get(route('cic.information.show', props.info))
            })
            .catch(error => {
                if (error.response) {
                    const errors = error.response.data.errors
                    const messages = []
                    if (errors) {
                        for (const field in errors) {
                            if (errors[field]) {
                                messages.push(...errors[field])
                            }
                        }
                        $q.notify({
                            type: 'negative',
                            message: messages,
                        })

                    } else if (error.response.data.message) {
                        $q.notify({
                            type: 'negative',
                            message: error.response.data.message,
                        })
                    }
                } else {
                    $q.notify({
                        type: 'negative',
                        message: 'System Error. Please try again later.'
                    })
                }
            })
    })
}

</script>
<style scoped>

</style>

