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

    <q-card class="mt-4" v-if="info.spio_answer">
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

<!--    Transfer-->
    <q-card class="mt-4" v-if="!info.spio_answer && info.information_status !== 11">
        <q-card-section class="row items-center justify-between">
            <button
                class="bg-[#cc7a1a] text-white text-base font-normal rounded px-4 py-2 mb-4"
            >
                Transfer
            </button>
            <p class="text-base text-gray-900 max-w-2xl">
                <strong>Note:</strong> Transfer should be made in no case later than
                <strong>five days</strong> from the date of receipt of the application.
                [Section 6(3)(ii) of the RTI Act, 2005 ]
            </p>
            <p
                class="text-sm italic text-gray-600 max-w-2xl mt-2"
            >
                "the subject matter of which is more closely connected with the functions of
                another public authority, the public authority, to which such application is
                made, shall transfer the application or such part of it as may be appropriate
                to that other public authority and inform the applicant immediately about such
                transfer: Provided that the transfer of an application pursuant to this
                sub-section shall be made as soon as practicable but in no case later than
                five days from the date of receipt of the application."
            </p>
        </q-card-section>

    </q-card>


<!--    SPIO ANswer-->
    <q-card class="mt-4" v-if="!info.spio_answer">
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-purple-600">Answer</div>
        </q-card-section>

        <q-card-section>
            <!-- Answer Input -->
            <q-input
                v-model="form.answer"
                type="textarea"
                placeholder="Type your Answer here"
                autogrow
                outlined
                dense
                class="text-base"
                :input-style="{ minHeight: '150px' }"
            />

            <!-- Note -->
            <div class="q-mt-md text-gray-900 text-base font-bold">
                <span class="underline">Note:</span> Maximum eight (8) files can be uploaded. If not, please merge the files.
            </div>

            <!-- File Upload -->
            <q-file
                filled
                v-model="form.attachment"
                label="Attach Document (optional)"
                accept=".jpg, .jpeg, .png, .pdf"
                class="q-mt-md"
                multiple
            />

            <!-- Show dropdown if file is uploaded -->
            <div v-if="form.attachment?.length > 0" class="q-mt-md">
                <q-select
                    v-model="form.is_free"
                    :options="[
                      { label: 'Free', value: true },
                      { label: 'Paid', value: false }
                    ]"
                    label="Is the document free or paid?"
                    emit-value
                    map-options
                    outlined
                    dense
                />
            </div>

            <!-- Show input for amount if 'Paid' is selected -->
            <div v-if="form.attachment?.length > 0 && form.is_free === false" class="q-mt-md">
                <q-input
                    v-model="form.attachment_price"
                    label="Attachment Price (in ₹)"
                    type="number"
                    outlined
                    dense
                    :rules="[
                      val => !!val || 'Amount is required',
                      val => val >= 0 || 'Amount must be positive'
                    ]"
                />
            </div>

            <!-- Submit Button -->
            <q-btn
                type="submit"
                label="Answer Applicant"
                color="orange-7"
                class="q-mt-lg"
                unelevated
                no-caps
                size="lg"
                @click="submitAnswer"
            />
        </q-card-section>
    </q-card>



</template>


<script setup>
import {useForm, router} from "@inertiajs/vue3";
import {useQuasar} from "quasar";
import { ref } from 'vue'
const $q = useQuasar()
const props = defineProps(['info']);

const form = useForm({
    answer: '',
    attachment: [],
    is_free: true,
    attachment_price:'',
})

const submitAnswer = () => {

    if (!form.answer) {
        $q.notify({
            type: 'warning',
            message: 'Please enter your answer.'
        })
        return
    }

    $q.dialog({
        title: 'Confirm Submission',
        message: 'Are you sure you want to submit the answer?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        axios.post(route('spio.information.store', props.info), form)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Comment submitted successfully.'
                })
                form.reset()
                router.get(route('spio.information.show', props.info))
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



