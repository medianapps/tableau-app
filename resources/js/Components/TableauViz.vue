<!-- src/components/TableauViz.vue -->
<template>
    <div ref="vizContainer" :id="id" style="width: 100%; height: 600px"></div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    src: {
        type: String,
        required: true,
    },
});

const initTableau = () => {
    const vizContainer = ref();
    const url = props.src;
    const options = {
        width: vizContainer.offsetWidth,
        height: vizContainer.offsetHeight,
        hideTabs: true,
        hideToolbar: true,
    };
    new tableau.Viz(vizContainer, url, options);
};

onMounted(() => {
    initTableau();
});
</script>

<style scoped>
/* Add any additional styling you need here */
</style>
