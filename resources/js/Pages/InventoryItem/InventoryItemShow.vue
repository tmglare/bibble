<script setup>
	import { useForm } from '@inertiajs/inertia-vue3';
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Errors from '@/Components/Errors.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';

	const props = defineProps({
			errors: Object,
			inventoryItem: Object,
	});

	const form = useForm({
		title:                  props.inventoryItem.book.title,
		author:                 props.inventoryItem.book.author.name,
		detailedCategory:       props.inventoryItem.book.detailed_category.name,
		copyNo:                 props.inventoryItem.copy_no,
		barcode:                props.inventoryItem.barcode
	});
</script>

<template>
	<Head title="Show item" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Show Item
			</h2>
		</template>
<pre v-show="false">
{{ JSON.stringify(inventoryItem,null,4) }}
</pre>
		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<Errors v-bind:errors="errors"></Errors>

			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form>
					<div>
						<Label value="Title"/>
						<Input
							id="title"
							v-model="form.title"
							class="w-3/4 border-2"
							maxlength="80"
							disabled
						/>
					</div>

					<div>
						<Label value="Author"/>
						<Input
							id="author"
							v-model="form.author"
							class="w-3/4 border-2"
							disabled
						/>
					</div>

					<div>
						<Label value="Category"/>
						<Input
							id="category"
							v-model="form.detailedCategory"
							class="w-3/4 border-2"
							disabled
						/>
					</div>

					<div>
						<Label value="Copy no"/>
						<Input
							id="copyNo"
							v-model="form.copyNo"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.copy_no }}
						</div>
					</div>

					<div>
						<Label value="Barcode"/>
						<Input
							id="barcode"
							v-model="form.barcode"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.barcode }}
						</div>
					</div>
				</form>

				<div>
					<Link :href="`/inventoryItems/${inventoryItem.id}/edit`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Edit</Link>
					<Link href="/inventoryItems" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
					<Link href="/inventoryItems/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
