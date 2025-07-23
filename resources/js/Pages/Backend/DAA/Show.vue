<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail :info="info" />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">

                <Answer :info="info" v-if="info.spio_answer"/>

                <FirstAppealQuestion v-if="info.first_appeal_citizen_question"  :info="info" class="mt-4" />

                <!--    Answer first appeal-->
                <q-card class="mt-4 bg-sky-100" v-if="!info.first_appeal_daa_answer && info.first_appeal_citizen_question ">
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

                <FirstAppealAnswer v-if="info.first_appeal_daa_answer" class="mt-4" :info="info" />

            </div>
        </div>
    </q-page>

</template>

<script setup>

import {ref} from 'vue'
import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";


import BackendLayout from "@/Layouts/BackendLayout.vue";

import Detail from "../../../Components/Information/Detail.vue";
import Answer from "../../../Components/Information/Answer.vue";
import FirstAppealQuestion from "../../../Components/Information/FirstAppealQuestion.vue";
import FirstAppealAnswer from "../../../Components/Information/FirstAppealAnswer.vue";


defineOptions({ layout: BackendLayout });

const props = defineProps({
    info: Object,
})


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
