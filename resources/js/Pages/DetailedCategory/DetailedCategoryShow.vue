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
			detailedCategory: Object
	});

	const form = useForm({
		name: props.detailedCategory.name,
		department: props.detailedCategory.general_category.name
	});
</script>

<template>
    <Head title="Show Category" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Show Category
            </h2>
        </template>
<!--
<pre>
	{{ JSON.stringify(detailedCategory) }}
</pre>
-->
        <div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
						<form>
							<div>
								<Label value="Department"/>
								<Input
									id="department"
									v-model="form.department"
									class="w-3/4 border-2"
									disabled
								/>
							</div>
							<div>
								<Label value="Category"/>
								<Input
									id="name"
									v-model="form.name"
									class="w-3/4 border-2"
									disabled
								/>
								<div class="bg-red-200">
									{{ errors.name }}
								</div>
							</div>
						</form>
						<div>
							<Link :href="`/detailedCategories/${detailedCategory.id}/edit`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Edit</Link>
							<Link href="/detailedCategories" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
							<Link href="/detailedCategories/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
						</div>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
