<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
	canLogin: Boolean,
	canRegister: Boolean,
	laravelVersion: String,
	phpVersion: String,
})
</script>

<template>
	<Head title="Start here" />

	<div class="min-h-screen bg-gray-100">
		<div v-if="canLogin" class="text-right px-6 py-4">
			<Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 underline">
				Dashboard
			</Link>

			<template v-else>
				<BreezeApplicationLogo class="inline float-left h-9 w-auto" />

				<Link :href="route('login')" class="text-sm text-gray-700 underline">
					Log in
				</Link>

				<Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
					Register
				</Link>
			</template>
		</div>

		<div class="p-6 bg-gray-100 text-center text-4xl font-bold">
			<h2>Our Library</h2>
			<img src="img/bookshelf.jpg" style="height:400px;margin:auto" />
		</div>

		<div class="w-full pr-6 text-right text-gray-500">
			Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
		</div>
	</div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>
