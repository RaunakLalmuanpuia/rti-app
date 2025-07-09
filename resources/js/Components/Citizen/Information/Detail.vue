<template>
    <q-card>
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-primary">RTI Application details</div>
            <q-btn
                flat
                icon="download"
                label="Download"
                class="text-grey-7"
                @click="download"
            />
        </q-card-section>

        <q-separator />

        <q-card-section class="q-pa-none">
            <q-markup-table flat class="text-subtitle2">
                <tbody>
                <tr>
                    <th class="text-left">Applicant Name</th>
                    <td>Raunak</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant Contact</th>
                    <td>7640876052</td>
                </tr>
                <tr><th class="text-left">Applicant Email</th><td>raunak.msegs@gmail.com</td></tr>
                <tr><th class="text-left">Applicant Address</th><td>Aizawl</td></tr>
                <tr><th class="text-left">Applicant Question</th><td>test</td></tr>
                <tr><th class="text-left">Applicant File Attached</th><td></td></tr>
                <tr><th class="text-left">Concern Department</th><td>dept1</td></tr>
                <tr><th class="text-left">Concern Local Council</th><td>N/A</td></tr>
                <tr><th class="text-left">Related to welfare of BPL</th><td>No</td></tr>
                <tr><th class="text-left">BPL Proof</th><td>nil</td></tr>
                <tr><th class="text-left">Does it concern the life or liberty of a person</th><td>No</td></tr>
                <tr><th class="text-left">Submitted On</th><td>11th Jul, 24 5:21:45 am</td></tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>

    <q-card class="mt-4">
        <!-- Clickable header to toggle -->
        <q-card-section
            class="row items-center justify-between cursor-pointer"
            @click="showPaymentSummary = !showPaymentSummary"
        >
            <div class="text-h6 text-primary">Payment Summary</div>
            <q-icon :name="showPaymentSummary ? 'expand_less' : 'expand_more'" />
        </q-card-section>

        <q-separator />

        <!-- Conditionally show the section -->
        <q-card-section v-show="showPaymentSummary" class="q-pa-none">
            <q-markup-table flat class="text-subtitle2">
                <tbody>
                <tr><th class="text-left">Order ID</th><td>172067526256134882</td></tr>
                <tr><th class="text-left">Tracking ID</th><td>113370597072</td></tr>
                <tr><th class="text-left">Bank Ref. No</th><td>102450245758</td></tr>
                <tr><th class="text-left">Order Status</th><td>Success</td></tr>
                <tr><th class="text-left">Amount</th><td>10.00</td></tr>
                <tr><th class="text-left">Date & Time of Billing</th><td>11/07/2024 10:41:28</td></tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>
</template>

<script setup>
import { ref } from 'vue'
const showPaymentSummary = ref(false)
const download = () => {
    console.log('Download triggered')
}
</script>
