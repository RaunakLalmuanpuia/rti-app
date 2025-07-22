

<template>

    <q-page padding>
        <div class="text-h6 mb-4">CIC INFORMATION INDEX</div>

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
                    <th class="border border-gray-300 font-semibold px-3 py-2">Name</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Complain/Grievance</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Date of Submission</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Status</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Time elapse to answer</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(info, index) in information.data"
                    :key="info.id"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                    @click="$inertia.get(route('cic.complain.show',info))"
                >
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ info.citizen_name }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">
                        {{ info.citizen_question.length > 50 ? info.citizen_question.slice(0, 50) + '...' : info.citizen_question }}
                    </td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ formatDate(info.created_at) }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">
                    <span
                        class="text-[10px] font-semibold px-2 py-[2px] rounded"
                        :class="getStatusInfo(info).class"
                        v-html="getStatusInfo(info).label"
                    ></span>
                    </td>
                    <td class="border border-gray-300 px-3 py-2 align-top">
                        {{ getElapsedTime(info) }}
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

    </q-page>


</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import { ref, onMounted } from 'vue'
import {useQuasar} from "quasar";


defineOptions({ layout: BackendLayout });
const q = useQuasar();

const information = ref({
    data: [],
    total: 0,
    per_page: 10,
    current_page: 1,
    from: 1,
    to: 1,
    links: []
});

const state = ref({
    search: '',
    perPage: 10,
    page: 1
});


const getData = () => {
    axios.get(route('cic.complain.json'), {
        params: {
            filter: state.value.search,
            page: state.value.page,
            rowsPerPage: state.value.perPage
        }
    }).then(res => {
        information.value = res.data.list;
    }).catch(err => {
        q.notify({ type: 'negative', message: err?.response?.data?.message || 'Something went wrong' });
    });
};
const handleSearch = () => {
    state.value.page = 1; // reset to first page on search
    getData();
};

const changePerPage = () => {
    state.value.page = 1;
    getData();
};

const goToPage = (url) => {
    if (!url) return;
    const page = new URL(url).searchParams.get('page');
    state.value.page = parseInt(page);
    getData();
};

onMounted(() => {
    // get initial data from server
    getData();
})

function formatDate(dateString) {
    const date = new Date(dateString)
    const options = {
        day: 'numeric',
        month: 'short',
        year: '2-digit',
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    }
    return date.toLocaleString('en-IN', options)
}

function getStatusInfo(info) {
    if (info.second_appeal_cic_answer !== null) {
        return {
            class: 'bg-green-100 text-green-700',
            label: 'Answered by CIC'
        }
    }

    if (info.complaint_read === null || info.complaint_read === 0) {
        return {
            class: 'bg-red-100 text-red-600',
            label: 'Under process by CIC'
        }
    }

    if (info.complaint_read === 1) {
        return {
            class: 'bg-green-200 text-green-800',
            label: 'Read'
        }
    }

    if (info.complaint_read === 2) {
        return {
            class: 'bg-yellow-100 text-yellow-700',
            label: 'Unread'
        }
    }

    return {
        class: 'bg-gray-100 text-gray-700',
        label: 'Unknown'
    }
}


function getElapsedTime(info) {
    if (!info.second_appeal_cic_in && !info.second_appeal_cic_out) return ''

    if (info.second_appeal_cic_answer === null && info.second_appeal_cic_in) {
        const from = new Date(info.second_appeal_cic_in)
        const to = new Date()
        return timeDiffHumanReadable(from, to)
    }

    if (info.second_appeal_cic_answer !== null && info.second_appeal_cic_out) {
        return 'Answered at ' + formatDate(info.second_appeal_cic_out)
    }

    return ''
}

function timeDiffHumanReadable(from, to) {
    const diffMs = Math.abs(to - from)
    const seconds = Math.floor(diffMs / 1000)
    const minutes = Math.floor(seconds / 60)
    const hours = Math.floor(minutes / 60)
    const days = Math.floor(hours / 24)

    if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`
    if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`
    if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`
    return `${seconds} second${seconds !== 1 ? 's' : ''} ago`
}
</script>

<style scoped>

</style>
