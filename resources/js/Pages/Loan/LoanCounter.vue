<script setup>
	import { useForm } from '@inertiajs/inertia-vue3';
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Errors from '@/Components/Errors.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';
	import { computed } from 'vue';
	import { ref } from 'vue';
	import { watch } from 'vue';
	import { Inertia } from '@inertiajs/inertia';

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
	const noItemInBarcodeClass = ref("hidden");
	const noItemOutBarcodeClass = ref("hidden");
	const noBorrowerBarcodeClass = ref("hidden");

	const form2 = useForm({
		borrowerBarcode:   "",
		itemBarcode:       "",
		borrower_id:       "",
		inventory_item_id: "",
		borrowerName:      "",
		itemName:          "",
		borrowed_on:       props.loan.borrowed_on.split("T")[0],
		due_back:          props.loan.due_back.split("T")[0]
	});

	function readBorrowerBarcode() {
		let barcode = document.getElementById("borrowerBarcode").value;

		axios.get(
			`/borrowers/getBorrowerByBarcode/${barcode}`
		).then(
			(response) => {
				form2.borrower_id = response.data.id;
				form2.borrowerName = response.data.name;
				this.searchBorrowerName = "";
				this.selectedBorrowerName = "";
				this.hasBorrowerBarcode();
			}
		);
	}

	function readItemInBarcode() {
		let barcode = document.getElementById("itemInBarcode").value;

		axios.get(
			`/inventoryItems/byBarcode/${barcode}`
		).then(
			(response) => {
				form1.inventory_item_id = response.data.id;
				form1.itemName = response.data.book.title;
				form1.copyNo = response.data.copy_no;
				this.searchItemOnLoanTitle = "";
				this.selectedItemOnLoanTitle = "";
				this.hasItemInBarcode();
			}
		);
	}

	function noItemInBarcode() {
		noItemInBarcodeClass.value = "";
	}

	function hasItemInBarcode() {
		noItemInBarcodeClass.value = "hidden";
	}

	function noItemOutBarcode() {
		noItemOutBarcodeClass.value = "";
	}

	function hasItemOutBarcode() {
		noItemOutBarcodeClass.value = "hidden";
	}

	function noBorrowerBarcode() {
		noBorrowerBarcodeClass.value = "";
	}

	function hasBorrowerBarcode() {
		noBorrowerBarcodeClass.value = "hidden";
	}

	function readItemOutBarcode() {
		let barcode = document.getElementById("itemOutBarcode").value;

		axios.get(
			`/inventoryItems/byBarcode/${barcode}`
		).then(
			(response) => {
				form2.inventory_item_id = response.data.id;
				form2.itemName = response.data.book.title;
				form2.copyNo = response.data.copy_no;
				this.searchItemOffLoanTitle = "";
				this.selectedItemOffLoanTitle = "";
				this.hasItemOutBarcode();
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

	let searchItemOnLoanTitle = ref('');
	let selectedItemOnLoanTitle = ref('');

	let searchItemOffLoanTitle = ref('');
	let selectedItemOffLoanTitle = ref('');

	let searchBorrowerName = ref('');
	let selectedBorrowerName = ref('');

	const selectItemIn = (item) => {
		selectedItemOnLoanTitle.value = item.book.title;
		searchItemOnLoanTitle.value = item.book.title;
		form1.itemBarcode = item.barcode;
		
		axios.get(
			`/inventoryItems/byBarcode/${item.barcode}`
		).then(
			(response) => {
				form1.inventory_item_id = response.data.id;
				form1.itemName = response.data.book.title;
				form1.copyNo = response.data.copy_no;
			}
		);
	}

	const searchItemsOnLoan = computed(
		() => {
			if (searchItemOnLoanTitle === '') {
				return [];
			}
			return props.inventoryItemsOnLoan.filter (
				itemOnLoan => {
					if (itemOnLoan.book.title.toLowerCase().includes(searchItemOnLoanTitle.value.toLowerCase())) {
						return true;
					}
					return false;
				}
			);
		}
	);

	const selectItemOut = (item) => {
		selectedItemOffLoanTitle.value = item.book.title;
		searchItemOffLoanTitle.value = item.book.title;
		form2.itemBarcode = item.barcode;
		
		axios.get(
			`/inventoryItems/byBarcode/${item.barcode}`
		).then(
			(response) => {
				form2.inventory_item_id = response.data.id;
				form2.itemName = response.data.book.title;
				form2.copyNo = response.data.copy_no;
			}
		);
	}
	const searchItemsOffLoan = computed(
		() => {
			if (searchItemOffLoanTitle === '') {
				return [];
			}
			return props.inventoryItemsOffLoan.filter (
				itemOffLoan => {
					if (itemOffLoan.book.title.toLowerCase().includes(searchItemOffLoanTitle.value.toLowerCase())) {
						return true;
					}
					return false;
				}
			);
		}
	);

	const searchBorrowers = computed(
		() => {
			if (searchBorrowerName === '') {
				return [];
			}
			return props.borrowers.filter (
				borrower => {
					if (borrower.name.toLowerCase().includes(searchBorrowerName.value.toLowerCase())) {
						return true;
					}
					return false;
				}
			);
		}
	);

	const selectBorrower = (borrower) => {
		selectedBorrowerName.value = borrower.name;
		searchBorrowerName.value = borrower.name;
		form2.borrowerBarcode = borrower.barcode;
		
		axios.get(
			`/borrowers/getBorrowerByBarcode/${borrower.barcode}`
		).then(
			(response) => {
				form2.borrower_id = response.data.id;
				form2.borrowerName = response.data.name;
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

							<div>
								<button
									type="button"
									class="text-xs text-blue-600"
									v-on:click="noItemInBarcode"
								>
									No barcode?
								</button>
							</div>

							<div v-bind:class="noItemInBarcodeClass">
								<Label value="Search title"/>
								<Input
									id="searchItemOnLoanTitle"
									v-model="searchItemOnLoanTitle"
									class="w-3/4 border-2"
									max-length="100"
									autocomplete="off"
								/>
							</div>

							<div
								v-if="searchItemsOnLoan.length && (selectedItemOnLoanTitle != searchItemOnLoanTitle)"
								class="border border-black p-2 bg-gray-200"
							>
								<ul>
									<li>
										Showing {{ searchItemsOnLoan.length }} of {{ props.inventoryItemsOnLoan.length }}
									</li>
									<li
										v-for="item in searchItemsOnLoan"
										v-on:click="selectItemIn(item)"
										v-if="selectedItemOnLoanTitle != searchItemOnLoanTitle"
									>
										{{ item.book.title }} (Copy {{ item.copy_no }}) {{ item.barcode }}
									</li>
								</ul>
							</div>

							<div>
								<Label value="Item ID"/>
								<Input
									id="itemInID"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form1.inventory_item_id"
									disabled
								/>
							</div>

							<div>
								<Label value="Title"/>
								<Input
									id="title"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form1.itemName"
									disabled
								/>
							</div>

							<div>
								<Label value="Copy no"/>
								<Input
									id="copyNo"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form1.copyNo"
									disabled
								/>
							</div>

							<div class="mt-2">
								<Button
									type="button"
									v-on:click="validateAndProcessItemIn();"
									class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2"
								>
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

							<div>
								<button
									type="button"
									class="text-xs text-blue-600"
									v-on:click="noBorrowerBarcode"
								>
									No barcode?
								</button>
							</div>

							<div v-bind:class="noBorrowerBarcodeClass">
								<Label value="Search borrower name"/>
								<Input
									id="searchBorrowerName"
									v-model="searchBorrowerName"
									class="w-3/4 border-2"
									max-length="100"
									autocomplete="off"
								/>
							</div>

							<div
								v-if="searchBorrowers.length && (selectedBorrowerName != searchBorrowerName)"
								class="border border-black p-2 bg-gray-200"
							>
								<ul>
									<li>
										Showing {{ searchBorrowers.length }} of {{ props.borrowers.length }}
									</li>
									<li
										v-for="borrower in searchBorrowers"
										v-on:click="selectBorrower(borrower)"
										v-if="selectedBorrowerName != searchBorrowerName"
									>
										{{ borrower.name }} {{ borrower.barcode }}
									</li>
								</ul>
							</div>

							<div>
								<Label value="Borrower ID"/>
								<Input
									id="borrower_id"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form2.borrower_id"
									disabled
								/>
							</div>

							<div class="mb-10">
								<Label value="Borrower name"/>
								<Input
									id="borrowerName"
									class="w-3/4 border-2"
									maxlength="80"
									v-model="form2.borrowerName"
									disabled
								/>
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
<!--
							<div class="mt-1">
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
-->
							<div>
								<button
									type="button"
									class="text-xs text-blue-600"
									v-on:click="noItemOutBarcode"
								>
									No barcode?
								</button>
							</div>

							<div v-bind:class="noItemOutBarcodeClass">
								<Label value="Search title"/>
								<Input
									id="searchItemOffLoanTitle"
									v-model="searchItemOffLoanTitle"
									class="w-3/4 border-2"
									max-length="100"
									autocomplete="off"
								/>
							</div>

							<div
								v-if="searchItemsOffLoan.length && (selectedItemOffLoanTitle != searchItemOffLoanTitle)"
								class="border border-black p-2 bg-gray-200"
							>
								<ul>
									<li>
										Showing {{ searchItemsOffLoan.length }} of {{ props.inventoryItemsOffLoan.length }}
									</li>
									<li
										v-for="item in searchItemsOffLoan"
										v-on:click="selectItemOut(item)"
										v-if="selectedItemOffLoanTitle != searchItemOffLoanTitle"
									>
										{{ item.book.title }} (Copy {{ item.copy_no }}) {{ item.barcode }}
									</li>
								</ul>
							</div>

							<div>
								<Label value="Item ID"/>
								<Input
									id="itemOutID"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form2.inventory_item_id"
									disabled
								/>
							</div>

							<div>
								<Label value="Title"/>
								<Input
									id="itemOutTitle"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form2.itemName"
									disabled
								/>
							</div>

							<div>
								<Label value="Copy no"/>
								<Input
									id="itemOutCopyNo"
									class="w-3/4 border-2"
									maxlength="20"
									v-model="form2.copyNo"
									disabled
								/>
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
								<Button
									type="button"
									class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2"
									v-on:click="form2.post('/loans?counter=1',{ preserveState: false })"
								>
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
