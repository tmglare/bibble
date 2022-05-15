<script setup>
	import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
	import Label from '@/Components/Label.vue';
	import Input from '@/Components/Input.vue';
	import { Head } from '@inertiajs/inertia-vue3';

	const props = defineProps({
			author: Object
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

        <div class="flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
					<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
						<form>
							<Label value="Name"/>
							<Input id="name" name="name" class="border-2"/>
						</form>
					</div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
