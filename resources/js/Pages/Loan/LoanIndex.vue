<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import Errors from '@/Components/Errors.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	import { computed } from 'vue';
	import { ref } from 'vue';

	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
			errors: Object,
			loans: Object
	});

	const history = ref(0);

	const filteredLoans = computed(function() {
		return props.loans.data.filter(
			(loan) => {
				if ((history.value == 0) && (loan.returned_on)) { return false }
				return true;
			}
		);
	});

	const zebra = "even:bg-gray-200 odd:bg-gray-100";

	function formatDate(borrowedOn) {
		if (! borrowedOn) { return ""; }
		let theDate = new Date(borrowedOn);
		return theDate.toLocaleDateString();
	}

	function toggleHistory(offOn) {
		history.value = offOn;
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
	<Head title="Loans" />
	<BreezeAuthenticatedLayout>
	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Loans
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<Errors v-bind:errors="errors"></Errors>
		<div class="w-full mt-6 px-6 pt-4 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<div style="display:inline-block;padding: 5px 50px 5px 10px;outline: solid black 1px">
				History
				<ul>
					<li>
						<input type="radio" name="history" value="0" checked v-on:click="toggleHistory(0)"><span style="margin-left:20%">Off</span>
					</li>
					<li>
						<input type="radio" name="history" value="1" v-on:click="toggleHistory(1)"><span style="margin-left:20%">On</span>
					</li>
				</ul>
				</div>
			<div class="mt-4 mb-2 p-2" style="outline: 2px solid #888888">
				<table class="w-full">
					<tr>
						<th></th>
						<th></th>
						<th class="text-left text-gray-600">Borrower</th>
						<th class="text-left text-gray-600">Title</th>
						<th class="text-left text-gray-600">Copy no</th>
						<th class="text-left text-gray-600">Borrowed on</th>
						<th class="text-left text-gray-600">Due back</th>
						<th class="text-left text-gray-600">Returned on</th>
					</tr>
					<tr
						v-for="(loan,key) in filteredLoans"
						v-bind:class="zebra"
					>
						<td>
							<Link class="font-semibold text-blue-600 hover:underline" :href="`/loans/${loan.id}`" method="get">View</Link>
						</td>
						<td>
							<form v-on:submit.prevent="deleteRecord(`${loan.id}`)">
								<button type="submit" class="text-red-600 hover:underline">Delete</button>
							</form>
						</td>
						<td class="text-left">{{ loan.borrower.name }}</td>
						<td class="text-left">{{ loan.inventory_item.book.title }}</td>
						<td class="text-left">{{ loan.inventory_item.copy_no }}</td>
						<td class="text-left">{{ formatDate(loan.borrowed_on) }}</td>
						<td class="text-left">{{ formatDate(loan.due_back) }}</td>
						<td class="text-left">{{ formatDate(loan.returned_on) }}</td>
					</tr>
				</table>
			</div>

			<div>
				<Link href="/loans/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
