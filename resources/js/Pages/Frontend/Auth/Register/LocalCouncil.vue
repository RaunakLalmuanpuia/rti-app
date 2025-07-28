<template>
    <div class="container bg-white  q-mt-xl">
        <div class="row ">
            <div v-if="$q.screen.gt.sm" class="col-xs-12 col-sm-6">
                <q-img v-if="!state.sentOtp" width="580" src="images/reg1.png"/>
                <q-img v-if="state.step==='otp' && state.sentOtp" width="580" src="images/reg2.png"/>
                <q-img v-if="state.step === 'register'" height="100%" width="580" src="images/reg3.png"/>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div v-if="!!$page.props.auth?.user" class="column q-gutter-sm">
                    <p class="text-dark">You are logged in</p>
                    <q-btn @click="$inertia.get(route('dashboard'))" flat label="Go to dashboard"/>
                </div>
                <div v-else class="column q-gutter-xs register-card justify-center items-center">
                    <div v-if="state.step === 'otp'">
                        <q-form v-if="!state.sentOtp" @submit.prevent="handleSendOtp" class="column" style="max-width: 420px">
                            <p class="login-title text-left">Mobile Verification</p>
                            <q-input
                                label="Phone Number"
                                v-model="otpForm.mobile"
                                mask="##########"
                                outlined
                                no-error-icon
                                :error="!!otpForm.errors?.mobile"
                                :error-message="otpForm.errors?.mobile?.toString()"
                                :rules="[val => !!val || 'Phone Number is required']"
                            />
                            <div class="flex q-mt-sm">
                                <q-btn class="sized-btn" color="blue" type="submit" rounded label="Send OTP" no-caps />
                            </div>
                        </q-form>

                        <q-form v-if="state.sentOtp" @submit="handleOtpConfirm" class="column " style="max-width: 420px">
                            <p class="login-title text-left">Verify OTP</p>
                            <q-input
                                label="Enter OTP"
                                v-model="otpForm.mobile_otp"
                                outlined
                                dense
                                mask="####"
                                no-error-icon
                                :error="!!otpForm.errors?.mobile_otp"
                                :error-message="otpForm.errors?.mobile_otp?.toString()"
                                :rules="[val => !!val || 'OTP is required']"
                            />
                            <div class="text-primary text-bold cursor-pointer q-mb-lg text-left" @click="handleSendOtp">Resend OTP</div>
                            <div class="flex">
                                <q-btn class="sized-btn" color="blue" type="submit" rounded label="Verify OTP" no-caps />
                            </div>
                        </q-form>
                    </div>

                    <q-form v-else-if="state.step === 'register'" style="max-width: 420px" @submit="handleRegister">
                        <div class="text-left">
                            <p class="login-title">Register</p>
                        </div>

                        <q-input label="Name" v-model="form.name" outlined no-error-icon :error="!!form.errors?.name"
                                 :error-message="form.errors?.name?.toString()"
                                 :rules="[val => !!val || 'Name is required']" />

                        <q-input label="Designation" v-model="form.designation" outlined no-error-icon :error="!!form.errors?.designation"
                                 :error-message="form.errors?.designation?.toString()"
                                 :rules="[val => !!val || 'Designation is required']" />

                        <q-input label="Email" v-model="form.email" outlined no-error-icon :error="!!form.errors?.email"
                                 :error-message="form.errors?.email?.toString()"
                                 :rules="[val => !!val || 'Email is required']" />

                        <q-input label="Password" v-model="form.password" :type="state.visiblePassword ? 'text' : 'password'"
                                 outlined no-error-icon :error="!!form.errors?.password"
                                 :error-message="form.errors?.password?.toString()"
                                 :rules="[val => !!val || 'Password is required']">
                            <template #append>
                                <q-icon @click="state.visiblePassword = !state.visiblePassword"
                                        :name="state.visiblePassword ? 'visibility' : 'visibility_off'" />
                            </template>
                        </q-input>

                        <q-input label="Confirm Password" :type="state.visiblePassword ? 'text' : 'password'"
                                 v-model="form.password_confirmation"
                                 outlined no-error-icon :error="!!form.errors?.password_confirmation"
                                 :error-message="form.errors?.password_confirmation?.toString()"
                                 :rules="[
             val => !!val || 'Confirm Password is required',
             val => val === form.password || 'Confirm password does not match password'
           ]" />

                        <!-- Contact (disabled and bound to otpForm.mobile) -->
                        <q-input label="Contact" v-model="otpForm.mobile" outlined no-error-icon disable
                                 :error="!!form.errors?.contact"
                                 :error-message="form.errors?.contact?.toString()"
                                 :rules="[val => !!val || 'Contact is required']" />

                        <q-input label="Address" v-model="form.address" outlined no-error-icon :error="!!form.errors?.address"
                                 :error-message="form.errors?.address?.toString()"
                                 :rules="[val => !!val || 'Address is required']" />

                        <!-- Role select -->
                        <q-select label="Role" v-model="form.role" outlined no-error-icon :error="!!form.errors?.role"
                                  :error-message="form.errors?.role?.toString()"
                                  :options="['SAPIO','SPIO','DAA']"
                                  :rules="[val => !!val || 'Role is required']" />

                        <!-- Department select - allow multiple when role is DAA -->
                        <q-select
                            :options="departmentOptions"
                            @filter="searchDepartments"
                            :loading="loadingDepartments"
                            emit-value
                            use-input
                            input-debounce="300"
                            map-options
                            option-value="id"
                            option-label="name"
                            label="Department"
                            clearable
                            v-model="form.department"
                            outlined
                            no-error-icon
                            :error="!!form.errors?.department"
                            :error-message="form.errors?.department?.toString()"
                            :rules="[val => !!val || 'Department is required']"
                            :multiple="form.role === 'DAA'"
                        />

                        <div class="flex q-mt-sm">
                            <q-btn class="sized-btn" color="blue" type="submit" rounded label="Register" no-caps />
                        </div>
                    </q-form>
                </div>


            </div>
        </div>

    </div>

</template>
<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

import {router, useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";
import {reactive, ref} from "vue";
import axios from "axios";
defineOptions({layout:FrontendLayout})

const q = useQuasar();

const state = reactive({
    step: 'otp', // 'otp' or 'register'
    sentOtp: false,
    visiblePassword: false,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    designation:'',
    address:'',
    contact:'',
    role:'',
    department:null,
});

const otpForm = useForm({
    mobile: '',
    mobile_otp: ''
});

const handleSendOtp = () => {
    q.loading.show({ message: 'Sending OTP...' });
    axios.post(route('register.send-otp'), { mobile: otpForm.mobile })
        .then(res => {
            if (res.data.status) {
                state.sentOtp = true;
                otpForm.setError({});
            }
        })
        .catch(err => {
            otpForm.setError(err.response?.data?.errors || {});
            q.notify({ type: 'negative', message: err.response?.data?.message || 'Error sending OTP' });
        })
        .finally(() => q.loading.hide());
};

const handleOtpConfirm = () => {
    q.loading.show({ message: 'Verifying OTP...' });
    axios.post(route('register.confirm-otp'), {
        mobile: otpForm.mobile,
        mobile_otp: otpForm.mobile_otp
    })
        .then(res => {
            if (res.data.status) {
                // Carry mobile over to form for registration
                state.step = 'register';
                form.setError({});
            }
        })
        .catch(err => {
            otpForm.setError(err.response?.data?.errors || {});
            q.notify({ type: 'negative', message: err.response?.data?.message || 'Invalid OTP' });
        })
        .finally(() => q.loading.hide());
};

const handleRegister = () => {
    q.loading.show({ message: 'Registering...' });
    form.contact = otpForm.mobile;
    axios.post(route('register.store'), form.data())
        .then(res => {
            if (res.data.status) {
                // Carry mobile over to form for registration
                form.setError({});
            }
        })
        .catch(err => {
            otpForm.setError(err.response?.data?.errors || {});
            q.notify({ type: 'negative', message: err.response?.data?.message || 'System Error' });
        })
        .finally(() => q.loading.hide());
};

const departmentOptions = ref([])
const loadingDepartments = ref(false)



const searchDepartments = async (val, update, abort) => {
    if (!val || val.length < 2) {
        update(() => {
            departmentOptions.value = []
        })
        return
    }
    loadingDepartments.value = true

    const url = route('register.search-department') // e.g. `/departments/search`

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
</script>
<style scoped>
.login-title{
    padding: 0;
    margin-left: 8px;
    color: #191c51;
    font-size: 28px;
    font-weight: bold;
}
.signup{
    font-family: Roboto,serif;
    font-size: 16px;
    font-weight: normal;
    color: #080808;
}
.register-card{
    padding: 32px;
    text-align: center;
}
@media (max-width: 599px) {
    .register-card{
        padding: 12px;
    }
}
</style>
