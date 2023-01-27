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
			book: Object,
			authors: Object,
			detailedCategories: Object
	});

	const form = useForm({
		title:                  props.book.title,
		publisher:              props.book.publisher,
		isbn:                   props.book.isbn,
		edition:                props.book.edition,
		first_publication_date: props.book.first_publication_date,
		edition_date:           props.book.edition_date,
		detailed_category_id:   props.book.detailed_category_id,
		author_id:              props.book.author_id
	});
</script>

<template>
	<Head title="Edit title" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Edit Title
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<Errors v-bind:errors="errors"></Errors>

			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form v-on:submit.prevent="form.patch(`/books/${book.id}`)">
					<div>
						<Label value="Category"/>
						<select
							id="category"
							v-model="form.detailed_category_id"
							class="w-3/4 border-2"
							required
						>
							<option v-for="(name, id) in detailedCategories" :value="id">
								{{ name }}
							</option>
						</select>
					</div>

					<div>
						<Label value="Author"/>
						<select
							id="author"
							v-model="form.author_id"
							class="w-3/4 border-2"
							required
						>
							<option v-for="(name, id) in authors" :value="id">
								{{ name }}
							</option>
						</select>
					</div>

					<div>
						<Label value="Title"/>
						<Input
							id="title"
							v-model="form.title"
							class="w-3/4 border-2"
							maxlength="80"
							required
						/>
						<div class="bg-red-200">
							{{ errors.title }}
						</div>
					</div>

					<div>
						<Label value="Publisher"/>
						<Input
							id="publisher"
							v-model="form.publisher"
							class="w-3/4 border-2"
							maxlength="80"
							required
						/>
						<div class="bg-red-200">
							{{ errors.publisher }}
						</div>
					</div>

					<div>
						<Label value="ISBN"/>
						<Input
							id="isbn"
							v-model="form.isbn"
							class="w-3/4 border-2"
							maxlength="20"
							required
						/>
						<div class="bg-red-200">
							{{ errors.isbn }}
						</div>
					</div>

					<div>
						<Label value="Edition"/>
						<Input
							id="edition"
							v-model="form.edition"
							class="w-3/4 border-2"
							maxlength="40"
						/>
						<div class="bg-red-200">
							{{ errors.edition }}
						</div>
					</div>

					<div>
						<Label value="First published"/>
						<Input
							id="first_publication_date"
							v-model="form.first_publication_date"
							class="w-3/4 border-2"
							maxlength="40"
						/>
						<div class="bg-red-200">
							{{ errors.first_publication_date }}
						</div>
					</div>

					<div>
						<Label value="This edition published"/>
						<Input
							id="edition_date"
							v-model="form.edition_date"
							class="w-3/4 border-2"
							maxlength="40"
						/>
						<div class="bg-red-200">
							{{ errors.edition_date }}
						</div>
					</div>

					<div class="mt-2">
						<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
							Save
						</Button>
					</div>
				</form>

				<div>
					<Link :href="`/books/${book.id}`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">View</Link>
					<Link href="/books" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
					<Link href="/books/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
