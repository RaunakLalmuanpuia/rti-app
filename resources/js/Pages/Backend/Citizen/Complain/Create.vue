<template>
    <div class="q-pa-lg flex flex-center">
        <q-form @submit.prevent="submitForm" class="bg-white q-pa-xl q-ma-md rounded-borders shadow-2 full-width" style="max-width: 700px">
            <div class="text-h6 q-mb-sm">Grievances/Complaint to CIC</div>
            <div class="text-body2 q-mb-md">
                CIC hnenah complaint thehluhna
            </div>

            <!-- Question -->
            <div class="q-mb-md">
                <q-input
                    filled
                    v-model="form.complain"
                    type="textarea"
                    rows="6"
                    label="Question / Zawhna"
                    placeholder="Type your questions here"
                />
            </div>

            <!-- Attachment -->
            <div class="q-mb-md">
                <q-file
                    filled
                    v-model="form.attachment"
                    label="Attach Document (optional)"
                    accept=".jpg, .jpeg, .png, .pdf"
                    class="q-mt-md"
                    multiple
                />
            </div>

            <!-- Submit Button -->
            <q-btn
                type="submit"
                label="Submit Complain"
                color="primary"
                class="full-width"
                unelevated
                rounded
            />
        </q-form>
    </div>
</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";

import {router, useForm} from "@inertiajs/vue3";
import axios from "axios";
import {useQuasar} from "quasar";

defineOptions({ layout: BackendLayout });

const form = useForm({
    complain: '',
    attachment: [],
})

const $q = useQuasar()
const submitForm = () => {
    if (!form.complain) {
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
        fd.append('complain', form.complain)

        if (form.attachment && form.attachment.length) {
            form.attachment.forEach((file, index) => {
                fd.append(`attachment[${index}]`, file)
            })
        }
        axios.post(route('citizen.complain.store'), fd)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Complain submitted successfully.'
                })
                form.reset()
                router.get(route('dashboard.citizen'))
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
