<template>
    <!-- Answer-->
    <q-card>
        <q-card-section>
            <div class="text-h6 text-purple-600">Answer</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="info.spio_answer"
                class="bg-grey-2"
                autogrow
            />

            <p class="text-subtitle2 q-mt-sm">Answered on: {{info.spio_out}}</p>
            <p class="text-subtitle2 q-mt-sm">
                SPIO: {{ info.spio_name }}<br />
                SPIO Contact: {{info.spio_contact}}
            </p>


            <!-- Attachment download block -->
            <div class="q-mt-md" v-if="info.spio_answer_file">
                <div
                    v-for="(file, index) in info.spio_answer_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />

                    <template v-if="!info.paid_attachments || info.paid_attachments.payment_status === 'paid'">
                        <!-- Free or paid => allow download -->
                        <a
                            :href="`/storage/spio_answers/${file.trim()}`"
                            class="text-blue-600 underline"
                            target="_blank"
                            download
                        >
                            {{ file.trim() }}
                        </a>
                    </template>

                    <template v-else>
                        <!-- Paid but not yet paid => restrict -->
                        <span class="text-gray-600">{{ file.trim() }}</span>
                    </template>
                </div>
            </div>

            <!-- Show Pay Now section only if attachment is paid and not yet paid -->
            <div
                class="q-pa-md row items-center no-wrap q-gutter-md"
                v-if="info.paid_attachments && info.paid_attachments.payment_status !== 'paid'"
            >
                <div class="col-auto">
                    <q-btn
                        label="PAY NOW"
                        color="primary"
                        text-color="white"
                        unelevated
                        class="text-sm font-normal q-px-md q-py-sm"
                        @click="payNow"
                    />
                </div>

                <div class="col text-base text-grey-9">
                    <strong class="text-weight-bold">₹{{ info.paid_attachments.amount }}</strong>
                    must be PAID to view exclusive documents
                    <div class="text-sm text-grey-8">
                        (Hemi zat hi document thil tel en nan pek belh a ngai.)
                    </div>
                </div>
            </div>

            <q-separator class="q-my-md" />

            <template v-if="!info.first_appeal_citizen_question">
                <p class="text-subtitle2">Department chhanna hi i lungawi lo em?</p>

                <q-btn
                    color="orange"
                    label="Do you want to apply for 1st Appeal?"
                    @click="applyFirstAppeal"
                />
            </template>
        </q-card-section>
    </q-card>
</template>

<script setup>
const props = defineProps(['info']);


const payNow = () => {
    console.log('pay attachment')
}
const applyFirstAppeal = () => {
    console.log('Apply for 1st Appeal')
}
</script>


<style scoped>

</style>
