<script setup>
defineProps({
	errors : {
		type: Object
	}
});
</script>

<template>
	<div v-if="Object.keys(errors).length" class="max-w-xl shadow-md rounded-lg mt-2 p-2 bg-white">
		<ul>
			<li class="px-2 bg-red-200" v-for="error in errors">
				{{ error }}
			</li>
		</ul>
	</div>
</template>
