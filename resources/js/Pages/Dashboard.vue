<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray -800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <tableau-viz
                            id="tableau-viz"
                            src="https://prod-apsoutheast-a.online.tableau.com/t/gforceid152d2772da457/views/Testingdashboard/ExecutiveDashboard"
                            token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImlzcyI6IjczNDAzM2FmLTJkNWYtNDExOS1hYjU5LTNlMjMxNjBhYmI4NSIsImtpZCI6ImNlYTI4N2Y4LTk4MmUtNDA1Zi04ODRjLTM2NWUyM2NlMWZkOCJ9.eyJzdWIiOiJnZm9yY2VpZDE1MkBnbWFpbC5jb20iLCJhdWQiOiJ0YWJsZWF1IiwibmJmIjoxNzIxNjQyNTUxLCJqdGkiOiIxNzIxNjQyNjUxIiwiaXNzIjoiNzM0MDMzYWYtMmQ1Zi00MTE5LWFiNTktM2UyMzE2MGFiYjg1Iiwic2NwIjpbInRhYmxlYXU6dmlld3M6ZW1iZWQiXSwiZXhwIjoxNzIxNjQyNzUxfQ.JFEB-WF0dqc7Vw5-g0P279MkZnb36aFkGg_otEIsDM0"
                        ></tableau-viz>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
