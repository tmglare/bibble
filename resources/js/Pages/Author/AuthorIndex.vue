<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';
	import { computed } from 'vue';
	import { ref } from 'vue';

	const props = defineProps({
			errors: Object,
			authors: Object
	});

	const zebra = "even:bg-gray-200 odd:bg-gray-100";
</script>

<template>
    <Head title="Authors" />
<pre style="display:none">
	{{ authors.path }}
</pre>
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Authors
            </h2>
        </template>

        <div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
					<table class="w-full">
					<tr>
						<th></th>
						<th class="text-left text-gray-600">Name</th>
					</tr>
					<tr
						v-for="(author,key) in authors.data"
						v-bind:class="zebra"
					>
						<td>
							<Link :href="`/authors/${author.id}`" method="get">View</Link>
						</td>
						<td class="text-left">{{ author.name }}</td>
					</tr>
				</table>
				</div>
						<div>
							<Link href="/authors/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
						</div>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
