<template>
<!--    Answer-->
    <q-card>
        <q-card-section>
            <div class="text-h6 text-purple-600">Answers</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Answered on: 26th Aug, 24 11:50:47 am</p>
            <p class="text-subtitle2 q-mt-sm">
                SPIO: spio1<br />
                SPIO Contact: 78109110469
            </p>

            <q-separator class="q-my-md" />

            <p class="text-subtitle2">Department chhanna hi i lungawi lo em?</p>

            <q-btn
                color="orange"
                label="Do you want to apply for 1st Appeal?"
                @click="applyFirstAppeal"
            />
        </q-card-section>
    </q-card>

<!--    First Appeal Question-->
    <q-card class="mt-4">
        <q-card-section>
            <div class="text-h6 text-purple-600">First Appeal Question</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Question / Zawhna"
                v-model="answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Submitted on: 26th Aug, 24 11:50:47 am</p>

        </q-card-section>
    </q-card>
    <!--    First Appeal Answer-->
    <q-card class="mt-4">
        <q-card-section>
            <div class="text-h6 text-purple-600">First Appeal Answer</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Answered on: 26th Aug, 24 11:50:47 am</p>
            <p class="text-subtitle2 q-mt-sm">
                DAA: daa1<br />
                DAA Contact: 78109110469
            </p>

            <q-separator class="q-my-md" />

            <p class="text-subtitle2">Department chhanna hi i lungawi lo em?</p>

            <q-btn
                color="orange"
                label="Do you want to apply for 2nd Appeal?"
                @click="applySecondAppeal"
            />
        </q-card-section>
    </q-card>

    <!--    Second Appeal Question-->
    <q-card class="mt-4">
        <q-card-section>
            <div class="text-h6 text-purple-600">Second Appeal Question</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Question / Zawhna"
                v-model="answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Submitted on: 26th Aug, 24 11:50:47 am</p>

        </q-card-section>
    </q-card>
    <!--    Second Appeal Answer-->
    <q-card class="mt-4">
        <q-card-section>
            <div class="text-h6 text-purple-600">Second Appeal Answer</div>
        </q-card-section>

        <q-card-section>
            <q-input
                filled
                readonly
                type="textarea"
                label="Answer"
                v-model="answer"
                class="bg-grey-2"
                autogrow
            />
            <p class="text-subtitle2 q-mt-sm">Answered on: 26th Aug, 24 11:50:47 am</p>


            <q-separator class="q-my-md" />


        </q-card-section>
    </q-card>
</template>

<script setup>
import { ref } from 'vue'

const answer = ref('No Answer (Computer Generated)')

const applyFirstAppeal = () => {
    console.log('Apply for 1st Appeal')
}

const applySecondAppeal = () => {
    console.log('Apply for 1st Appeal')
}
</script>
