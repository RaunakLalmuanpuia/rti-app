<template>
    <q-layout @scroll="handleScroll" style="background: #ffffff" view="hHh lpR fff">

        <q-header height-hint="90"  :class="classObject">
            <q-toolbar  class="container flex justify-between items-center" style="height: 80px">
                <div class="flex items-center q-gutter-sm">
                    <q-img width="60px" src="/images/logo.png" />

                </div>

                <div class="flex items-center q-gutter-md">
<!--                    <a v-if="$q.screen.gt.sm" class="nav-link" href="#">Home</a>-->
<!--                    <a v-if="$q.screen.gt.sm" class="nav-link" href="#feature">Feature</a>-->
<!--                    <a v-if="$q.screen.gt.sm" class="nav-link" href="#about">About</a>-->
                    <q-btn square @click="$inertia.get(route('login'))" class="login-btn" outline label="Login" no-caps/>
                </div>
            </q-toolbar>
        </q-header>

        <q-page-container>
            <slot />
        </q-page-container>

        <q-footer  class="bg-secondary text-footer q-py-md">
            <q-toolbar class="container flex justify-between items-center">
                <div class="flex q-gutter-sm">
                    <img src="images/msegs.png"/>
                    <div>
                        <div>Crafted with care by</div>
                        <div>Mizoram State e-Governance Society</div>
                    </div>
                </div>
                <div class="column items-center">
                    <div class="nav-link">Help Desk</div>
                    <div class="nav-link">support-msegs@mizoram.gov.in/9233942951</div>
                </div>
                <div class="column items-end">
                    <div>© 2024 RTI Online-GoM. All rights reserved</div>
                    <div class="flex q-gutter-sm">
                        <a class="footer-link" href="#">Privacy Policy</a>
                        <a class="footer-link" href="#">Terms and condition</a>
                    </div>
                </div>
            </q-toolbar>
        </q-footer>

    </q-layout>
</template>
<script setup>

import {computed, reactive} from "vue";

const state=reactive({
    isTop:true
})
const classObject=computed(()=>({
    'bg-transparent':state.isTop,
    'text-primary':state.isTop,
    'bg-white shadow-bottom-5 text-primary':!state.isTop,
}))

const handleScroll=detail=>{
    const {position} = detail;
    if (position < 10) {
        state.isTop=true
    }else {
        state.isTop=false
    }
}


</script>
<style scoped>
.login-btn{
    min-width: 100px;
}
.nav-link{
    text-decoration: none;
    width: 80px;
    color: #444444;
}
.text-footer{
    color: #686B9A;
}
.footer-link{
    text-decoration: none;
    color: black;
    font-size: 12px;
}

</style>
