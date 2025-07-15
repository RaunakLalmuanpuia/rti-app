

<template>
    <q-page class="q-pa-md">

        <!-- Web Payment-->
        <div class="overflow-x-auto border border-t-0 border-gray-200 rounded-b-md">
            <div>Payment History From Website</div>

            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <span class="text-sm">Show</span>
                <select
                    v-model="state.perPageWeb"
                    @change="changePerPage"
                    class="border border-gray-300 rounded px-2 py-1 text-sm"
                >
                    <option v-for="n in [10, 25, 50, 100]" :key="n" :value="n">
                        {{ n }}
                    </option>
                </select>
                <span class="text-sm">entries</span>
            </div>

            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead>
                <tr class="bg-black text-white text-left">
                    <th class="border border-gray-300 font-semibold px-3 py-2">Sl No</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Order ID</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Tracking ID</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Order Status</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Payment Mode</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Amount</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Billing Name</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Created At</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(payment, index) in payments.data"
                    :key="payment.id"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                >
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ index + 1 }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.order_id }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.tracking_id  }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.order_status }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.payment_mode }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.amount }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.billing_name  }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.trans_date }}</td>
                </tr>
                <tr v-if="payments.length === 0">
                    <td colspan="5" class="text-center py-3 text-gray-500">No records found.</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border border-t-0 border-gray-200 rounded-b-md px-4 py-3 text-sm text-gray-700">
            <div>
                Showing {{ payments.from }} to {{ payments.to }} of {{ payments.total }} entries
            </div>
            <div class="inline-flex items-center space-x-1 mt-2 sm:mt-0">
                <button
                    v-for="link in payments.links"
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

        <!--Mobile Payment-->
        <div class="overflow-x-auto border border-t-0 mt-5 border-gray-200 rounded-b-md">
            <div>Payment History From Mobile Application</div>

            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <span class="text-sm">Show</span>
                <select
                    v-model="state.perPageMobile"
                    @change="changePerPage"
                    class="border border-gray-300 rounded px-2 py-1 text-sm"
                >
                    <option v-for="n in [ 10, 25, 50, 100]" :key="n" :value="n">
                        {{ n }}
                    </option>
                </select>
                <span class="text-sm">entries</span>
            </div>

            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead>
                <tr class="bg-black text-white text-left">
                    <th class="border border-gray-300 font-semibold px-3 py-2">Sl No</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Order ID</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Transaction ID</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Order Status</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Amount</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Currency</th>
                    <th class="border border-gray-300 font-semibold px-3 py-2">Created At</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(payment, index) in paytmPayments.data"
                    :key="payment.id"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                >
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ index+1 }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.orderId }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.transactionId  }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.status }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.amount }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.currency  }}</td>
                    <td class="border border-gray-300 px-3 py-2 align-top">{{ payment.created_at }}</td>
                </tr>
                <tr v-if="paytmPayments.data.length === 0">
                    <td colspan="5" class="text-center py-3 text-gray-500">No records found.</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border border-t-0 border-gray-200 rounded-b-md px-4 py-3 text-sm text-gray-700">
            <div>
                Showing {{ paytmPayments.from }} to {{ paytmPayments.to }} of {{ paytmPayments.total }} entries
            </div>
            <div class="inline-flex items-center space-x-1 mt-2 sm:mt-0">
                <button
                    v-for="link in paytmPayments.links"
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



<!--{{payments}}-->
<!--    {{paytmPayments}}-->
</template>


<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";

defineOptions({ layout: BackendLayout });


const props = defineProps({
    payments: Object,
    paytmPayments: Object,
    perPageWeb: Number,
    perPageMobile: Number,

})
const state = reactive({
    perPageWeb: props.perPageWeb || 10,
    perPageMobile: props.perPageMobile || 10,
})
const changePerPage = () => {
    router.get(route('citizen.payment.index'), {
        perPageWeb: state.perPageWeb,
        perPageMobile: state.perPageMobile,
    }, { preserveState: true, replace: true })
}

// Pagination navigation
const goToPage = (url) => {
    if (url) {
        router.visit(url, {
            preserveState: true,
            replace: true,
            data: {
                perPageWeb: state.perPageWeb,
                perPageMobile: props.perPageMobile,
            }
        })

    }
}


</script>
<style scoped>

</style>
