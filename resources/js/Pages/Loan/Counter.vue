<script setup>
	import { useForm } from '@inertiajs/inertia-vue3';
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	const props = defineProps({
			errors: Object,
			loan: Object,
			borrowers: Array,
			inventoryItems: Array
	});

	const form = useForm({
		borrowerBarcode:   "",
		itemBarcode:       "",
		borrower_id:       "",
		inventory_item_id: "",
		borrowed_on:       props.loan.borrowed_on.split("T")[0],
		due_back:          props.loan.due_back.split("T")[0]
	});

	function readBorrowerBarcode() {
		axios.get(
			`/borrowers/byBarcode/${borrowerBarcode.value}`
		).then(
			(response) => {
				form.borrower_id = response.data;
			}
		);
	}

	function readItemBarcode() {
		axios.get(
			`/inventoryItems/byBarcode/${itemBarcode.value}`
		).then(
			(response) => {
				form.inventory_item_id = response.data;
			}
		);
	}
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
			<form v-on:submit.prevent="form.post('/loans')">
				<div>
					<Label value="Borrower Barcode"/>
					<Input
						id="borrowerBarcode"
						class="w-3/4 border-2"
						maxlength="20"
						title="Enter barcode or select from list"
						style="text-transform:uppercase"
						v-model="form.borrowerBarcode"
						v-on:change='readBorrowerBarcode'
					/>
				</div>

				<div class="mt-1">
					<!-- <Label value="Borrower"/> -->
					<select
						id="borrower_id"
						v-model="form.borrower_id"
						class="w-3/4 border-2"
						required
					>
						<option value="">
							Select a borrower
						</option>
						<option v-for="(borrower) in borrowers" :value="borrower.id">
							{{ borrower.name }}
						</option>
					</select>
					<div class="bg-red-200">
						{{ errors.borrower_id }}
					</div>
				</div>

				<div>
					<Label value="Book Barcode"/>
					<Input
						id="itemBarcode"
						class="w-3/4 border-2"
						maxlength="20"
						title="Enter barcode or select from list"
						style="text-transform:uppercase"
						v-model="form.itemBarcode"
						v-on:change='readItemBarcode'
					/>
				</div>

				<div class="mt-1">
					<!-- <Label value="Book"/> -->
					<select
						id="book"
						v-model="form.inventory_item_id"
						class="w-3/4 border-2"
						required
					>
						<option value="">
							Select a book
						</option>
						<option v-for="(inventoryItem) in inventoryItems" :value="inventoryItem.id">
							{{ inventoryItem.book.title + ' (copy ' + inventoryItem.copy_no + ')' }}
						</option>
					</select>
					<div class="bg-red-200">
						{{ errors.inventoryitem_id }}
					</div>
				</div>

				<div>
					<Label value="Borrowed on"/>
					<Input
						type="date"
						id="borrowedOn"
						v-model="form.borrowed_on"
						class="w-3/4 border-2"
						required
					/>
					<div class="bg-red-200">
						{{ errors.borrowed_on }}
					</div>
				</div>

				<div>
					<Label value="Due back"/>
					<Input
						type="date"
						id="dueBack"
						v-model="form.due_back"
						class="w-3/4 border-2"
						required
					/>
					<div class="bg-red-200">
						{{ errors.due_back }}
					</div>
				</div>

				<div class="mt-2">
					<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
						Finish
					</Button>
				</div>
			</form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
