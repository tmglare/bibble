<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import Errors from '@/Components/Errors.vue';
	import ColumnSort from '@/Components/ColumnSort.vue';
	import Pagination from '@/Components/Pagination.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	import { computed } from 'vue';
	import { ref } from 'vue';

	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
		errors: Object,
		loans: Object
	});

	const zebra = "even:bg-gray-200 odd:bg-gray-100";

	function formatDate(borrowedOn) {
		if (! borrowedOn) { return ""; }
		let theDate = new Date(borrowedOn);
		return theDate.toLocaleDateString();
	}

	const deleteRecord = (id) => {
		if (! confirm(`Do you wish to delete this loan?`)) return false;
		Inertia.delete(`/loans/${id}`);
	}
</script>

<template>
<pre style="display:none;font-size:xx-small">
	{{ loans }}
</pre>
	<Head title="Loans (incl history)" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Loans
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<Errors v-bind:errors="errors"></Errors>
		<div class="w-full mt-6 px-6 pt-4 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">
							Borrower <ColumnSort url="loans-incl-history" columnName="borrowers.surname"></ColumnSort>
						</th>

						<th class="text-left text-gray-600">
							Title <ColumnSort url="loans-incl-history" columnName="books.title"></ColumnSort>
						</th>

						<th class="text-left text-gray-600">
							Copy no
						</th>

						<th class="text-left text-gray-600">
							Barcode
						</th>

						<th class="text-left text-gray-600">
							Borrowed on <ColumnSort url="loans-incl-history" columnName="borrowed_on"></ColumnSort>
						</th>

						<th class="text-left text-gray-600">
							Due back <ColumnSort url="loans-incl-history" columnName="due_back"></ColumnSort>
						</th>

						<th class="text-left text-gray-600">
							Returned on
							<ColumnSort url="loans-incl-history" columnName="returned_on"></ColumnSort> </th>
					</tr>
					<tr
						v-for="(loan,key) in loans.data"
						v-bind:class="zebra"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/loans/${loan.loan_id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${loan.loan_id}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td class="text-left">{{ loan.borrower.name }}</td>
						<td class="text-left">{{ loan.inventory_item.book.title }}</td>
						<td class="text-left">{{ loan.inventory_item.copy_no }}</td>
						<td class="text-left">{{ loan.inventory_item.barcode }}</td>
						<td class="text-left">{{ formatDate(loan.borrowed_on) }}</td>
						<td class="text-left">{{ formatDate(loan.due_back) }}</td>
						<td class="text-left">{{ formatDate(loan.returned_on) }}</td>
					</tr>
				</table>
<pre style="display:none">
		{{ JSON.stringify(loans.links,null,2) }}
</pre>
				<Pagination :data="loans" />
			</div>
<pre style="display:none">
	{{ loans.links }}
</pre>
			<div>
				<Link href="/loans/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
