<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import Errors from '@/Components/Errors.vue';
	import Pagination from '@/Components/Pagination.vue';
	import ColumnSort from '@/Components/ColumnSort.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	import { computed } from 'vue';
	import { ref } from 'vue';

	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
			errors: Object,
			books: Object
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
		if (! confirm(`Do you wish to delete book ${title}?`)) return false;
		Inertia.delete(`/books/${id}`);
	}

	const reinstateRecord = (id) => {
		Inertia.get(`/books/${id}/reinstate`);
	}
</script>

<template>
	<Head title="Titles" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Titles
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<Errors v-bind:errors="errors"></Errors>
		<div class="w-full mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">
							Title <ColumnSort url="books" columnName="title"></ColumnSort>
						</th>
						<th class="text-left text-gray-600">Edition</th>
						<th class="text-left text-gray-600">
							Author <ColumnSort url="books" columnName="author.name"></ColumnSort>
						</th>
						<th class="text-left text-gray-600">ISBN</th>
						<th class="text-left text-gray-600">
							Category <ColumnSort url="books" columnName="detailedCategory.name"></ColumnSort>
						</th>
						<th class="text-left text-gray-600">Copies</th>
					</tr>
					<tr
						v-for="(book,key) in books.data"
						v-bind:class="archived(`${book.deleted_at}`)"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/books/${book.id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${book.id}`,`${book.title}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td>
							<form v-if="book.deleted_at" v-on:submit.prevent="reinstateRecord(`${book.id}`)">
								<button type="submit" class="text-green-600 hover:underline">Re-instate</button>
							</form>
						</td>
						<td class="text-left">{{ book.title }}</td>
						<td class="text-left">{{ book.edition }}</td>
						<td class="text-left">{{ book.author.name }}</td>
						<td class="text-left">{{ book.isbn }}</td>
						<td class="text-left">{{ book.detailed_category.name }}</td>
						<td class="text-left">{{ book.copies }}</td>
					</tr>
				</table>
				<Pagination :data="books" />
			</div>

			<div>
				<Link href="/books/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
