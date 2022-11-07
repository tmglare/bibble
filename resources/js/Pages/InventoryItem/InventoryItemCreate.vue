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
			books: Object,
			inventoryItem: Object
	});

	const form = useForm({
		copy_no: 1,
		book_id: "",
		notes: ""
	});
</script>

<template>
	<Head title="New item" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				New Item
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form v-on:submit.prevent="form.post('/inventoryItems')">
					<div>
						<Label value="Title"/>
						<select
							id="book"
							v-model="form.book_id"
							class="w-3/4 border-2"
							required
						>
							<option value="">
								Select a title
							</option>
							<option v-for="(book) in books" :value="book.id">
								{{ book.title }}
							</option>
						</select>
						<div class="bg-red-200">
							{{ errors.book_id }}
						</div>
					</div>

					<div>
						<Label value="Copy no"/>
						<Input
							id="copy_no"
							type="number"
							min="1"
							v-model="form.copy_no"
							class="w-3/4 border-2"
							required
						/>
						<div class="bg-red-200">
							{{ errors.copy_no }}
						</div>
					</div>

					<div>
						<Label value="Notes"/>
						<textarea
							id="notes"
							v-model="form.notes"
							class="w-3/4 border-2 border-gray-300 rounded-md shadow-sm"
							rows="3"
						>
						</textarea>
						<div class="bg-red-200">
							{{ errors.notes }}
						</div>
					</div>

					<div class="mt-2">
						<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
							Save
						</Button>
					</div>
				</form>

				<div>
					<Link href="/inventoryItems" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
