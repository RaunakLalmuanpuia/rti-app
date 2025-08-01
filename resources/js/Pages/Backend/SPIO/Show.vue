<template>
    <q-page class="q-pa-md">
        <div class="row q-col-gutter-md">
            <!-- Detail Section -->
            <div class="col-12 col-md-6">
                <Detail :info="info" />
            </div>

            <!-- Answer Section -->
            <div class="col-12 col-md-6">

                <!-- SAPIO Comment-->
                <Comment :info="info" />
                <!--Transfer-->
                <q-card class="mt-4" v-if="!info.spio_answer && info.information_status !== 11 && showTransferCard">
                    <q-card-section class="row items-center justify-between">
                        <button @click="openTransferDialog"
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

                <!--  Answer function -->
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
                      { label: 'Free', value: 1 },
                      { label: 'Paid', value: 0 }
                    ]"
                                label="Is the document free or paid?"
                                emit-value
                                map-options
                                outlined
                                dense
                            />
                        </div>

                        <!-- Show input for amount if 'Paid' is selected -->
                        <div v-if="form.attachment?.length > 0 && form.is_free === 0" class="q-mt-md">
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

                <!--  Answer-->
                <Answer v-if="info.spio_answer" class="mt-4" :info="info"/>

<!--                first appeal question-->
                <FirstAppealQuestion v-if="info.first_appeal_citizen_question" :info="info" class="mt-4" />
<!--                first appeal answer-->
                <FirstAppealAnswer v-if="info.first_appeal_daa_answer" :info="info" class="mt-4"/>
            </div>
        </div>


        <q-dialog v-model="transferDialog">
            <q-card
                class="q-pa-md"
                style="width: 600px; max-width: 90vw; margin-top: -100px;"
            >
                <q-card-section>
                    <div class="text-lg font-semibold">Transfer Application</div>
                </q-card-section>

                <q-card-section>
                    <q-input
                        v-model="transferRemark"
                        label="Transfer Remark"
                        type="textarea"
                        outlined
                        dense
                    />

                    <!--                <q-select-->
                    <!--                    filled-->
                    <!--                    v-model="selectedDepartment"-->
                    <!--                    :options="departmentOptions"-->
                    <!--                    @filter="searchDepartments"-->
                    <!--                    :loading="loadingDepartments"-->
                    <!--                    use-input-->
                    <!--                    input-debounce="300"-->
                    <!--                    map-options-->
                    <!--                    emit-value-->
                    <!--                    option-value="id"-->
                    <!--                    option-label="name"-->
                    <!--                    label="Search Department"-->
                    <!--                    clearable-->
                    <!--                />-->

                    <q-select
                        v-model="selectedDepartment"
                        :options="departmentOptions"
                        @filter="searchDepartments"
                        :loading="loadingDepartments"
                        label="Select Department"
                        emit-value
                        use-input
                        input-debounce="300"
                        map-options
                        option-value="id"
                        option-label="name"
                        outlined
                        dense
                        class="mt-4"
                        clearable
                    />

                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="grey" @click="closeTransferDialog" />
                    <q-btn flat label="Confirm" color="primary" @click="submitTransfer" />
                </q-card-actions>
            </q-card>
        </q-dialog>

    </q-page>

</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";

import Detail from "../../../Components/Information/Detail.vue";
import Comment from "../../../Components/Information/Comment.vue";
import Answer from "../../../Components/Information/Answer.vue";
import FirstAppealQuestion from "../../../Components/Information/FirstAppealQuestion.vue";
import FirstAppealAnswer from "../../../Components/Information/FirstAppealAnswer.vue";


defineOptions({ layout: BackendLayout });

import {useForm, router} from "@inertiajs/vue3";
import {useQuasar} from "quasar";
import { ref, computed } from 'vue'
import axios from "axios";
const $q = useQuasar()
const props = defineProps(['info']);

const form = useForm({
    answer: '',
    attachment: [],
    is_free: 1,
    attachment_price:'',
})

const showTransferCard = computed(() => {
    if (!props.info.spio_in) return false;

    const spioDate = new Date(props.info.spio_in);
    const now = new Date();

    const diffInMs = now - spioDate;
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

    return diffInDays <= 15;
});

const transferDialog = ref(false)
const transferRemark = ref('')

const departmentOptions = ref([])
const loadingDepartments = ref(false)

const selectedDepartment = ref(null)


const searchDepartments = async (val, update, abort) => {
    if (!val || val.length < 2) {
        update(() => {
            departmentOptions.value = []
        })
        return
    }
    loadingDepartments.value = true

    const url = route('information.search-department') // e.g. `/departments/search`

    axios.get(url, { params: { search: val } })
        .then(res => {
            update(() => {
                departmentOptions.value = res.data
            })
        })
        .catch(err => {
            $q.notify({
                type: 'negative',
                message: err.response?.data?.message || 'Failed to fetch departments',
            })
        })
        .finally(() => {

            loadingDepartments.value = false
        })
}


const openTransferDialog = () => {
    transferDialog.value = true
}

const closeTransferDialog = () => {
    transferDialog.value = false
    transferRemark.value = ''
    selectedDepartment.value = null
}

const submitTransfer = () => {
    if (!transferRemark.value || !selectedDepartment.value) {
        $q.notify({
            color: 'negative',
            message: 'Please fill all required fields.',
        })
        return
    }
    $q.dialog({
        title: 'Confirm Submission',
        message: 'Are you sure you want to transfer the application?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        const fd = new FormData()
        fd.append('remark', transferRemark.value)
        fd.append('department_id', selectedDepartment.value)

        axios.post(route('spio.information.transfer', props.info), fd)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Application transfer successfully.'
                })
                transferDialog.value = false
                transferRemark.value = ''
                selectedDepartment.value = null
                router.get(route('spio.information.index'))
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
        const fd = new FormData()
        fd.append('answer', form.answer)

        if (form.attachment && form.attachment.length) {
            form.attachment.forEach((file, index) => {
                fd.append(`attachment[${index}]`, file)
            })

            fd.append('is_free', form.is_free)
            if (form.is_free === 0) {
                fd.append('attachment_price', form.attachment_price)
            }
        }

        axios.post(route('spio.information.store', props.info), fd)
            .then(() => {
                $q.notify({
                    type: 'positive',
                    message: 'Answer submitted successfully.'
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
