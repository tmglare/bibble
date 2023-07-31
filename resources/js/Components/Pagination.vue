<script setup>
	import { Link } from '@inertiajs/inertia-vue3';

	defineProps({
		data: {
			type: Object,
			default: () => ({})
		}
	});
</script>

<template>
	<!--
		If the supplied "data" object is a paginated Eloquent collection, then "links"
		will be the pagination links, i.e. like line $data->links in a blade template
	-->
	<div v-if="data.links.length > 3" class="flex justify-center mt-4 space-x-4">
		<Link
			v-for="(link, index) in data.links"
			:key="index"
			class="px-4 py-2 text-sm leading-4 bg-yellow-200 rounded hover:bg-yellow-300 hover:shadow"
			:class="{'bg-yellow-300 font-bold':link.active}"
			:href="link.url"
			v-html="link.label"
		/>
	</div>
</template>
