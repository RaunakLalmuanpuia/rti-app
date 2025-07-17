<template>
    <q-card v-if="!info.aspio_answer && !info.spio_answer">
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-primary">Comment</div>
        </q-card-section>
        <q-card-section>
            <q-input
                filled
                v-model="form.comment"
                type="textarea"
                rows="6"
                label="Comment"
                placeholder="Type your comment here"
            />


            <q-separator class="q-my-md" />

            <q-btn
                color="orange"
                label="Comment"
                @click="submitComment"
            />

        </q-card-section>
    </q-card>

    <q-card v-else>
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
</template>


<script setup>
import {useForm, router} from "@inertiajs/vue3";
import {useQuasar} from "quasar";

const $q = useQuasar()
const props = defineProps(['info']);

const form = useForm({
    comment: '',
})
const submitComment = () => {

    if (!form.comment) {
        $q.notify({
            type: 'warning',
            message: 'Please enter your comment.'
        })
        return
    }

    $q.dialog({
        title: 'Confirm Submission',
        message: 'Are you sure you want to submit the comment?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        axios.post(route('sapio.information.store', props.info), form)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Comment submitted successfully.'
                })
                form.reset()
                router.get(route('sapio.information.show', props.info))
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
