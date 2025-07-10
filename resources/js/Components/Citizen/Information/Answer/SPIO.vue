<template>
    <!-- Answer-->
    <q-card>
        <q-card-section>
            <div class="text-h6 text-purple-600">Answer</div>
        </q-card-section>

        <q-card-section>
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


            <!-- Attachment download block -->
            <div class="q-mt-md" v-if="info.spio_answer_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.spio_answer_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />

                    <template v-if="!info.paid_attachments || info.paid_attachments.payment_status === 'Paid'">
                        <!-- Free or paid => allow download -->
                        <a
                            :href="`/storage/files/${file.trim()}`"
                            class="text-blue-600 underline"
                            target="_blank"
                            download
                        >
                            {{ file.trim() }}
                        </a>
                    </template>

                    <template v-else>
                        <!-- Paid but not yet paid => restrict -->
                        <span class="text-gray-600">{{ file.trim() }}</span>
                    </template>
                </div>
            </div>

            <!-- Show Pay Now section only if attachment is paid and not yet paid -->
            <div
                class="q-pa-md row items-center no-wrap q-gutter-md"
                v-if="info.paid_attachments && info.paid_attachments.payment_status !== 'Paid'"
            >
                <div class="col-auto">
                    <q-btn
                        label="PAY NOW"
                        color="primary"
                        text-color="white"
                        unelevated
                        class="text-sm font-normal q-px-md q-py-sm"
                        @click="payNow"
                    />
                </div>

                <div class="col text-base text-grey-9">
                    <strong class="text-weight-bold">₹{{ info.paid_attachments.amount }}</strong>
                    must be PAID to view exclusive documents
                    <div class="text-sm text-grey-8">
                        (Hemi zat hi document thil tel en nan pek belh a ngai.)
                    </div>
                </div>
            </div>

            <q-separator class="q-my-md" />

            <template v-if="!info.first_appeal_citizen_question">
                <p class="text-subtitle2">Department chhanna hi i lungawi lo em?</p>

                <q-btn
                    color="orange"
                    label="Do you want to apply for 1st Appeal?"
                    @click="applyFirstAppeal"
                />
            </template>
        </q-card-section>
    </q-card>
</template>

<script setup>

import {usePage} from "@inertiajs/vue3";
import {computed, onMounted} from "vue";
import axios from "axios";
import {useQuasar} from "quasar";
const page = usePage()
const user = computed(() => page.props.auth.user)

const props = defineProps(['info']);
const $q = useQuasar()


const applyFirstAppeal = () => {
    console.log('Apply for 1st Appeal')
}

const payNow=e=>{
    $q.dialog({
        title:'Confirmation',
        message:'Do you want to submit the application?',
        ok:'Yes',
        cancel:'No'
    })
        .onOk(()=>{
            $q.loading.show({
                boxClass: 'bg-grey-2 text-grey-9',
                spinnerColor: 'primary', message: ' Submitting...'
            });
            axios.post(route('information.pay-attachment', props.info.paid_attachments)).then(res=>{
                const {order_id,amount,currency, receipt,key} = res.data;
                initRazorpay({order_id,amount,currency,receipt,key})
            }).catch(err=>{
                console.log(err.response.data)
                $q.notify({type:'negative',message:err?.response?.data?.message || err.toString()});
                if (!!err?.response?.data?.errors) {
                    form.errors = err.response.data.errors;
                }
            }).finally(()=>$q.loading.hide());
        })
}
const initRazorpay = data => {
    if (!window.Razorpay) {
        alert('Razorpay SDK not loaded yet. Please try again.')
        return
    }

    try {
        const options = {
            key: data.key,
            amount: data.amount,
            currency: data.currency,
            name: 'MIC Mizoram',
            description: 'RTI Attachment Payment',
            order_id: data.order_id,
            callback_url : route('callback.attachment'),
            // handler: function (response) {
            //     // This runs after successful payment
            //     console.log('Payment successful', response)
            //     alert('Payment successful: ' + response.razorpay_payment_id)
            // },
            prefill: {
                name: user.value.name,
                email: user.value.email,
                contact: user.value.contact,
            },
            theme: {
                color: '#3399cc'
            }
        }

        const rzp = new window.Razorpay(options)
        rzp.open()
    } catch (err) {
        console.error('Payment initiation failed', err)
    }
}
onMounted(() => {
    const scriptElement = document.createElement('script')
    scriptElement.setAttribute('src', 'https://checkout.razorpay.com/v1/checkout.js')
    document.head.appendChild(scriptElement)
})
</script>


<style scoped>

</style>
