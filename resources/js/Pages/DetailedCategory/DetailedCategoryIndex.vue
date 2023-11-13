<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import Pagination from '@/Components/Pagination.vue';
	import ColumnSort from '@/Components/ColumnSort.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	import { computed } from 'vue';
	import { ref } from 'vue';

	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
			errors: Object,
			detailedCategories: Object
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
		if (! confirm(`Do you wish to delete category ${name}?`)) return false;
		Inertia.delete(`/detailedCategories/${id}`);
	}

	const reinstateRecord = (id) => {
		Inertia.get(`/detailedCategories/${id}/reinstate`);
	}
</script>

<template>
	<Head title="Categories" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Categories
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
		<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">
							Department <ColumnSort url="detailedCategories" columnName="generalCategoryName"></ColumnSort>
						</th>
						<th class="text-left text-gray-600">
							Category <ColumnSort url="detailedCategories" columnName="detailedCategoryName"></ColumnSort>
						</th>
					</tr>
					<tr
						v-for="(detailedCategory,key) in detailedCategories.data"
						v-bind:class="archived(`${detailedCategory.deleted_at}`)"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/detailedCategories/${detailedCategory.id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${detailedCategory.id}`,`${detailedCategory.name}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td>
							<form v-if="detailedCategory.deleted_at" v-on:submit.prevent="reinstateRecord(`${detailedCategory.id}`)">
								<button type="submit" class="text-green-600 hover:underline">Re-instate</button>
							</form>
						</td>
						<td class="text-left">{{ detailedCategory.general_category.name }}</td>
						<td class="text-left">{{ detailedCategory.name }}</td>
					</tr>
				</table>
				<Pagination :data="detailedCategories" />
			</div>

			<div>
				<Link href="/detailedCategories/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
