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
			inventoryItems: Object
	});

	const zebra = "even:bg-gray-200 odd:bg-gray-100";

	const archived = (deletedAt) => {
		if ((! deletedAt) || deletedAt == "null") {
			return `${zebra} text-gray-800`;
		} else {
			return `${zebra} text-gray-400`;
		}
	}

	const deleteRecord = (id,title) => {
		if (! confirm(`Do you wish to delete item ${title}?`)) return false;
		Inertia.delete(`/inventoryItems/${id}`);
	}

	const reinstateRecord = (id) => {
		Inertia.get(`/inventoryItems/${id}/reinstate`);
	}
</script>

<template>
	<Head title="Items" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Items
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<div class="w-3/4 mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">Title</th>
						<th class="text-left text-gray-600">Author</th>
						<th class="text-left text-gray-600">Copy no</th>
						<th class="text-left text-gray-600">Barcode</th>
					</tr>
					<tr
						v-for="(inventoryItem,key) in inventoryItems.data"
						v-bind:class="archived(`${inventoryItem.deleted_at}`)"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/inventoryItems/${inventoryItem.id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${inventoryItem.id}`,`${inventoryItem.book.title}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td>
							<form v-if="inventoryItem.deleted_at" v-on:submit.prevent="reinstateRecord(`${inventoryItem.id}`)">
								<button type="submit" class="text-green-600 hover:underline">Re-instate</button>
							</form>
						</td>
						<td class="text-left">{{ inventoryItem.book.title }}</td>
						<td class="text-left">{{ inventoryItem.book.author.name }}</td>
						<td class="text-left">{{ inventoryItem.copy_no }}</td>
						<td class="text-left">{{ inventoryItem.barcode }}</td>
					</tr>
				</table>
			</div>

			<div>
				<Link href="/inventoryItems/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
