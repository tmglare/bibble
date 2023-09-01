<script setup>
	import { Link } from '@inertiajs/inertia-vue3';

	defineProps(
		{
			url : {
				type: String,
				default: null
			},
			columnName : {
				type: String,
				default: null
			},
			borrowerId : {
				type: String,
				default: null
			}
		}
	);
</script>

<template>
	<div class="inline-block align-middle">
		<div style="line-height:0.65em">
			<Link v-if="(borrowerId === undefined) || (borrowerId == null)" class="ml-1 font-semibold" style="cursor:none" :href="`/${url}?columnName=${columnName}&direction=asc`" title="Sort ascending" method="get">
				<i class="fa-solid fa-caret-up fa-lg hover:text-blue-400"></i>
			</Link>
			<Link v-else class="ml-1 font-semibold" style="cursor:none" :href="`/${url}?borrowerId=${borrowerId}&columnName=${columnName}&direction=asc`" title="Sort ascending" method="get">
				<i class="fa-solid fa-caret-up fa-lg hover:text-blue-400"></i>
			</Link>
		</div>

		<div style="line-height:0.65em">
			<Link v-if="(borrowerId === undefined) || (borrowerId == null)" class="ml-1 font-semibold" style="cursor:none" :href="`/${url}?columnName=${columnName}&direction=desc`" title="Sort descending" method="get">
				<i class="fa-solid fa-caret-down fa-lg hover:text-blue-400"></i>
			</Link>
			<Link v-else  class="ml-1 font-semibold" style="cursor:none" :href="`/${url}?borrowerId=${borrowerId}&columnName=${columnName}&direction=desc`" title="Sort descending" method="get">
				<i class="fa-solid fa-caret-down fa-lg hover:text-blue-400"></i>
			</Link>
		</div>
	</div>
</template>
