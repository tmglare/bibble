<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center text-4xl font-bold">
											<h2 class="mb-4">Our Library</h2>
											<img src="img/bookshelf.jpg" style="height:400px;margin:auto" />
											<div class="mt-4">
												<Link class="font-semibold text-blue-600 hover:underline" href="/loans/counter" title="Customer desk" method="get">
													<i class="fa-solid fa-user-tie fa-xl"></i>
													<h4 class="mt-2 text-sm">
														Customer desk
													</h4>
												</Link>
											</div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
