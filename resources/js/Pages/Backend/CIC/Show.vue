<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail :info="info" />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">
                <Answer :info="info"/>

                <FirstAppealQuestion :info="info" class="mt-4" />

                <FirstAppealAnswer :info="info" class="mt-4" />

                <SecondAppealQuestion :info="info" class="mt-4" />
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

                <SecondAppealAnswer v-else  class="mt-4" :info="info" />

            </div>
        </div>
    </q-page>

</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Detail from "../../../Components/Information/Detail.vue";
import Answer from "../../../Components/Information/Answer.vue";
import FirstAppealQuestion from "../../../Components/Information/FirstAppealQuestion.vue";
import FirstAppealAnswer from "../../../Components/Information/FirstAppealAnswer.vue";
import SecondAppealQuestion from "../../../Components/Information/SecondAppealQuestion.vue";
import SecondAppealAnswer from "../../../Components/Information/SecondAppealAnswer.vue";

import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";

defineOptions({ layout: BackendLayout });

const props = defineProps({
    info: Object,
})

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
