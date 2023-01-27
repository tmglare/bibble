<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	import { computed } from 'vue';
	import { ref } from 'vue';

	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
			errors: Object,
			borrowers: Object
	});

	const zebra = "even:bg-gray-200 odd:bg-gray-100";

	const archived = (deletedAt) => {
		if ((! deletedAt) || deletedAt == "null") {
			return `${zebra} text-gray-800`;
		} else {
			return `${zebra} text-gray-400`;
		}
	}

	const deleteRecord = (id,name) => {
		if (! confirm(`Do you wish to delete borrower ${name}?`)) return false;
		Inertia.delete(`/borrowers/${id}`);
	}

	const reinstateRecord = (id) => {
		Inertia.get(`/borrowers/${id}/reinstate`);
	}
</script>

<template>
	<Head title="Borrowers" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Borrowers
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<div class="w-full mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">Style</th>
						<th class="text-left text-gray-600">Name</th>
						<th class="text-left text-gray-600">Street</th>
						<th class="text-left text-gray-600">Town</th>
						<th class="text-left text-gray-600">Postcode</th>
						<th class="text-left text-gray-600">Telephone</th>
						<th class="text-left text-gray-600">Email</th>
						<th class="text-left text-gray-600">Barcode</th>
					</tr>
					<tr
						v-for="(borrower,key) in borrowers.data"
						v-bind:class="archived(`${borrower.deleted_at}`)"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/borrowers/${borrower.id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${borrower.id}`,`${borrower.name}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td>
							<form v-if="borrower.deleted_at" v-on:submit.prevent="reinstateRecord(`${borrower.id}`)">
								<button type="submit" class="text-green-600 hover:underline">Re-instate</button>
							</form>
						</td>
						<td class="text-left">{{ borrower.style }}</td>
						<td class="text-left">{{ borrower.name }}</td>
						<td class="text-left">{{ borrower.street }}</td>
						<td class="text-left">{{ borrower.town }}</td>
						<td class="text-left">{{ borrower.postcode }}</td>
						<td class="text-left">{{ borrower.telephone }}</td>
						<td class="text-left">{{ borrower.email }}</td>
						<td class="text-left">{{ borrower.barcode }}</td>
					</tr>
				</table>
			</div>

			<div>
				<Link href="/borrowers/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
