
<template>
    <q-page class="container" padding>
        <div class="flex items-end justify-between q-pa-md bg-white">
            <div>
                <div class="stitle">My Application List</div>
                <q-breadcrumbs  class="text-dark">
                    <q-breadcrumbs-el class="text-accent"  icon="dashboard" label="Dashboard" @click="$inertia.get(route('dashboard.citizen'))"/>
                    <q-breadcrumbs-el  label="Applications" @click="$inertia.get(route('information.index'))"/>
                </q-breadcrumbs>
            </div>

            <div class="flex q-gutter-sm">
                <q-input borderless dense debounce="800"
                         @update:model-value="handleSearch"
                         outlined
                         v-model="filter" placeholder="Search">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                </q-input>
            </div>
        </div>
        <br/>
        <q-table
            flat
            ref="tableRef"
            :rows="rows"
            :columns="columns"
            row-key="id"
            v-model:pagination="pagination"
            :loading="loading"
            :filter="filter"
            binary-state-sort
            :rows-per-page-options="[1,10,15,30,50]"
            @request="onRequest"
            class="table-fixed"
            wrap-cells
        >

            <template #body-cell-citizen_question="props">
                <q-td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ props.row.citizen_question }}
                </q-td>
            </template>

            <template #body-cell-department="props">
                <q-td>
                    {{ props.row?.department?.name ?? props.row?.local_council?.name  }}
                </q-td>
            </template>

            <template #body-cell-created_at="props">
                <q-td>
                    {{ formatDate(props.row?.created_at) }}
                </q-td>
            </template>

            <template #body-cell-status="props">
                <q-td class="whitespace-nowrap">
                    <span
                        class="text-[10px] font-semibold px-2 py-[2px] rounded inline-block truncate"
                        :class="getStatusClass(props.row)"
                        v-html="getStatusLabel(props.row)"
                    ></span>
                </q-td>
            </template>

            <template #body-cell-time_since_submission="props">
                <q-td>
                    <template v-if="getTimeStatus(props.row).image">
                        <q-img
                            :src="getTimeStatus(props.row).image"
                            class="inline-block mr-1"
                            style="width: 64px; height: 16px"
                            fit="contain"
                        />
                    </template>
                    <template v-if="getTimeStatus(props.row).text">
                        {{ getTimeStatus(props.row).text }}
                    </template>
                </q-td>
            </template>

            <template #body-cell-action="props">
                <q-td>
                    <q-btn
                        @click="$inertia.get(route('information.show', props.row))"
                        label="View"
                        size="sm"
                        outline
                        color="primary"
                        no-caps
                    />
                </q-td>
            </template>
        </q-table>


    </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { router } from '@inertiajs/vue3'
import BackendLayout from '@/Layouts/BackendLayout.vue'

import useUtils from "@/Composables/utils";

defineOptions({ layout: BackendLayout })

const {getStatusLabel,getStatusClass,getTimeStatus } = useUtils();
const q = useQuasar();
const rows = ref([])
const filter = ref('')
const loading = ref(false)
const pagination = ref({
    sortBy: 'desc',
    descending: false,
    page: 1,
    rowsPerPage: 10,
    rowsNumber: 0
})
// 6 Columns
const columns = [
    { name: 'department', label: 'Department', field: 'department', align: 'left' },
    { name: 'citizen_question', label: 'Question', field: 'citizen_question', align: 'left' },
    { name: 'created_at', label: 'Date of Submission', field: 'created_at', align: 'left' },
    { name: 'status', label: 'Status', field: 'status', align: 'left' },
    { name: 'time_since_submission', label: 'Time since submission', field: 'time_since_submission', align: 'left' },
    { name: 'action', label: 'Actions', field: 'action', align: 'right' }
]

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

const handleSearch=val=>{
    onRequest({pagination:pagination.value,filter:val})
}
function onRequest (props) {
    const { page, rowsPerPage, sortBy, descending } = props.pagination
    const filter = props.filter

    loading.value = true
    axios.get(route('information.json-index'),{
        params:{
            filter,
            page,
            rowsPerPage,
        }
    }).then(res=>{
        const {list} = res.data;
        const {data,per_page,current_page,total,to,from} = list;
        rows.value = data;
        pagination.value.page = current_page;
        pagination.value.rowsNumber = total;
        pagination.value.rowsPerPage = per_page;

    })
        .catch(err=>{
            q.notify({type:'negative',message:err?.response?.data?.message})
        })
        .finally(()=>loading.value=false)
}

onMounted(() => {
    // get initial data from server (1st page)
    // tableRef.value.requestServerInteraction()
    onRequest({pagination:pagination.value,
        filter:filter.value
    })
})

</script>




<style scoped>

</style>
