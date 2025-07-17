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

    <q-card v-if="showAppealCard" class="q-mt-md">
        <q-card-section>
            <q-input
                filled
                v-model="form.appeal_reason"
                type="textarea"
                rows="6"
                label="Appeal Reason"
                placeholder="Type your appeal reason here"
            />
            <q-file
                filled
                v-model="form.attachment"
                label="Attach Document (optional)"
                accept=".jpg, .jpeg, .png, .pdf"
                class="q-mt-md"
                multiple
            />
            <q-checkbox
                v-model="confirmChecked"
                label="Note: I understand that the 2nd appeal will be forwarded to
                        the Mizoram Information Commission"
                class="q-mt-md"
            />

            <q-separator class="q-my-md" />

            <q-btn
                color="orange"
                label="Submit for 2nd Appeal"
                @click="submitSecondAppeal"
            />

        </q-card-section>
    </q-card>
</template>
<script setup>
import axios from "axios";
import {useQuasar} from "quasar";
import {ref} from "vue";
import {useForm,router} from "@inertiajs/vue3";

const props = defineProps(['info']);

const $q = useQuasar()

const showAppealCard = ref(false)

const form = useForm({
    appeal_reason: '',
    attachment: null,
})
const confirmChecked = ref(false)


const applySecondAppeal = () => {
    showAppealCard.value = !showAppealCard.value
    console.log('Apply for 1st Appeal')
}

const submitSecondAppeal = () => {
    if (!confirmChecked.value) {
        $q.notify({
            type: 'warning',
            message: 'You must confirm before submitting the appeal.'
        })
        return
    }

    if (!form.appeal_reason) {
        $q.notify({
            type: 'negative',
            message: 'Appeal reason must be at least 20 characters.'
        })
        return
    }

    $q.dialog({
        title: 'Confirm Submission',
        message: 'Are you sure you want to submit the 2nd Appeal?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        const fd = new FormData()
        fd.append('appeal_reason', form.appeal_reason)

        if (form.attachment && form.attachment.length) {
            form.attachment.forEach((file, index) => {
                fd.append(`attachment[${index}]`, file)
            })
        }

        axios.post(route('information.second-appeal', props.info), fd, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then(response => {
                $q.notify({
                    type: 'positive',
                    message: response.data.message || '2nd Appeal submitted successfully.'
                })
                showAppealCard.value = false
                form.reset()
                confirmChecked.value = false
                router.get(route('information.show', props.info))
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
                        // Custom error message (like DAA not found)

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
