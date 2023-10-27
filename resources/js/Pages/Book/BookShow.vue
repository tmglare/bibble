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
	});

	const form = useForm({
		title:                  props.book.title,
		publisher:              props.book.publisher,
		isbn:                   props.book.isbn,
		edition:                props.book.edition,
		first_publication_date: props.book.first_publication_date,
		edition_date:           props.book.edition_date,
		author:                 props.book.author.name,
		detailed_category:      props.book.detailed_category.name,
		copies:                 props.book.copies
	});
</script>

<template>
	<Head title="Show title" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Show Title
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<Errors v-bind:errors="errors"></Errors>

			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form>
					<div>
						<Label value="Category"/>
						<Input
							id="category"
							v-model="form.detailed_category"
							class="w-3/4 border-2"
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
						<Label value="Title"/>
						<Input
							id="title"
							v-model="form.title"
							class="w-3/4 border-2"
							maxlength="80"
							disabled
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
							disabled
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
							disabled
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
							disabled
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
							disabled
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
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.edition_date }}
						</div>
					</div>

					<div>
						<Label value="No of copies"/>
						<Input
							id="copies"
							v-model="form.copies"
							class="w-3/4 border-2"
							disabled
						/>
					</div>
				</form>

				<div>
					<Link :href="`/books/${book.id}/add-copy`" method="get" type="button" as="button" class="bg-yellow-200 w-40 border-yellow-300 border-2 rounded m-2">Add new copy</Link>
					<Link :href="`/books/${book.id}/edit`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Edit</Link>
					<Link href="/books" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
					<Link href="/books/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
