<template>
    <div class="q-pa-lg flex flex-center">
        <q-form @submit.prevent="submitForm" class="bg-white q-pa-xl q-ma-md rounded-borders shadow-2 full-width" style="max-width: 700px">
            <div class="text-h6 q-mb-sm">New RTI Application / RTI hmanga dilna Form</div>
            <div class="text-body2 q-mb-md">
                I zawh duh zawk thlang ang che / Please select where you want to ask question.
            </div>

            <!-- Department / Local Council -->
            <div class="q-gutter-sm row items-center q-mb-md">
                <q-checkbox
                    :model-value="form.toDepartment"
                    @click="handleDepartmentToggle"
                    label="Department"
                />
                <q-checkbox
                    :model-value="form.toLocalCouncil"
                    @click="handleLocalCouncilToggle"
                    label="Local Council"
                />
            </div>

            <!-- Department Select -->
            <div v-if="form.toDepartment" class="q-mb-md">
                <q-select
                    filled
                    v-model="form.department"
                    :options="departmentOptions"
                    @filter="searchDepartments"
                    :loading="loadingDepartments"
                    use-input
                    input-debounce="300"
                    map-options
                    emit-value
                    option-value="id"
                    option-label="name"
                    label="Search Department"
                    clearable
                />
            </div>

            <!-- District Select -->
            <div v-if="form.toLocalCouncil" class="q-mb-md">
                <q-select
                    filled
                    v-model="form.district"
                    :options="['Aizawl', 'Lunglei']"
                    label="Select District"
                    clearable
                    class="q-mb-md"
                />

            </div>

            <!-- Local Council Select -->
            <div v-if="form.toLocalCouncil" class="q-mb-md">
                <q-select
                    v-if="form.toLocalCouncil"
                    filled
                    v-model="form.localCouncil"
                    :options="localCouncilOptions"
                    label="Select Local Council"
                    option-label="label"
                    option-value="value"
                    emit-value
                    map-options
                    clearable
                    class="q-mb-md"
                />
            </div>

            <!-- Question -->
            <div class="q-mb-md">
                <q-input
                    filled
                    v-model="form.question"
                    type="textarea"
                    rows="6"
                    label="Question / Zawhna"
                    placeholder="Type your questions here"
                />
            </div>

            <!-- Attachment -->
            <div class="q-mb-md">
                <q-file
                    v-model="form.attachment"
                    label="Attachment (Optional) (image/pdf)"
                    accept=".jpg, .jpeg, .png, .gif, .pdf"
                    use-chips
                    multiple
                    counter
                    @update:model-value="handleAttachment"
                    outlined
                    filled
                />
            </div>

            <!-- BPL Checkbox -->
            <q-checkbox
                v-model="form.isBPL"
                label="Below Poverty Line (BPL) ka ni e / I belong to Below Poverty Line (BPL) community"
                class="q-mb-md"
            />

            <!-- BPL Info -->
            <div v-if="form.isBPL" class="q-pa-md bg-grey-2 rounded-borders text-body2 text-grey-8 q-mb-md">
                <p class="q-mb-xs"><strong>Mizoram RTI Rules, 2010, Rule 6(a)</strong></p>
                <p class="text-caption text-grey-6">
                    Persons who are of Below Poverty Line as may be determined by the State Government of Mizoram for
                    <strong>provisions of information related to welfare of BPL.</strong>
                </p>
            </div>

            <!-- BPL Proof Upload -->
            <div v-if="form.isBPL" class="q-mb-md">
                <q-file
                    v-model="form.bplProof"
                    label="Upload BPL Proof"
                    accept=".jpg, .jpeg, .png, .gif, .pdf"
                    @update:model-value="handleBPLProof"
                    filled
                    outlined
                    clearable
                />
            </div>

            <!-- Life or Liberty -->
            <q-checkbox
                v-model="form.isLifeOrLiberty"
                label="If it concerns the life or liberty of a person / Mi nunna emaw, zalenna khawih chungchang"
                class="q-mb-md"
            />

            <!-- RTI Act Section Info -->
            <div v-if="form.isLifeOrLiberty" class="q-pa-md bg-grey-2 rounded-borders text-caption text-grey-8 q-mb-md">
                <p class="text-weight-medium">Provisio of Section 7(1) of the RTI Act, 2005:</p>
                <p class="text-grey-6 q-mb-sm">
                    Where the information sought for concerns the life or liberty of a person, the same shall be provided within
                    forty-eight hours of the receipt of the request.
                </p>
                <q-checkbox
                    v-model="form.understandsConsequences"
                    label="I understand the consequences"
                    dense
                />
            </div>

            <!-- Submit Button -->
            <q-btn
                type="submit"
                :label="form.isBPL && form.bplProof ? 'Submit' : 'Make Payment'"
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
import {useForm, router, usePage} from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import {ref, watch, onMounted, computed} from 'vue'
import axios from "axios";

defineOptions({ layout: BackendLayout });

const page = usePage()
const user = computed(() => page.props.auth.user)

const props = defineProps({
    razorpayKey: String
})

const $q = useQuasar()

const form = useForm({
    toDepartment: false,
    toLocalCouncil: false,
    department: '',
    district:'',
    localCouncil:'',
    question: '',
    attachment: [],
    isBPL: false,
    bplProof: null,
    isLifeOrLiberty: false,
    understandsConsequences: false,
})

const handleDepartmentToggle = () => {
    if (!form.toDepartment) {
        form.toDepartment = true;
        form.toLocalCouncil = false;
        form.district = '';
        form.localCouncil = '';
    } else {
        form.toDepartment = false;
        form.department = '';
    }
};

const handleLocalCouncilToggle = () => {
    if (!form.toLocalCouncil) {
        form.toLocalCouncil = true;
        form.toDepartment = false;
        form.department = '';
    } else {
        form.toLocalCouncil = false;
        form.district = '';
        form.localCouncil = '';
    }
};


const departmentOptions = ref([])
const loadingDepartments = ref(false)

const localCouncilOptions = ref([])

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
const fetchLocalCouncils = async (district) => {
    localCouncilOptions.value = []
    if (!district) return

    try {
        const res = await axios.get(route('information.get-local_council'), { params: { district: district } })
        localCouncilOptions.value = res.data.map((c) => ({
            label: c.name,
            value: c.id,
        }))
    } catch (e) {
        console.error('Failed to fetch local councils', e)
    }
}
// Watch for district changes
watch(() => form.district, (newDistrict) => {
    form.localCouncil = ''
    fetchLocalCouncils(newDistrict)
})
// This handles updates to the file input
const handleAttachment = (files) => {
    // Directly assign files (Quasar handles `v-model` as array when multiple is enabled)
    form.attachment = Array.isArray(files) ? files : [files]
}
const handleBPLProof = (file) => {
    form.bplProof = file
}
const submitForm=e=>{
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

            const formData = new FormData()
            formData.append('department', form.department)
            formData.append('local_council', form.localCouncil)
            formData.append('question', form.question)
            formData.append('life_or_death', form.isLifeOrLiberty)

            // Append each attachment
            form.attachment.forEach((file, index) => {
                formData.append(`attachment[]`, file) // or `attachments[]` if your backend expects that
            })

            axios.post(route('information.store'),formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res=>{
                const {order_id,amount,currency, receipt} = res.data;
                initRazorpay({order_id,amount,currency,receipt})
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
            key: props.razorpayKey,
            amount: data.amount,
            currency: data.currency,
            name: 'MIC Mizoram',
            description: 'RTI Payment',
            order_id: data.order_id,
            callback_url : route('callback.information'),
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

