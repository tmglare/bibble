<script setup>
	import $ from 'jquery';
	require("jquery-ui/ui/widgets/autocomplete.js");
	import "jquery-ui/themes/base/theme.css";
	import "jquery-ui/themes/base/autocomplete.css";
	import { useForm } from '@inertiajs/inertia-vue3';
	import { computed } from 'vue';
	import { ref } from 'vue';
	import { watch } from 'vue';
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import Button from '@/Components/Button.vue';
	import { Head } from '@inertiajs/inertia-vue3';
	import { Link } from '@inertiajs/inertia-vue3';
	import { Inertia } from '@inertiajs/inertia';

	const props = defineProps({
			errors: Object,
			book: Object,
			authors: Array,
			detailedCategories: Object
	});

	const form = useForm({
		title: "",
		publisher: "",
		isbn: "",
		edition: "",
		author_id: "",
		author_name: "",
		detailed_category_id: "",
		first_publication_date: "",
		edition_date: "",
		add_inventory_item: 0
	});

	let searchAuthorName = ref('');

	const searchAuthors = computed(
		() => {
			if (searchAuthorName.value === '') {
				return [];
			}
			return props.authors.filter (
				author => {
					if (author.toLowerCase().includes(searchAuthorName.value.toLowerCase())) {
						return true;
					}
					return false;
				}
			);
		}
	);

	let selectedAuthor = ref('');

	const selectAuthor = (author) => {
		searchAuthorName.value = author;
		selectedAuthor.value = author;
	}

	watch(
		selectedAuthor,
		(value) => {
			form.author_name = value;
		}
	);

	watch(
		searchAuthorName,
		(value) => {
			form.author_name = value;
		}
	);
</script>

<template>
	<Head title="New title" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				New Title
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form v-on:submit.prevent="form.post('/books')">
					<div>
						<Label value="Category"/>
						<select
							id="category"
							v-model="form.detailed_category_id"
							class="w-3/4 border-2"
							required
						>
							<option value="">
								Select a category
							</option>
							<option v-for="(detailedCategory) in detailedCategories" :value="detailedCategory.id">
								{{ detailedCategory.name }}
							</option>
						</select>
						<div class="bg-red-200">
							{{ errors.detailed_category_id }}
						</div>
					</div>

					<Input type="hidden" v-model="form.author_name"/>

					<div>
						<Label value="Author name"/>
						<Input
							id="searchAuthorName"
							v-model="searchAuthorName"
							class="w-3/4 border-2"
							maxlength="80"
							autocomplete="off"
							required
						/>
					</div>

					<div
						v-if="searchAuthors.length && (selectedAuthor != searchAuthorName)"
						class="border border-black p-2 bg-gray-200"
					>
						<ul>
							<li>
								Showing {{ searchAuthors.length }} of {{ props.authors.length }}
							</li>
							<li
								v-for="author in searchAuthors"
								v-on:click="selectAuthor(author)"
								v-if="selectedAuthor != searchAuthorName"
							>
								{{ author }}
							</li>
						</ul>
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

					<div>
						<Label value="Add library copy?"/>
						No <input type="radio" v-model="form.add_inventory_item" value="0" class="mr-4">
						Yes <input type="radio" v-model="form.add_inventory_item" value="1">
					</div>

					<div class="mt-2">
						<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
							Save
						</Button>
					</div>
				</form>

				<div>
					<Link href="/books" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
