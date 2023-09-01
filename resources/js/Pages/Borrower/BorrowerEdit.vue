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
			borrower: Object
	});

	const form = useForm({
		style:     props.borrower.style,
		surname:   props.borrower.surname,
		forenames: props.borrower.forenames,
		street:    props.borrower.street,
		town:      props.borrower.town,
		postcode:  props.borrower.postcode,
		telephone: props.borrower.telephone,
		email:     props.borrower.email,
		barcode:   props.borrower.barcode
	});
</script>

<template>
	<Head title="Edit borrower" />

	<BreezeAuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Edit Borrower
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<Errors v-bind:errors="errors"></Errors>
			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form v-on:submit.prevent="form.patch(`/borrowers/${borrower.id}`)">
					<div>
						<Label value="Style"/>
						<Input
							id="style"
							v-model="form.style"
							class="w-3/4 border-2"
						/>
						<div class="bg-red-200">
							{{ errors.style }}
						</div>
					</div>

					<div>
						<Label value="Forenames"/>
						<Input
							id="forenames"
							v-model="form.forenames"
							class="w-3/4 border-2"
						/>
						<div class="bg-red-200">
							{{ errors.forenames }}
						</div>
					</div>

					<div>
						<Label value="Surname"/>
						<Input
							id="surname"
							v-model="form.surname"
							class="w-3/4 border-2"
							required
						/>
						<div class="bg-red-200">
							{{ errors.surname }}
						</div>
					</div>

					<div>
						<Label value="Street"/>
						<textarea
							id="street"
							v-model="form.street"
							class="w-3/4 border-2 border-gray-300 rounded-md shadow-sm"
							rows="3"
							required
						>
						</textarea>
						<div class="bg-red-200">
							{{ errors.street }}
						</div>
					</div>

					<div>
						<Label value="Town"/>
						<Input
							id="town"
							v-model="form.town"
							class="w-3/4 border-2 uppercase"
							required
						/>
						<div class="bg-red-200">
							{{ errors.town }}
						</div>
					</div>

					<div>
						<Label value="Postcode"/>
						<Input
							id="postcode"
							v-model="form.postcode"
							class="w-3/4 border-2 uppercase"
							required
						/>
						<div class="bg-red-200">
							{{ errors.postcode }}
						</div>
					</div>

					<div>
						<Label value="Telephone"/>
						<Input
							id="telephone"
							v-model="form.telephone"
							class="w-3/4 border-2"
						/>
						<div class="bg-red-200">
							{{ errors.telephone }}
						</div>
					</div>

					<div>
						<Label value="Email"/>
						<Input
							id="email"
							v-model="form.email"
							class="w-3/4 border-2"
						/>
						<div class="bg-red-200">
							{{ errors.email }}
						</div>
					</div>

					<div>
						<Label value="Barcode"/>
						<Input
							id="barcode"
							v-model="form.barcode"
							class="w-3/4 border-2"
						/>
						<div class="bg-red-200">
							{{ errors.barcode }}
						</div>
					</div>
					<div class="mt-2">
						<Button class="text-gray-800 bg-green-400 hover:bg-green-500  active:bg-green-500  focus:bg-green-500 ml-2">
							Save
						</Button>
					</div>
				</form>

				<div>
					<Link :href="`/borrowers/${borrower.id}`" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Show</Link>
					<Link href="/borrowers" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">Index</Link>
					<Link href="/borrowers/create" method="get" type="button" as="button" class="bg-yellow-200 w-20 border-yellow-300 border-2 rounded m-2">New</Link>
				</div>

			</div>
		</div>
	</BreezeAuthenticatedLayout>
</template>
