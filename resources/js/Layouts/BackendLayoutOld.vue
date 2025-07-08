<template>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0 min-h-screen">
            <nav class="mt-10 space-y-1" v-if="user.role === 0">
                <a
                    :href="route('information.create')"
                    :class="linkClass('information.create')"
                >
                    <i class="mr-2 ti-layout-accordion-merged"></i> Apply for RTI
                </a>


                <a
                    :href="route('dashboard.citizen')"
                    :class="linkClass('dashboard.citizen')"
                >
                    <i class="mr-2 ti-layout-accordion-merged"></i> My Application List
                </a>

                <a
                    :href="route('home')"
                    :class="linkClass('information.complain.create')"
                >
                    <i class="mr-2 ti-file"></i> Submit Complaint
                </a>

            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <button class="text-gray-600 lg:hidden">
                    <i class="ti-menu"></i>
                </button>

                <!-- User Dropdown -->
                <div class="relative" @click="dropdownOpen = !dropdownOpen">
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <img
                            :src="avatarUrl"
                            class="w-8 h-8 rounded-full object-cover"
                            alt="Avatar"
                        />
                        <span class="font-medium text-gray-800">{{ user.name }}</span>
                    </div>

                    <div
                        v-if="dropdownOpen"
                        class="absolute right-0 mt-2 w-64 bg-white border shadow-lg rounded z-50"
                    >
                        <ul class="text-sm text-gray-700 divide-y">
                            <template v-if="user.role === 10">
                                <!--                                <li><a href="/department" class="dropdown-link"><i class="mr-2 ti-settings"></i>Dept List</a></li>-->
                                <!--                                <li><a href="/localcouncil" class="dropdown-link"><i class="mr-2 ti-settings"></i>VC/LC List</a></li>-->
                            </template>

                            <template v-if="user.role === 5">
                                <!--                                <li><a href="/information/report" class="dropdown-link"><i class="mr-2 ti-clipboard"></i>Report</a></li>-->
                                <li>
                                    <a
                                        :href="user.bio === 'cic' ? '/user/colleague/cic' : `/user/colleague/${user.department}`"
                                        class="dropdown-link"
                                    >
                                        <i class="mr-2 ti-gift"></i>Colleague
                                    </a>
                                </li>
                            </template>

                            <!--                            <li>-->
                            <!--                                <a-->
                            <!--                                    :href="user.role === 10 ? '/admin/users' : `/users/${user.id}/edit`"-->
                            <!--                                    class="dropdown-link"-->
                            <!--                                >-->
                            <!--                                    <i class="mr-2 ti-user"></i> Profile-->
                            <!--                                </a>-->
                            <!--                            </li>-->

                            <li v-if="user.role === 0">
                                <a href="#" class="dropdown-link">
                                    <i class="mr-2 ti-list"></i> Payment History
                                </a>
                            </li>

                            <template v-if="user.role === 10">
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-list"></i>Approve User</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-money"></i>Department Revenue</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-money"></i>Local Council Revenue</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-rocket"></i>All Payment History</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-dashboard"></i>Admin Dashboard (Quick)</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-dashboard"></i>Admin Dashboard (Detailed)</a></li>
                                <li><a href="#" class="dropdown-link"><i class="mr-2 ti-dashboard"></i>Export User</a></li>
                            </template>

                            <li><a href="/logout" class="dropdown-link"><i class="mr-2 ti-power-off"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'


const page = usePage()
const user = computed(() => page.props.auth.user)
const dropdownOpen = ref(false)

const avatarUrl = computed(() =>
    user.value.avatar === 'https://placehold.it/160x160'
        ? '/images/head_shot.jpg'
        : user.value.avatar
)

const linkClass = (routeName) =>
    route().current(routeName)
        ? 'block px-6 py-3 bg-gray-900 text-white font-semibold'
        : 'block px-6 py-3 hover:bg-gray-700 transition'
</script>

<style scoped>
.dropdown-link {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    transition: background 0.2s;
}
.dropdown-link:hover {
    background-color: #f3f4f6;
}
</style>
