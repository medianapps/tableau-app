<!-- resources/js/Pages/Unauthorized.vue -->
<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900"
    >
        <div
            class="max-w-md w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6"
        >
            <h1
                class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4"
            >
                Unauthorized
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                You do not have the necessary permissions to access this page.
            </p>
            <Link href="/" class="text-blue-500 dark:text-blue-300"
                >Go back to the homepage</Link
            >
        </div>
    </div>
</template>
