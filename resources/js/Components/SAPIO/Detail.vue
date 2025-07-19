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
                    <td>{{ info.citizen_name }}</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant Contact</th>
                    <td>{{ info.citizen_contact }}</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant Email</th>
                    <td>raunak.msegs@gmail.com</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant Address</th>
                    <td>{{ info.citizen_address }}</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant Question</th>
                    <td>{{ info.citizen_question }}</td>
                </tr>
                <tr>
                    <th class="text-left">Applicant File Attached</th>
                    <td>
                        <div v-if="info.citizen_question_file">
                            <div
                                v-for="(file, index) in info.citizen_question_file.split(',')"
                                :key="index"
                                class="mb-1"
                            >
                                <a
                                    :href="`/storage/files/${file.trim()}`"
                                    target="_blank"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ file }}
                                </a>
                            </div>
                        </div>
                        <div v-else>
                            No file attached
                        </div>
                    </td>
                </tr>

                <tr>
                    <th class="text-left">Concern Department</th>
                    <td>
                        {{ info.citizen_question_department ? info.department?.name || 'N/A' : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Concern Local Council</th>
                    <td>
                        {{ info.citizen_question_locall_council ? info.local_council?.name || 'N/A' : 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <th class="text-left">Related to welfare of BPL</th>
                    <td>{{ info.citizen_bpl ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th class="text-left">BPL Proof</th>
                    <td>
                        <template v-if="info.citizen_bpl && info.citizen_bpl_file">
                            <a
                                :href="`/storage/files/${info.citizen_bpl_file}`"
                                target="_blank"
                                class="text-blue-600 hover:underline"
                            >
                                {{ info.citizen_bpl_file }}
                            </a>
                        </template>
                        <template v-else>
                            N/A
                        </template>
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Does it concern the life or liberty of a person</th>
                    <td :class="info.life_or_death == 1 ? 'bg-negative text-white' : ''">
                        {{ info.life_or_death == 1 ? 'Yes' : 'No' }}
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Submitted On</th>
                    <td>{{ info.created_at }}</td>
                </tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>

    <q-card class="mt-4" v-if="info.order_id">
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
                <tr><th class="text-left">Order ID</th><td>{{ info.order_id }}</td></tr>
                <tr><th class="text-left">Tracking ID</th><td>{{ info.tracking_id }}</td></tr>
                <tr><th class="text-left">Bank Ref. No</th><td>{{ info.bank_ref_no }}</td></tr>
                <tr><th class="text-left">Order Status</th><td>Success</td></tr>
                <tr><th class="text-left">Amount</th><td>10.00</td></tr>
                <tr><th class="text-left">Date & Time of Billing</th><td>{{info.created_at}}</td></tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>


<!--    {{info}}-->
    <q-card class="mt-4" v-if="info.secondhand_question_previous_department">
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-primary">Transfer Reason</div>
        </q-card-section>

        <q-separator />

        <q-card-section class="q-pa-none">
            <q-markup-table flat class="text-subtitle2">
                <tbody>
                <tr>
                    <th class="text-left">Previous Department Name</th>
                    <td>{{ info.citizen_name }}</td>
                </tr>
                <tr>
                    <th class="text-left">Previous Department Remark</th>
                    <td>{{ info.secondhand_question_previous_department_remark }}</td>
                </tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps(['info']);

const showPaymentSummary = ref(false)
const download = () => {
    console.log('Download triggered')
}
</script>
