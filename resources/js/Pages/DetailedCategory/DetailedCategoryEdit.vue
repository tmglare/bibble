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
			detailedCategory: Object,
			generalCategories: Object
	});

	const form = useForm({
		name: props.detailedCategory.name,
		general_category_id: props.detailedCategory.general_category_id
	});
</script>

<template>
    <Head title="Edit category" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Category
            </h2>
        </template>

        <div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
						<form v-on:submit.prevent="form.patch(`/detailedCategories/${detailedCategory.id}`)">
							<div>
								<Label value="Department"/>
								<select
									id="department"
									v-model="form.general_category_id"
									class="w-3/4 border-2"
									required
								>
									<option v-for="(name, id) in generalCategories" :value="id">
										{{ name }}
									</option>
								</select>

								<div class="bg-red-200">
									{{ errors.general_category_id }}
								</div>
							</div>

							<div>
								<Label value="Name"/>
								<Input
									id="name"
									v-model="form.name"
									class="w-3/4 border-2"
									required
								/>

								<div class="bg-red-200">
									{{ errors.name }}
								</div>
							</div>

							<div class="mt-2">
								<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
									Save
								</Button>
							</div>
						</form>
						<div>
							<Link :href="`/detailedCategories/${detailedCategory.id}`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">View</Link>
							<Link href="/detailedCategories" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
							<Link href="/detailedCategories/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
						</div>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
