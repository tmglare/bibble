<script setup>
import { ref } from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-10">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <BreezeApplicationLogo class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <BreezeNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </BreezeNavLink>
                            </div>
                            <!-- Navigation Links -->
                            <div class="ml-3 relative flex items-center">
                                <!-- <BreezeDropdown align="left" width="48" class="py-2 border-b-2" :mainMenu="1"> -->
                                <BreezeDropdown
																	align="left"
																	width="48"
																	class="ml-5"
																	:mainMenu="true"
																	:active="route().current('generalCategories.*') ||
																	route().current('detailedCategories.*')"
																>
                                    <template #trigger>
                                        <span class="inline-flex align-bottom rounded-md">
                                            <button type="button" class="inline-flex text-sm font-medium text-gray-900">
                                                {{ "Departments/Categories" }}

                                                <svg class="ml-2 -mr-0.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink href="/generalCategories" method="get" as="button">
                                            Department Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/generalCategories/create" method="get" as="button">
                                            New Department
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/detailedCategories" method="get" as="button">
                                            Category Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/detailedCategories/create" method="get" as="button">
                                            New Category
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                                <BreezeDropdown
																	align="left"
																	width="48"
																	class="ml-5"
																	:mainMenu="true"
																	:active="route().current('authors.*')"
																>
                                    <template #trigger>
                                        <span class="inline-flex align-bottom rounded-md">
                                            <button type="button" class="inline-flex text-sm font-medium text-gray-900">
                                                {{ "Authors" }}

                                                <svg class="ml-2 -mr-0.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink href="/authors" method="get" as="button">
                                            Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/authors/create" method="get" as="button">
                                            New
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                                <BreezeDropdown
																	align="left"
																	width="48"
																	class="ml-5"
																	:mainMenu="true"
																	:active="route().current('books.*')"
																>
                                    <template #trigger>
                                        <span class="inline-flex align-bottom rounded-md">
                                            <button type="button" class="inline-flex text-sm font-medium text-gray-900">
                                                {{ "Books" }}

                                                <svg class="ml-2 -mr-0.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink href="/books" method="get" as="button">
                                            Title Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/books/create" method="get" as="button">
                                            New Title
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/inventoryItems" method="get" as="button">
                                            Item Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/inventoryItems/create" method="get" as="button">
                                            New Item
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                                <BreezeDropdown
																	align="left"
																	width="48"
																	class="ml-5"
																	:mainMenu="true"
																	:active="route().current('borrowers.*')"
																>
                                    <template #trigger>
                                        <span class="inline-flex align-bottom rounded-md">
                                            <button type="button" class="inline-flex text-sm font-medium text-gray-900">
                                                {{ "Borrowers" }}

                                                <svg class="ml-2 -mr-0.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink href="/borrowers" method="get" as="button">
                                            Borrower Index
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink href="/borrowers/create" method="get" as="button">
                                            New Borrower
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <BreezeDropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <BreezeResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </BreezeResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </BreezeResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <!-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> -->
                <div class="mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
