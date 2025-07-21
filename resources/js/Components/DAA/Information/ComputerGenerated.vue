<template>
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
                @click="$inertia.get(route('daa.information.show',info))"
            >
                <td class="border border-gray-300 px-3 py-2 align-top">{{ info.citizen_name }}</td>
                <td class="border border-gray-300 px-3 py-2 align-top">
                    {{ info.citizen_question.length > 50 ? info.citizen_question.slice(0, 50) + '...' : info.citizen_question }}
                </td>
                <td class="border border-gray-300 px-3 py-2 align-top">{{ info.department?.name }}</td>
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

</template>

<script setup>
import { ref, onMounted } from 'vue'
import {useQuasar} from "quasar";
import useUtils from "@/Composables/utils";

import {router} from "@inertiajs/vue3";
const {getStatusLabel,getStatusClass,getTimeStatus } = useUtils();
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
const getData = () => {
    axios.get(route('daa.information.computer-generated'), {
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
</script>

<style scoped>

</style>
