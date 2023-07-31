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
		errors: Object
	});

	const form = useForm({
		itemBarcode:       "",
		inventory_item_id: "",
		itemName:          "",
		copyNo:            null
	});

	const ibcError = ref("");

	function readItemBarcode() {
		if (! itemBarcode.value) {
			// ibcError.value = "Barcode required";
			return false;
		}

		ibcError.value = "";

		axios.get(
			`/inventoryItems/getItemByBarcode/${itemBarcode.value}`
		).then(
			(response) => {
				if (response) {
					if (response.data) {
						let id   = response.data.id;
						let name = response.data.book.title;
						let copyNo = response.data.copy_no;
						if ( id && name) {
							form.inventory_item_id = id;
							form.itemName          = name;
							form.copyNo            = copyNo;
							ibcError.value = "";
							return true;
						}
					}
				}

				form.itemName          = "";
				form.copyNo            = null;
				form.inventory_item_id = "";
				ibcError.value = "Invalid code";
				// itemBarcode.focus();
				return false;
			}
		);
	}

	function validateForm() {
		if (! itemBarcode.value) {
			ibcError.value = "Barcode required";
			return false;
		}

		axios.get(
			`/inventoryItems/getItemByBarcode/${itemBarcode.value}`
		).then(
			(response) => {
				if (response) {
					if (response.data) {
						let id   = response.data.id;
						let name = response.data.book.title;
						if ( id && name) {
							form.inventory_item_id = id;
							form.itemName          = name;
							ibcError.value = "";
							form.post("/loans/processReturn");
							return true;
						}
					}
				}

				form.itemName          = "";
				form.inventory_item_id = "";
				ibcError.value = "Invalid code";
				// itemBarcode.focus();
				return false;
			}
		);
	}
</script>

<template>
	<Head title="Return" />

	<BreezeAuthenticatedLayout>

	<template #header>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Return
		</h2>
	</template>

	<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
		<Errors v-bind:errors="errors"></Errors>
		<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<!-- <form v-on:submit.prevent="validateForm();form.post(`/loans/processReturn`)"> -->
			<form v-on:submit.prevent="validateForm();">
				<div>
					<Label value="Book Barcode"/>
					<Input
						id="itemBarcode"
						class="w-3/4 border-2"
						maxlength="20"
						title="Enter barcode"
						style="text-transform:uppercase"
						v-model="form.itemBarcode"
						v-on:focusout='readItemBarcode()'
						required
					/>
					<div class="bg-red-200">
						{{ ibcError }}
					</div>
				</div>

				<div class="mt-1">
					<input type="hidden" id="inventory_item_id" v-model="form.inventory_item_id" required>

					<Label value="Title"/>
					<Input
						v-model="form.itemName"
						id="itemName"
						class="w-3/4 border-2"
						disabled
					/>
					<div class="bg-red-200">
						{{ errors.itemName }}
					</div>
				</div>

				<div class="mt-1">
					<Label value="Copy no"/>
					<Input
						v-model="form.copyNo"
						id="copyNo"
						class="w-3/4 border-2"
						disabled
					/>
					<div class="bg-red-200">
						{{ errors.copyNo }}
					</div>
				</div>

				<div class="mt-2">
					<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
						Return now
					</Button>
				</div>
			</form>
		</div>
	</div>

	</BreezeAuthenticatedLayout>
</template>
