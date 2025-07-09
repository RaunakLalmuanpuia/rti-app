<template>
    <q-page class="q-pa-md flex flex-center">
        <q-card class="q-pa-md q-mx-auto" style="max-width: 500px; width: 100%">
            <!-- Success Banner -->
            <div
                class="text-white text-center q-pa-sm"
                :class="data.orderStatus === 'Success' ? 'bg-teal' : 'bg-red-6'"
            >
                <q-icon
                    :name="data.orderStatus === 'Success' ? 'check_circle' : 'cancel'"
                    size="md"
                />
                <div class="text-h6">
                    {{ data.orderStatus === 'Success' ? 'Well Done!' : 'Oops!' }}
                </div>
                <div class="text-subtitle2">
                    {{ data.orderStatus === 'Success' ? 'Payment is Successful' : 'Payment was not Successful' }}
                </div>
            </div>

            <!-- Receipt Details -->
            <q-card-section>
                <q-btn
                    icon="print"
                    label="Print Receipt"
                    color="primary"
                    class="q-mb-md"
                    @click="submitReceipt"
                    unelevated
                />

                <q-markup-table dense flat>
                    <tbody>
                    <tr>
                        <td class="text-bold">Order ID</td>
                        <td>: {{ data.orderID }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Bank Ref. No</td>
                        <td>: {{ data.bankRefNo }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Name</td>
                        <td>: {{ data.customerName }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Contact</td>
                        <td>: {{ data.contact }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Amount</td>
                        <td>: ₹{{ data.amount }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Date & Time</td>
                        <td>: {{ formatDate(data.dateTime) }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold">Payment Mode</td>
                        <td>: {{ data.paymentMode }}</td>
                    </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <!-- Back to site link -->
            <q-card-actions align="center">
                <Link href="/information" class="text-primary">Back to site</Link>
            </q-card-actions>
        </q-card>
    </q-page>
</template>

<script setup>
import BackendLayout from "@/Layouts/BackendLayout.vue";


import { router, Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

defineOptions({ layout: BackendLayout });

const props = defineProps({
    data: Object
})

const formatDate = (dateTime) => {
    return new Date(dateTime).toLocaleString()
}

const submitReceipt = () => {
    router.post('/information/receipt', {
        orderID: props.data.orderID,
        name: props.data.customerName,
        contact: props.data.contact,
        amount: props.data.amount,
        dateTime: props.data.dateTime,
        paymentMode: props.data.paymentMode,
        bankRefNo: props.data.bankRefNo
    })
}
</script>

<style scoped>
.text-bold {
    font-weight: 600;
}
</style>
