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
			generalCategory: Object
	});

	const form = useForm({
		name: props.generalCategory.name
	});
</script>

<template>
    <Head title="Show Department" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Show Department
            </h2>
        </template>

        <div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
						<form>
							<div>
								<Label value="Name"/>
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
							<Link :href="`/generalCategories/${generalCategory.id}/edit`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Edit</Link>
							<Link href="/generalCategories" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
							<Link href="/generalCategories/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
						</div>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
