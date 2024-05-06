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
			author: Object
	});

	const form = useForm({
		name: props.author.name,
		ordered_name: props.author.ordered_name
	});
</script>

<template>
    <Head title="New author" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Author
            </h2>
        </template>

        <div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
						<form v-on:submit.prevent="form.post('/authors')">
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
							<div>
								<Label value="Name (surname first)"/>
								<Input
									id="ordered_name"
									v-model="form.ordered_name"
									class="w-3/4 border-2"
									required
								/>
								<div class="bg-red-200">
									{{ errors.ordered_name }}
								</div>
							</div>
							<div class="mt-2">
								<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
									Save
								</Button>
							</div>
						</form>
						<div>
							<Link href="/authors" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
						</div>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
