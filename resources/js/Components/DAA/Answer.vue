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

<!--    Answer first appeal-->

    <q-card class="mt-4 bg-sky-100" v-if="!info.first_appeal_daa_answer">
        <q-card-section>
            <div class="text-lg q-mb-md">
                <div class="text-h6 q-mb-sm text-purple-600">Reply for 1st Appeal</div>
<!--                <p class="q-mb-sm text-purple-600">Reply for 1st Appeal</p>-->
                <p class="text-bold text-sm q-mb-sm">
                    Departmental Appellate Authority (DAA) te tan chhiar tur / Must read for DAA
                </p>
                <p class="text-bold text-sm q-mb-sm">
                    Note:
                    <span class="text-regular">
                        Departmental Appellate Authorities are to strictly comply with the provisions of
                        Section 19 of the RTI Act, 2005 and are also instructed to follow the Office
                        Memorandum No. 10/23/2007-IR dt. 09.07.2007 issued by DoPT, Government of India.
                    </span>
                </p>
                <q-btn
                    flat
                    label="Disposal of first appeals under the RTI Act, 2005 - Download"
                    class="text-blue-600 text-bold q-mb-md"
                    @click="downloadDocument"
                />
            </div>

            <q-checkbox
                v-model="understood"
                label="I understand / ka hrethiam e"
                class="text-lg q-mb-md"
            />

            <div v-if="understood">
                <q-input
                    v-model="form.reply"
                    filled
                    type="textarea"
                    rows="8"
                    label="Reply to applicant"
                    class="q-mb-md"
                />

                <q-file
                    filled
                    v-model="form.attachment"
                    label="Attach Document (optional)"
                    accept=".jpg, .jpeg, .png, .pdf"
                    class="q-mt-md"
                    multiple
                />

                <q-btn
                    label="Reply Applicant"
                    color="orange-8"
                    text-color="white"
                    class="text-bold q-mt-md"
                    @click="submitReply"
                />
            </div>
        </q-card-section>
    </q-card>

<!--    First appeal answer-->
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
</template>

<script setup>
import {ref} from 'vue'
import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";


const props = defineProps(['info']);
const $q = useQuasar()
const form = useForm({
    reply: '',
    attachment: [],
})
const understood = ref(false)

function downloadDocument () {
    // Implement download logic
    alert('Download triggered')
}


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
        axios.post(route('daa.information.store', props.info), fd)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Reply submitted successfully.'
                })
                form.reset()
                router.get(route('daa.information.show', props.info))
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

