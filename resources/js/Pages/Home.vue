<template>
    <div class="max-w-xl mx-auto px-4 py-10 bg-blue-5">
        <h1 class="text-center text-4xl font-bold text-white mb-4">RTI ONLINE</h1>
        <h4 v-if="!isMobile" class="text-center text-white text-lg font-light mb-6">Login</h4>

        <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
                <label for="email" class="block text-white mb-1">Email/Phone</label>
                <input
                    id="email"
                    v-model="form.login"
                    type="text"
                    required
                    autofocus
                    class="w-full px-4 py-2 rounded bg-white text-black border focus:outline-none focus:ring-2 focus:ring-cyan-400"
                />
                <p v-if="form.errors.login" class="text-red-500 text-sm mt-1">{{ form.errors.login }}</p>
            </div>

            <div>
                <label for="password" class="block text-white mb-1">Password</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    class="w-full px-4 py-2 rounded bg-white text-black border focus:outline-none focus:ring-2 focus:ring-cyan-400"
                />
                <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
            </div>

            <div class="flex justify-between items-center">
                <label class="inline-flex items-center text-white">
                    <input type="checkbox" v-model="form.remember" class="form-checkbox text-cyan-500 mr-2" />
                    Remember Me
                </label>
                <a :href="route('login.forgot')" class="text-cyan-400 hover:underline text-sm">Forgot your password?</a>
            </div>

            <div class="text-center">
                <button
                    :disabled="form.processing"
                    type="submit"
                    class="w-full bg-cyan-400 text-white font-semibold py-2 rounded hover:bg-cyan-500 transition"
                >
                  <span v-if="form.processing" class="flex justify-center items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Logging in...
                  </span>
                    <span v-else>LOG IN</span>
                </button>
            </div>
        </form>

        <h3 class="text-white text-xl font-bold text-center mt-10">Register na hmun</h3>

        <div class="mt-4">
            <a
                :href="route('register.create')"
                class="block bg-teal-300 text-black text-center py-4 px-6 rounded mb-4 hover:bg-teal-400 transition"
            >
                <div class="font-bold">Mipui tan/ Citizen</div>
                <div>RTI a zawhna nei te tan a in register na hmun a ni</div>
            </a>

            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                <a
                    href="/localcouncilregister"
                    class="flex-1 bg-white text-black p-4 rounded hover:ring-2 hover:ring-teal-300 transition"
                >
                    <div class="text-pink-600 font-bold">Local Council</div>
                    <div>AMC leh LMC area chhunga Local Council te register-na hmun</div>
                </a>
                <a
                    href="/staffregister"
                    class="flex-1 bg-white text-black p-4 rounded hover:ring-2 hover:ring-blue-400 transition"
                >
                    <div class="text-indigo-700 font-bold">Government Official</div>
                    <div>
                        RTI chhangtu te <span class="font-bold">(SPIO, SAPIO leh DAA)</span> te register-na tur hmun
                    </div>
                </a>
            </div>
        </div>

        <div class="flex justify-center space-x-4 mt-10">
            <a href="https://play.google.com/store/apps/details?id=com.msegs.rti_apps">
                <img src="/images/playstore1.png" alt="Playstore" class="h-12" />
            </a>
            <a href="https://apps.apple.com/in/app/rti-online-mizoram/id1631322103">
                <img src="/images/appstore.png" alt="App Store" class="h-12" />
            </a>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'



const isMobile = ref(false)

const form = useForm({
    login: '',
    password: '',
    remember: false,
})
const handleSubmit = () => {
    form.post(route("login.store"));
};


onMounted(() => {
    isMobile.value = /Mobi|Android/i.test(navigator.userAgent)
})
</script>
