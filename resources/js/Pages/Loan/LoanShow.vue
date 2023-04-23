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
			loan: Object
	});

	const form = useForm({
		borrowerName: props.loan.borrower.name,
		bookTitle:    props.loan.inventory_item.book.title,
		copyNo:       props.loan.inventory_item.copy_no,
		borrowedOn:   props.loan.borrowed_on,
		dueBack:      props.loan.due_back,
		returnedOn:   props.loan.returned_on
	});

	function getColour() {
		if (props.loan.returned_on) {
			return "default";
		} else {
			return "transparent";
		}
	}
</script>

<template>
<pre style="display:none;font-size:xx-small">
	{{ loan }}
</pre>
	<Head title="Show loan" />

	<BreezeAuthenticatedLayout>

		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Show Loan
			</h2>
		</template>

		<div class="flex flex-col justify-center items-left pt-6 pl-6 sm:pt-0 bg-gray-100">
			<Errors v-bind:errors="errors"></Errors>
			<div class="w-full sm:max-w-xl mt-6 px-6 pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
				<form>
					<div>
						<Label value="Borrower"/>
						<Input
							id="borrowerName"
							v-model="form.borrowerName"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.borrowerName }}
						</div>
					</div>

					<div>
						<Label value="Book"/>
						<Input
							id="bookTitle"
							v-model="form.bookTitle"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.bookTitle }}
						</div>
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
							{{ errors.copyNo }}
						</div>
					</div>

					<div>
						<Label value="Borrowed on"/>
						<Input
							type="date"
							id="borrowedOn"
							v-model="form.borrowedOn"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.borrowedOn }}
						</div>
					</div>

					<div>
						<Label value="Due back"/>
						<Input
							type="date"
							id="dueBack"
							v-model="form.dueBack"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.dueBack }}
						</div>
					</div>

					<div>
						<Label value="Returned on"/>
						<Input
							type="date"
							id="returnedOn"
							v-model="form.returnedOn"
							v-bind:style="{ color : getColour() }"
							class="w-3/4 border-2"
							disabled
						/>
						<div class="bg-red-200">
							{{ errors.returnedOn }}
						</div>
					</div>

				</form>

				<div>
					<Link :href="`/loans/${loan.id}/edit`" method="get" type="button" as="button" class="bg-yellow-200 w-24 border-yellow-300 border-2 rounded m-2">Edit</Link>
					<Link href="/loans" method="get" type="button" as="button" class="bg-yellow-200 w-24 border-yellow-300 border-2 rounded m-2">Index</Link>
					<Link href="/loans/create" method="get" type="button" as="button" class="bg-yellow-200 w-24 border-yellow-300 border-2 rounded m-2">New loan</Link>
					<Link href="/loans/return" method="get" type="button" as="button" class="bg-yellow-200 w-24 border-yellow-300 border-2 rounded m-2">New return</Link>
				</div>
			</div>
		</div>

	</BreezeAuthenticatedLayout>
</template>
