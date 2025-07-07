<template>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
        <form @submit.prevent="submitForm" class="bg-white max-w-3xl w-full p-8 rounded-md shadow-sm">
            <p class="text-2xl font-normal mb-3 leading-tight">
                New RTI Application / RTI hmanga dilna Form
            </p>
            <p class="mb-4 text-sm leading-snug">
                I zawh duh zawk thlang ang che / Please select where you want to ask question.
            </p>

            <!-- Department / Local Council -->
            <div class="mb-6 text-sm leading-tight">
                <label class="inline-flex items-center mr-4">
                    <input
                        type="checkbox"
                        :checked="form.toDepartment"
                        @change="selectDepartment"
                        class="form-checkbox text-blue-600"
                    />
                    <span class="ml-2 select-none">Department</span>
                </label>
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        :checked="form.toLocalCouncil"
                        @change="selectLocalCouncil"
                        class="form-checkbox text-blue-600"
                    />
                    <span class="ml-2 select-none">Local Council</span>
                </label>
            </div>

            <!-- Department Select -->
            <div v-if="form.toDepartment" class="mb-4">
                <label for="department" class="block mb-1 text-sm font-normal text-gray-900">Department</label>
                <select
                    id="department"
                    v-model="form.department"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                >
                    <option value="">Select Department</option>
                    <option>Administrative Training Institute</option>
                    <option>Education Department</option>
                    <option>Health Department</option>
                </select>
            </div>

            <!-- district Select -->
            <div v-if="form.toLocalCouncil" class="mb-4">
                <label for="department" class="block mb-1 text-sm font-normal text-gray-900">Department</label>
                <select
                    id="department"
                    v-model="form.department"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                >
                    <option value="">Select Department</option>
                    <option>Administrative Training Institute</option>
                    <option>Education Department</option>
                    <option>Health Department</option>
                </select>
            </div>

            <!-- Local Council Select -->
            <div v-if="form.toLocalCouncil" class="mb-4">
                <label for="department" class="block mb-1 text-sm font-normal text-gray-900">Department</label>
                <select
                    id="department"
                    v-model="form.department"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                >
                    <option value="">Select Department</option>
                    <option>Administrative Training Institute</option>
                    <option>Education Department</option>
                    <option>Health Department</option>
                </select>
            </div>

            <!-- Question -->
            <div class="mb-4">
                <label for="question" class="block mb-1 text-sm font-normal text-gray-900">Question / Zawhna</label>
                <textarea
                    id="question"
                    v-model="form.question"
                    rows="5"
                    placeholder="Type your questions here"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 text-sm resize-y focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                ></textarea>
            </div>

            <!-- Attachment -->
            <div class="mb-6 text-sm text-gray-900">
                <label for="attachment" class="cursor-pointer">Attachment (Optional) (image/pdf)</label>
                <input
                    type="file"
                    id="attachment"
                    @change="handleAttachment"
                    accept="image/*,application/pdf"
                    class="ml-2 inline-block"
                />
                <span class="ml-2">{{ form.attachment?.name || 'No files selected.' }}</span>
            </div>

            <!-- BPL -->
            <div class="mb-4 text-sm leading-tight">
                <label class="inline-flex items-start">
                    <input type="checkbox" v-model="form.isBPL" class="form-checkbox mt-1 text-blue-600" />
                    <span class="ml-2 select-none">
                        Below Poverty Line (BPL) ka ni e / I belong to Below Poverty Line (BPL) community
                    </span>
                </label>
            </div>

            <!-- Rule 6(a) Info -->
            <div v-if="form.isBPL" class="mb-6 p-4 bg-gray-100 rounded-md text-gray-700 text-sm leading-relaxed">
                <p class="mb-1 font-normal">Mizoram RTI Rules, 2010, Rule 6(a)</p>
                <p class="text-gray-400 text-xs font-light">
                    Persons who are of Below Poverty Line as may be determined by the State Government of Mizoram for
                    <strong>provisions of information related to welfare of BPL.</strong>
                </p>
            </div>

            <!-- BPL Proof -->
            <div v-if="form.isBPL" class="mb-6 text-sm text-gray-900">
                <label for="bplproof" class="cursor-pointer">BPL ID Proof:</label>
                <input type="file" id="bplproof" @change="handleBPLProof" class="ml-2 inline-block" />
                <span class="ml-2">{{ form.bplProof?.name || 'No files selected.' }}</span>
            </div>

            <!-- Life or Liberty -->
            <div class="mb-4 text-sm leading-tight">
                <label class="inline-flex items-start">
                    <input type="checkbox" v-model="form.isLifeOrLiberty" class="form-checkbox mt-1 text-blue-600" />
                    <span class="ml-2 select-none">
                        If it concerns the life or liberty of a person / Mi nunna emaw, zalenna khawih chungchang
                    </span>
                </label>
            </div>

            <!-- RTI Act Section Info -->
            <div v-if="form.isLifeOrLiberty" class="mb-6 p-4 bg-gray-100 rounded-md text-gray-700 text-xs leading-relaxed">
                <p class="mb-1 font-semibold text-sm">Provisio of Section 7(1) of the RTI Act, 2005:</p>
                <p class="mb-2 text-gray-400 text-[10px] font-light">
                    (1) Subject to the proviso to sub-section (2) of section 5 or the proviso to sub-section (3) of section 6, the
                    Central Public Information Officer or State Public Information Officer, as the case may be on receipt of a
                    request under section 6 shall, as expeditiously as possible, and in any case within thirty days of the
                    receipt of the request, either provide the information on payment of such fee as may be prescribed or reject
                    the request for any of the reasons specified in sections 8 and 9: Provided that where the information sought
                    for concerns the life or liberty of a person, the same shall be provided within forty-eight hours of the
                    receipt of the request.
                </p>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="form.understandsConsequences" class="form-checkbox text-gray-900" />
                    <span class="ml-2 font-normal text-sm select-none">I understand the consequences</span>
                </label>
            </div>

            <!-- Submit -->

            <button v-if="form.isBPL && form.bplProof"
                type="submit"
                class="w-full bg-sky-400 hover:bg-sky-500 text-white font-bold text-lg rounded-md py-3 transition-colors duration-200"
            >
                Submit
            </button>

            <button v-else
                type="submit"
                class="w-full bg-sky-400 hover:bg-sky-500 text-white font-bold text-lg rounded-md py-3 transition-colors duration-200"
            >
                Make Payment
            </button>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    toDepartment: true,
    toLocalCouncil: false,
    department: '',
    question: '',
    attachment: null,
    isBPL: false,
    bplProof: null,
    isLifeOrLiberty: false,
    understandsConsequences: false,
})

const selectDepartment = () => {
    form.toDepartment = true
    form.toLocalCouncil = false
}

const selectLocalCouncil = () => {
    form.toLocalCouncil = true
    form.toDepartment = false
}

const handleAttachment = (e) => {
    form.attachment = e.target.files[0]
}

const handleBPLProof = (e) => {
    form.bplProof = e.target.files[0]
}

const submitForm = () => {
    form.post(route('rti.store'), {
        forceFormData: true,
        onStart: () => {
            // Optional: show loading
        },
        onSuccess: () => {
            // Optional: reset form or redirect
            form.reset()
        },
        onError: (errors) => {
            // Optional: show error
            console.error(errors)
        }
    })
}
</script>

