<template>
    <q-layout view="LHh lpR lff">
        <q-header height-hint="70" elevated style="background-color: #9177FE;" class="bg-white text-black">
            <q-toolbar style="min-height: 60px" class="q-px-lg">
                <q-btn  class="text-primary" round dense color="blue" icon="menu" @click="toggleLeftDrawer" />
<!--                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />-->

                <q-toolbar-title>

                </q-toolbar-title>

                <div class="flex items-center q-gutter-sm">
                    <div v-show="$q.screen.gt.xs" style="line-height: 1">
                        <div class="text-lg text-weight-medium ">{{$page?.props?.auth?.user?.name}}</div>
                        <div class="text-sm text-weight-medium ">{{$page?.props?.auth?.user?.designation}}</div>
                    </div>
                    <q-btn-dropdown  dropdown-icon="account_circle">
                        <q-list v-if="user.role === 0" dense>
                            <q-item clickable v-close-popup>
                                <q-item-section>Profile</q-item-section>
                            </q-item>
                            <q-item @click="router.get(route('citizen.payment.index'))" clickable v-close-popup>
                                <q-item-section>Payment History</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item clickable v-close-popup @click.prevent="$inertia.delete(route('login.destroy'))">
                                <q-item-section>Logout</q-item-section>
                            </q-item>
                        </q-list>

                        <q-list v-if="user.role === 5 && user.bio ==='aspio'" dense>
                            <q-item clickable v-close-popup>
                                <q-item-section>Report</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Colleague</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Profile</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item clickable v-close-popup @click.prevent="$inertia.delete(route('login.destroy'))">
                                <q-item-section>Logout</q-item-section>
                            </q-item>
                        </q-list>


                        <q-list v-if="user.role === 5 && user.bio ==='spio'" dense>
                            <q-item clickable v-close-popup>
                                <q-item-section>Report</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Colleague</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Profile</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item clickable v-close-popup @click.prevent="$inertia.delete(route('login.destroy'))">
                                <q-item-section>Logout</q-item-section>
                            </q-item>
                        </q-list>


                        <q-list v-if="user.role === 5 && user.bio ==='daa'" dense>
                            <q-item clickable v-close-popup>
                                <q-item-section>Report</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Colleague</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Profile</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item clickable v-close-popup @click.prevent="$inertia.delete(route('login.destroy'))">
                                <q-item-section>Logout</q-item-section>
                            </q-item>
                        </q-list>

                        <q-list v-if="user.role === 5 && user.bio ==='cic'" dense>
                            <q-item clickable v-close-popup>
                                <q-item-section>Report</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Colleague</q-item-section>
                            </q-item>
                            <q-item clickable v-close-popup>
                                <q-item-section>Profile</q-item-section>
                            </q-item>
                            <q-separator />
                            <q-item clickable v-close-popup @click.prevent="$inertia.delete(route('login.destroy'))">
                                <q-item-section>Logout</q-item-section>
                            </q-item>
                        </q-list>

                    </q-btn-dropdown>

                </div>
                <!-- Title with Dropdown Menu -->
            </q-toolbar>
        </q-header>

        <q-drawer width="250" style="background-color: #9177FE;" class="text-white" show-if-above v-model="leftDrawerOpen" side="left">
            <!-- drawer content -->
            <q-list class="text-weight-medium">
                <div class="column items-center q-gutter-md q-pa-lg">

                    <q-img src="/images/logo.png" width="46px"/>
                    <div style="line-height: 1" class="text-lg  text-weight-medium text-center">
                        RTI Online
                    </div>
                </div>
                <q-separator class="q-my-sm"/>

                <div v-if="user.role === 0">


                    <q-item
                        :key="'information.create'"
                        :active="route().current() === 'information.create'"
                        active-class="active-menu"
                        clickable
                        :class="route().current() === 'information.create' ? 'active-menu' : 'default-list'"
                        @click="$inertia.get(route('information.create'))"
                    >
                        <q-item-section avatar><q-icon name="receipt" /></q-item-section>
                        <q-item-section><q-item-label>Apply for RTI</q-item-label></q-item-section>
                    </q-item>

                    <q-item
                        :key="'information.index'"
                        :active="route().current() === 'information.index'"
                        active-class="active-menu"
                        clickable
                        :class="route().current() === 'information.index' ? 'active-menu' : 'default-list'"
                        @click="$inertia.get(route('information.index'))"
                    >
                        <q-item-section avatar><q-icon name="folder_open" /></q-item-section>
                        <q-item-section><q-item-label>My Application List</q-item-label></q-item-section>
                    </q-item>

                    <q-item
                        :key="'citizen.complain.create'"
                        :active="route().current() === 'citizen.complain.create'"
                        active-class="active-menu"
                        clickable
                        :class="route().current() === 'citizen.complain.create' ? 'active-menu' : 'default-list'"
                        @click="$inertia.get(route('citizen.complain.create'))"
                    >
                        <q-item-section avatar><q-icon name="edit_document" /></q-item-section>
                        <q-item-section><q-item-label>Submit Complaint</q-item-label></q-item-section>
                    </q-item>

                </div>

                <div v-if="user.role === 5 && user.bio ==='aspio'">

                    <q-item
                        :key="'dashboard.official'"
                        :active="route().current() === 'dashboard.official'"
                        active-class="active-menu"
                        clickable
                        :class="route().current() === 'dashboard.official' ? 'active-menu' : 'default-list'"
                        @click="$inertia.get(route('dashboard.official'))"
                    >
                        <q-item-section avatar><q-icon name="dashboard" /></q-item-section>
                        <q-item-section><q-item-label>Dashboard</q-item-label></q-item-section>
                    </q-item>

                    <q-item
                        :key="'sapio.information.index'"
                        :active="route().current() === 'sapio.information.index'"
                        active-class="active-menu"
                        clickable
                        :class="route().current() === 'sapio.information.index' ? 'active-menu' : 'default-list'"
                        @click="$inertia.get(route('sapio.information.index'))"
                    >
                        <q-item-section avatar><q-icon name="description" /></q-item-section>
                        <q-item-section><q-item-label>My Application List</q-item-label></q-item-section>
                    </q-item>

                </div>

                <div v-if="user.role === 5 && user.bio ==='spio'">
                    <q-item :active="route().current()==='dashboard.official'" active
                            active-class="active-menu "
                            clickable
                            @click="$inertia.get(route('dashboard.official'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='dashboard.official'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                                <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                    <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                            </q-icon>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='spio.information.index'" active
                            active-class="active-menu"
                            clickable
                            @click="$inertia.get(route('spio.information.index'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='spio.information.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>My Application List</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='dashboard.official'" active
                            active-class="active-menu "
                            clickable
                            @click="$inertia.get(route('dashboard.official'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='dashboard.official'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                                <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                    <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                            </q-icon>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Consultation Received</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='dashboard.official'" active
                            active-class="active-menu "
                            clickable
                            @click="$inertia.get(route('dashboard.official'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='dashboard.official'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                                <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                    <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                            </q-icon>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Application Transfer History</q-item-label>
                        </q-item-section>
                    </q-item>

                </div>


                <div v-if="user.role === 5 && user.bio ==='daa'">
                    <q-item :active="route().current()==='dashboard.official'" active
                            active-class="active-menu "
                            clickable
                            @click="$inertia.get(route('dashboard.official'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='dashboard.official'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                                <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                    <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                            </q-icon>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='daa.information.index'" active
                            active-class="active-menu"
                            clickable
                            @click="$inertia.get(route('daa.information.index'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='daa.information.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>My Application List</q-item-label>
                        </q-item-section>
                    </q-item>
                </div>


                <div v-if="user.role === 5 && user.bio ==='cic'">
                    <q-item :active="route().current()==='dashboard.official'" active
                            active-class="active-menu "
                            clickable
                            @click="$inertia.get(route('dashboard.official'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='dashboard.official'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                                <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                    <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                    <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                                </svg>
                            </q-icon>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='cic.information.index'" active
                            active-class="active-menu"
                            clickable
                            @click="$inertia.get(route('cic.information.index'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='cic.information.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Second Appeal</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='cic.complain.index'" active
                            active-class="active-menu"
                            clickable
                            @click="$inertia.get(route('cic.complain.index'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='cic.complain.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Grievance/Complaint List</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item :active="route().current()==='daa.information.index'" active
                            active-class="active-menu"
                            clickable
                            @click="$inertia.get(route('daa.information.index'))">
                        <q-item-section avatar>
                            <q-icon>
                                <svg v-if="route().current()==='daa.information.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>List of Officers</q-item-label>
                        </q-item-section>
                    </q-item>
                </div>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot />
        </q-page-container>
    </q-layout>
</template>

<script setup>
import { computed, ref } from 'vue'
import {router, usePage} from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)

const leftDrawerOpen = ref(false)


const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>

<style scoped>
.default-list{
    max-height: 8px !important;
    min-height: 42px !important;
    color: black;
}
.active-menu {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.2); /* optional highlight */
}


</style>
