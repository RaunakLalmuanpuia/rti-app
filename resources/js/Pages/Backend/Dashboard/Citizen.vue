<template>
    <div class="max-w-full mx-6 my-6">
        <p class="text-2xl mb-2 font-normal">RTI Application List</p>

        <!-- Tabs / Navigation -->
        <div class="flex items-center space-x-4 mb-3">
            <button
                class="bg-purple-600 text-white px-4 py-2 rounded-sm text-sm font-normal"
                type="button"
            >
                RTI List
            </button>
            <a
                href="#"
                class="text-blue-600 text-sm font-normal hover:underline"
            >
                Complaint List
            </a>
        </div>

        <!-- Filters: Per Page & Search -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200 rounded-t-md px-2 py-1"
        >
            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <span class="text-sm">Show</span>
                <select
                    v-model="state.perPage"
                    @change="changePerPage"
                    class="border border-gray-300 rounded px-2 py-1 text-sm"
                >
                    <option v-for="n in [10, 25, 50, 100]" :key="n" :value="n">
                        {{ n }}
                    </option>
                </select>
                <span class="text-sm">entries</span>
            </div>

            <div class="flex items-center space-x-2">
                <label for="search" class="text-sm">Search:</label>
                <input
                    id="search"
                    v-model="state.search"
                    @input="handleSearch"
                    type="search"
                    placeholder="Search applicant, question, department"
                    class="border border-gray-300 rounded px-2 py-1 text-sm"
                />
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto border border-t-0 border-gray-200 rounded-b-md">
            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead>
                <tr class="bg-black text-white text-left">
                    <th class="border border-gray-300 font-semibold px-3 py-2">Applicant Name</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Question</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Department</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Submitted At</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Status</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Time since submission</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(info, index) in information.data"
                    :key="info.id"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                    @click="$inertia.get(route('information.show',info))"
                >
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ info.citizen_name }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ info.citizen_question }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ info.department?.name ?? info.local_council?.name  }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ formatDate(info.created_at) }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">
                      <span
                          class="text-[10px] font-semibold px-2 py-[2px] rounded"
                          :class="getStatusClass(info)"
                          v-html="getStatusLabel(info)"
                      ></span>
                    </td>
                    <td class="border border-gray-300 px-3 py-2 align-top d-none d-sm-table-cell">

                            <template v-if="getTimeStatus(info).image">
                                <img :src="getTimeStatus(info).image" class="inline-block mr-1" />
                            </template>
                            <template v-if="getTimeStatus(info).text">
                                {{ getTimeStatus(info).text }}
                            </template>

                    </td>
                </tr>
                <tr v-if="information.data.length === 0">
                    <td colspan="5" class="text-center py-3 text-gray-500">No records found.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border border-t-0 border-gray-200 rounded-b-md px-4 py-3 text-sm text-gray-700">
            <div>
                Showing {{ information.from }} to {{ information.to }} of {{ information.total }} entries
            </div>
            <div class="inline-flex items-center space-x-1 mt-2 sm:mt-0">
                <button
                    v-for="link in information.links"
                    :key="link.label"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    :disabled="!link.url"
                    :class="[
                        'px-3 py-1 rounded border text-sm',
                        link.active ? 'bg-gray-200 border-gray-300' : 'border-gray-300 hover:bg-gray-100',
                        !link.url ? 'text-gray-300 cursor-not-allowed' : 'text-gray-700'
                      ]"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
defineOptions({ layout: BackendLayout });
// Props from Laravel
const props = defineProps({
    information: Object,
    search: String,
    perPage: Number,
})

// Reactive state
const state = reactive({
    search: props.search || '',
    perPage: props.perPage || 10,
})

// Handle search input
let searchTimeout = null
const handleSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        router.get(route('dashboard.citizen'), {
            search: state.search,
            perPage: state.perPage,
        }, { preserveState: true, replace: true })
    }, 400)
}

// Handle entries per page
const changePerPage = () => {
    router.get(route('dashboard.citizen'), {
        search: state.search,
        perPage: state.perPage,
    }, { preserveState: true, replace: true })
}

// Pagination navigation
const goToPage = (url) => {
    if (url) {
        router.visit(url, {
            preserveState: true,
            replace: true
        })
    }
}

// Optional: Date formatting
const formatDate = (dateStr) => {
    const date = new Date(dateStr)
    return date.toLocaleString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: '2-digit',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    })
}

function getStatusLabel(info) {
    if (info.complain) return 'Complaint Registered'

    if (info.aspio_in && !info.spio_in) {
        return 'Under process by SAPIO'
    }

    if (info.spio_in && !info.spio_out) {
        return info.information_status == 11
            ? '30 days has passed'
            : 'Under process by SPIO'
    }

    if (info.spio_answer && !info.first_appeal_citizen_question && info.spio_out) {
        return info.information_status == 11
            ? '30 days has passed (SPIO)'
            : 'Answered by SPIO'
    }

    if (info.first_appeal_citizen_question && !info.first_appeal_daa_answer) {
        return 'Under process by DAA'
    }

    if (!info.second_appeal_citizen_question && info.first_appeal_daa_answer) {
        return info.information_status == 22
            ? '30 days has passed (DAA)'
            : 'Answered by DAA'
    }

    if (info.second_appeal_citizen_question && !info.second_appeal_cic_answer) {
        return 'Under process by CIC'
    }

    if (info.second_appeal_citizen_question && info.second_appeal_cic_answer) {
        return 'Answered by CIC'
    }

    if (!info.aspio_answer && !info.aspio_in && info.spio_in && !info.spio_out) {
        return 'Under process by SPIO'
    }

    if (!info.aspio_answer && !info.aspio_in && info.spio_in && info.spio_out) {
        return 'Answered by SPIO'
    }

    return 'Pending'
}

function getStatusClass(info) {
    const label = getStatusLabel(info).toLowerCase()

    if (label.includes('under process')) {
        return 'bg-red-100 text-red-600'
    }
    if (label.includes('answered')) {
        return 'bg-green-100 text-green-700'
    }
    if (label.includes('30 days')) {
        return 'bg-yellow-100 text-yellow-700'
    }
    if (label.includes('complaint')) {
        return 'bg-purple-100 text-purple-700'
    }
    return 'bg-gray-100 text-gray-700'
}

function getTimeStatus(info) {

    // console.log('Showing PENDING for info.id =', info.id)
    // console.log('aspio_in:', info.aspio_in)
    // console.log('aspio_answer:', info.aspio_answer)
    // console.log('spio_in:', info.spio_in)
    // console.log('spio_answer:', info.spio_answer)
    // console.log('first_appeal_citizen_question:', info.first_appeal_citizen_question)
    // console.log('first_appeal_daa_answer:', info.first_appeal_daa_answer)
    // console.log('second_appeal_citizen_question:', info.second_appeal_citizen_question)
    // console.log('second_appeal_cic_answer:', info.second_appeal_cic_answer)
    const now = new Date()

    const stages = [
        {
            field: 'aspio_in',
            answered: info.aspio_answer != null,
            condition: !info.aspio_answer && !info.spio_in && info.aspio_in,
            outputType: 'image+text',
        },
        {
            field: 'spio_in',
            answered: info.spio_answer != null,
            condition: !info.spio_answer && info.spio_in,
            outputType: 'image+text',
        },
        {
            field: 'spio_out',
            answered: true,
            condition: info.spio_answer && !info.first_appeal_citizen_question,
            outputType: 'answered',
        },
        {
            field: 'first_appeal_daa_in',
            answered: info.first_appeal_daa_answer != null,
            condition: info.first_appeal_citizen_question && !info.first_appeal_daa_answer,
            outputType: 'image+text',
        },
        {
            field: 'first_appeal_daa_out',
            answered: true,
            condition: info.first_appeal_daa_answer && !info.second_appeal_citizen_question,
            outputType: 'answered',
        },
        {
            field: 'second_appeal_cic_in',
            answered: info.second_appeal_cic_answer != null,
            condition: info.second_appeal_citizen_question && !info.second_appeal_cic_answer,
            outputType: 'image+text',
        },
        {
            field: 'second_appeal_cic_out',
            answered: true,
            condition: info.second_appeal_cic_answer,
            outputType: 'answered',
        },
    ]

    for (const stage of stages) {
        const raw = info[stage.field]
        if (stage.condition && raw) {
            const date = new Date(raw)
            if (isNaN(date)) return { image: null, text: 'Invalid date' }

            const days = Math.floor((now - date) / (1000 * 60 * 60 * 24))

            if (stage.outputType === 'answered') {
                return {
                    image: null,
                    text: 'Answered at ' + formatDateTime(date),
                }
            }

            return {
                image: getBadgeImage(days),
                text: days === 0 ? 'Today' : days === 1 ? '1 day ago' : `${days} days ago`,
            }
        }
    }

    return { image: null, text: 'Pending' } // fallback
}

function getBadgeImage(days) {
    if (days <= 5) return '/images/one.png'
    if (days <= 10) return '/images/two.png'
    if (days <= 15) return '/images/three.png'
    if (days <= 20) return '/images/four.png'
    if (days <= 25) return '/images/five.png'
    return '/images/six.png'
}

function formatDateTime(date) {
    return date.toLocaleString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    })
}
</script>
