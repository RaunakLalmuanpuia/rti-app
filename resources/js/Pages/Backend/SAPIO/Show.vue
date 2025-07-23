<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail :info="info" />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">

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

                <Comment v-else :info="info" />

                <Answer v-if="info.spio_answer" :info="info" class="mt-4" />
            </div>
        </div>
    </q-page>
</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";

import Detail from "../../../Components/Information/Detail.vue";
import Comment from "../../../Components/Information/Comment.vue";
import Answer from "../../../Components/Information/Answer.vue";



defineOptions({ layout: BackendLayout });

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
