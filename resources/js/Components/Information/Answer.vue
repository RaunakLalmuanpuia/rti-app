<script setup>
const props = defineProps(['info']);
</script>

<template>
    <q-card>
        <q-card-section class="row items-center justify-between">
            <div class="text-h6 text-purple-600">SPIO Answer</div>
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

            <div class="q-mt-md" v-if="info.spio_answer_file">
                <div class="text-h8 mb-2">Attachments</div>
                <div
                    v-for="(file, index) in info.spio_answer_file.split(',')"
                    :key="index"
                    class="q-mb-sm"
                >
                    <q-icon name="attach_file" class="q-mr-sm text-primary" />
                    <a
                        :href="`/storage/files/${file.trim()}`"
                        class="text-blue-600 underline"
                        target="_blank"
                        download
                    >
                        {{ file.trim() }}
                    </a>
                </div>
            </div>

        </q-card-section>
    </q-card>
</template>

<style scoped>

</style>
