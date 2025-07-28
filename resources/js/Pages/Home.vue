<template>

    <q-page class="container">
<!--        Login part-->
        <div class=" bg-white q-mt-xl">
            <div class="row">
                <!-- IMAGE FIRST -->
                <div v-if="$q.screen.gt.sm" class="col-xs-12 col-sm-6">
                    <div style="overflow: hidden; height: 98%; border-radius: 8px;">
                        <q-img
                            src="images/login.png"
                            fit="cover"
                            style="margin-top: -10px; margin-bottom: -10px; height: calc(100% + 20px);"
                        />
                    </div>
                </div>
                <!-- LOGIN FORM + REGISTER SECTION -->
                <div class="col-xs-12 col-sm-6">
                    <div class="column q-gutter-xs register-card justify-center items-center">
                        <div v-if="!!$page.props.auth?.user" class="column q-gutter-sm">
                            <p class="text-dark">You are logged in</p>
                            <q-btn @click="$inertia.get(route('dashboard'))" flat label="Go to dashboard" />
                        </div>
                        <q-form
                            style="max-width: 450px; width: 100%"
                            v-else
                            class="column"
                            @submit.prevent="handleSubmit"
                        >
                            <p class="login-title">Login</p>

                            <label class="text-grey-8 text-weight-medium" for="email">Email/Phone</label>
                            <q-input
                                id="email"
                                v-model="form.login"
                                dense
                                outlined
                                hide-bottom-space
                                no-error-icon
                                :error="!!form.errors?.login"
                                :error-message="form.errors?.login"
                                :rules="[val => !!val || 'Email/Phone is required']"
                            />

                            <label class="text-grey-8 text-weight-medium" for="password">Password</label>
                            <q-input
                                :type="state.visiblePassword ? 'text' : 'password'"
                                v-model="form.password"
                                outlined
                                dense
                                no-error-icon
                                hide-bottom-space
                                :error="!!form.errors?.password"
                                :error-message="form.errors?.password"
                                :rules="[val => !!val || 'Password is required']"
                            >
                                <template #append>
                                    <q-icon
                                        @click="state.visiblePassword = !state.visiblePassword"
                                        :name="state.visiblePassword ? 'visibility' : 'visibility_off'"
                                    />
                                </template>
                            </q-input>

                            <div class="flex justify-end">
                                <a
                                    :href="route('login.forgot')"
                                    class="text-accent text-weight-medium cursor-pointer text-no-underline"
                                >
                                    Forgot Password ?
                                </a>
                            </div>

                            <div class="flex">
                                <q-btn
                                    class="sized-btn"
                                    color="blue"
                                    type="submit"
                                    rounded
                                    label="Login"
                                    no-caps
                                />
                            </div>
                        </q-form>
                    </div>
                    <!-- REGISTER SECTION -->
                    <p class="login-title text-center">Register</p>
                    <div class=" flex items-center justify-center column">
                        <div style="max-width: 450px; width: 100%">  <!-- Increased width here -->
                            <a
                                :href="route('register.create-citizen')"
                                class="block bg-teal-300 text-black text-center py-4 px-6 rounded mb-4 hover:bg-teal-400 transition"
                            >
                                <div class="font-bold">Mipui tan/ Citizen</div>
                                <div>RTI a zawhna nei te tan a in register na hmun a ni</div>
                            </a>

                            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                                <a
                                    :href="route('register.create-official')"
                                    class="flex-1 bg-white border border-black text-black p-4 rounded hover:ring-2 hover:ring-blue-400 transition"
                                >
                                    <div class="text-indigo-700 font-bold">Government Official</div>
                                    <div>
                                        RTI chhangtu te <span class="font-bold">(SPIO, SAPIO leh DAA)</span> te register-na tur hmun
                                    </div>
                                </a>
                                <a
                                    :href="route('register.create-local-council')"
                                    class="flex-1 bg-white border border-black text-black p-4 rounded hover:ring-2 hover:ring-blue-400 transition"
                                >
                                    <div class="text-pink-600 font-bold">Local Council</div>
                                    <div>AMC leh LMC area chhunga Local Council te register-na hmun</div>
                                </a>
                            </div>
                        </div>

                        <!-- APP STORE LINKS -->
                        <div class="flex justify-center space-x-4 mt-10">
                            <a href="https://play.google.com/store/apps/details?id=com.msegs.rti_apps">
                                <img src="/images/playstore1.png" alt="Playstore" class="h-12" />
                            </a>
                            <a href="https://apps.apple.com/in/app/rti-online-mizoram/id1631322103">
                                <img src="/images/appstore.png" alt="App Store" class="h-12" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <div id="support" class="support">
            <div class="flex justify-center items-center">
                <div class="text-center">
                    <div class="support-title">Support</div>
                    <div class="home-line"/>
                </div>

            </div>
        </div>
        <div class="flex justify-center items-center">
            <div class="p-5 text-center">
                <div class="text-xl font-semibold mb-3">
                    Available Monday - Friday between 11:00AM - 3:00PM
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-4 text-base">
                    <div>Mobile: 6033206128</div>
                    <div>Landline: 0389-2334825</div>
                </div>
            </div>
        </div>

    </q-page>




</template>

<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {ref, onMounted, reactive} from 'vue'
import { useForm } from '@inertiajs/vue3'
defineOptions({layout:FrontendLayout})


const isMobile = ref(false)

const form = useForm({
    login: '',
    password: '',
    remember: false,
})
const handleSubmit = () => {
    form.post(route("login.store"));
};

const state=reactive({
    visiblePassword:false
})
onMounted(() => {
    isMobile.value = /Mobi|Android/i.test(navigator.userAgent)
})
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
.support{
    color: #1ED9E8;
    font-size: 20px;
    font-weight: bold;
}
.support-title{

    color: #191C51;
    font-weight: bold;
    font-size: 35px;
}
.home-line{
    background-color: #1DEFFF;
    height:5px;
    width: 150px;
}

</style>
