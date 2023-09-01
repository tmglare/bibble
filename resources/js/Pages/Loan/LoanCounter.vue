<script setup>
	import { useForm } from '@inertiajs/inertia-vue3';
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Errors from '@/Components/Errors.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';
	import { ref } from 'vue';

	const props = defineProps({
			errors: Object,
			loan: Object,
			borrowers: Array,
			inventoryItemsOffLoan: Array,
			inventoryItemsOnLoan: Array
	});

	const form1 = useForm({
		itemBarcode:       "",
		inventory_item_id: "",
		itemName:          "",
		copyNo:            null
	});

	const ibcError = ref("");

	const form2 = useForm({
		borrowerBarcode:   "",
		itemBarcode:       "",
		borrower_id:       "",
		inventory_item_id: "",
		borrowed_on:       props.loan.borrowed_on.split("T")[0],
		due_back:          props.loan.due_back.split("T")[0]
	});

	function readBorrowerBarcode() {
		let barcode = document.getElementById("borrowerBarcode").value;

		axios.get(
			`/borrowers/byBarcode/${barcode}`
		).then(
			(response) => {
				form2.borrower_id = response.data;
			}
		);
	}


	function readItemInBarcode() {
		let barcode = document.getElementById("itemInBarcode").value;

		axios.get(
			`/inventoryItems/byBarcode/${barcode}`
		).then(
			(response) => {
				form1.inventory_item_id = response.data;
			}
		);
	}

	function readItemOutBarcode() {
		let barcode = document.getElementById("itemOutBarcode").value;

		axios.get(
			`/inventoryItems/byBarcode/${barcode}`
		).then(
			(response) => {
				form2.inventory_item_id = response.data;
			}
		);
	}

	function validateAndProcessItemIn() {
		let barcode = document.getElementById("itemInBarcode").value;

		if (! barcode) {
			if (form1.inventory_item_id) {
				form1.post("/loans/processReturn?counter=1",{ preserveState: false });
				return true;
			}
			ibcError.value = "Barcode required";
			return false;
		}

		axios.get(
			`/inventoryItems/getItemByBarcode/${barcode}`
		).then(
			(response) => {
				if (response) {
					if (response.data) {
						let id   = response.data.id;
						let name = response.data.book.title;
						if ( id && name) {
							form1.inventory_item_id = id;
							form1.itemName          = name;
							ibcError.value = "";
							form1.post("/loans/processReturn?counter=1",{ preserveState: false });
							return true;
						}
					}
				}

				form1.itemName          = "";
				form1.inventory_item_id = "";
				ibcError.value = "Invalid code";
				// itemBarcode.focus();
				return false;
			}
		);
	}
</script>

<template>
<pre style="display:none">
	{{ $page.props.flash.message }}
</pre>
	<Head title="Counter" />

	<BreezeAuthenticatedLayout>

	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Counter
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 px-6 sm:pt-0 bg-gray-100">
		<Errors v-bind:errors="errors"></Errors>
		<div class="w-11/12 mx-auto mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<table class="w-full mt-2">
				<tr>
					<td class="w-1/2 align-top">
						<h2 class="font-semibold text-xl text-gray-800 leading-tight">
							Book in
						</h2>
						<form v-on:submit.prevent="validateAndProcessItemIn();">
							<div>
								<Label value="Book Barcode"/>
								<Input
									id="itemInBarcode"
									class="w-3/4 border-2"
									maxlength="20"
									title="Enter barcode"
									style="text-transform:uppercase"
									v-model="form1.itemBarcode"
									v-on:change="readItemInBarcode()"
								/>
								<div class="bg-red-200">
									{{ ibcError }}
								</div>
							</div>

							<div class="mt-1">
								<!-- <Label value="Book"/> -->
								<select
									id="book"
									v-model="form1.inventory_item_id"
									v-on:click="form1.itemBarcode = '';"
									class="w-3/4 border-2"
									required
								>
									<option value="" selected>
										Barcode missing/damaged?
									</option>
									<option v-for="(inventoryItem) in inventoryItemsOnLoan" :value="inventoryItem.id">
										{{ '[' + inventoryItem.barcode + '] ' + inventoryItem.book.title + ' (copy ' + inventoryItem.copy_no + ')' }}
									</option>
								</select>

								<div class="bg-red-200">
									{{ errors.inventoryitem_id }}
								</div>
							</div>

							<div class="mt-2">
								<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
									Return now
								</Button>
							</div>
						</form>
					</td>
					<td>
						<h2 class="font-semibold text-xl text-gray-800 leading-tight">
							Book out
						</h2>
						<form v-on:submit.prevent="form2.post('/loans?counter=1',{ preserveState: false })">
							<div>
								<Label value="Borrower Barcode"/>
								<Input
									id="borrowerBarcode"
									class="w-3/4 border-2"
									maxlength="20"
									title="Enter barcode or select from list"
									style="text-transform:uppercase"
									v-model="form2.borrowerBarcode"
									v-on:change='readBorrowerBarcode()'
								/>
							</div>

							<div class="mt-1">
								<!-- <Label value="Borrower"/> -->
								<select
									id="borrower_id"
									v-model="form2.borrower_id"
									v-on:click="form2.borrowerBarcode = '';"
									class="w-3/4 border-2"
									required
								>
									<option value="" selected>
										Barcode missing/damaged?
									</option>
									<option v-for="(borrower) in borrowers" :value="borrower.id">
											{{ '[' + borrower.barcode + '] ' + borrower.name }}
									</option>
								</select>
								<div class="bg-red-200">
									{{ errors.borrower_id }}
								</div>
							</div>

							<div>
								<Label value="Book Barcode"/>
								<Input
									id="itemOutBarcode"
									class="w-3/4 border-2"
									maxlength="20"
									title="Enter barcode or select from list"
									style="text-transform:uppercase"
									v-model="form2.itemBarcode"
									v-on:change="readItemOutBarcode()"
								/>
							</div>

							<div class="mt-1">
								<!-- <Label value="Book"/> -->
								<select
									id="book"
									v-model="form2.inventory_item_id"
									v-on:click="form2.itemBarcode = '';"
									class="w-3/4 border-2"
									required
								>
									<option value="" selected>
										Barcode missing/damaged?
									</option>
									<option v-for="(inventoryItem) in inventoryItemsOffLoan" :value="inventoryItem.id">
										{{ '[' + inventoryItem.barcode + '] ' + inventoryItem.book.title + ' (copy ' + inventoryItem.copy_no + ')' }}
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
									v-model="form2.borrowed_on"
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
									v-model="form2.due_back"
									class="w-3/4 border-2"
									required
								/>
								<div class="bg-red-200">
									{{ errors.due_back }}
								</div>
							</div>

							<div class="mt-2">
								<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
									Book out now
								</Button>
							</div>
						</form>
					</td>
				</tr>
			</table>

			<div>
				<Link href="/dashboard" method="get" type="button" as="button" class="bg-yellow-200 w-24 border-yellow-300 border-2 rounded m-2">Dashboard</Link>
			</div>

		</div>
	</div>
	</BreezeAuthenticatedLayout>
</template>
