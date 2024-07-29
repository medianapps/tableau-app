<template>
    <div v-if="!isOnline" class="alert alert-warning">
        You are currently offline. Some features may not be available.
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const isOnline = ref(navigator.onLine);

const updateOnlineStatus = () => {
    isOnline.value = navigator.onLine;
    console.log(isOnline.value);
};

onMounted(() => {
    window.addEventListener("online", updateOnlineStatus);
    window.addEventListener("offline", updateOnlineStatus);
});

onBeforeUnmount(() => {
    window.removeEventListener("online", updateOnlineStatus);
    window.removeEventListener("offline", updateOnlineStatus);
});
</script>

<style scoped>
.alert {
    position: fixed;
    top: 0;
    width: 100%;
    text-align: center;
    z-index: 1000;
}
</style>
