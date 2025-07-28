<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({layout:FrontendLayout})
</script>

<template>
    <div class="container bg-white q-mt-xl">
        <div class="row ">
            <div v-if="$q.screen.gt.sm" class="col-xs-12 col-sm-6">
                <q-img  height="480" width="580"  src="images/reg1.png"/>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="flex items-center justify-center">
                    <div class="column q-gutter-sm">
                        <p class="login-title">Pending For Approval</p>
                        <q-btn @click="$inertia.get('/')" flat label="Go to Home"/>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<style scoped>
.login-title{
    padding: 0;
    margin-left: 8px;
    color: #191c51;
    font-size: 28px;
    font-weight: bold;
}
</style>
