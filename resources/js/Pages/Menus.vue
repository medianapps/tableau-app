<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import { ref, watch } from "vue";
import PickList from "primevue/picklist";
import Chip from "primevue/chip";
import AutoComplete from "primevue/autocomplete";

const props = defineProps({
    menus: Array,
    roles: Array,
});

const displayAddModal = ref(false);
const displayEditModal = ref(false);
const newMenu = useForm({
    name: "",
    slug: "",
    workbook: "",
    view: "",
    group: "",
    icon: "",
});

const editMenu = useForm({
    id: null,
    name: "",
    slug: "",
    workbook: "",
    view: "",
    group: "",
    icon: "",
});

const roles = ref([props.roles, []]);

const filterRoles = (selectedRoles) => {
    return props.roles.filter((role) => !selectedRoles.includes(role));
};

const onAddMenu = () => {
    roles.value = [props.roles, []];
    displayAddModal.value = true;
};

const onSaveMenu = () => {
    newMenu.group = roles.value[1];
    newMenu.icon = selectedIcon.value;
    newMenu.post(route("menu.store"), {
        onSuccess: () => {
            displayAddModal.value = false;
        },
        onError: () => {
            console.error("Failed to save the menu");
        },
    });
};

const onEditMenu = (menu) => {
    editMenu.id = menu.id;
    editMenu.name = menu.name;
    editMenu.slug = menu.slug;
    editMenu.workbook = menu.workbook;
    editMenu.view = menu.view;
    editMenu.group = menu.group;
    editMenu.icon = menu.icon ?? null;
    selectedIcon.value = menu.icon ?? null;

    const selectedRoles = JSON.parse(menu.group);
    roles.value = [filterRoles(selectedRoles), selectedRoles];
    displayEditModal.value = true;
};

const onUpdateMenu = () => {
    editMenu.group = roles.value[1];
    editMenu.icon = selectedIcon.value;

    editMenu.patch(route("menu.update", editMenu.id), {
        onSuccess: () => {
            displayEditModal.value = false;
        },
        onError: () => {
            console.error("Failed to update the menu");
        },
    });
};

const onDeleteMenu = (menuId) => {
    const form = useForm({});
    form.delete(route("menu.destroy", menuId), {
        onSuccess: () => {
            menus.value = menus.value.filter((menu) => menu.id !== menuId);
        },
    });
};

const selectedIcon = ref(null);
const icons = ref([
    "home",
    "users",
    "chart-line",
    "chart-pie",
    "chart-bar",
    "cog",
    "file",
    "file-edit",
    "file-plus",
    "file-minus",
    "file-export",
    "file-import",
    "file-text",
    "file-zip",
    "file-powerpoint",
    "file-music",
    "file-video",
    "file-code",
]);

watch(
    () => newMenu.name,
    (name) => {
        newMenu.slug = name.toLowerCase().replace(/ /g, "-");
    }
);

watch(
    () => editMenu.name,
    (name) => {
        editMenu.slug = name.toLowerCase().replace(/ /g, "-");
    }
);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Menus
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <Button
                            label="Add Menu"
                            icon="pi pi-plus"
                            class="p-button-success"
                            iconPos="right"
                            @click="displayAddModal = true"
                        />

                        <DataTable
                            :value="menus"
                            paginator
                            :rows="5"
                            :rowsPerPageOptions="[5, 10, 20, 50]"
                            tableStyle="min-width: 50rem"
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="{first} to {last} of {totalRecords}"
                            v-if="menus.length > 0"
                        >
                            <Column
                                field="id"
                                sortable
                                style="width: 25%"
                                class="hidden"
                            />
                            <Column
                                field="name"
                                header="Name"
                                sortable
                                style="width: 25%"
                            />
                            <Column
                                field="slug"
                                header="Slug"
                                sortable
                                style="width: 25%"
                            />
                            <Column
                                field="workbook"
                                header="Workbook"
                                sortable
                                style="width: 25%"
                            />
                            <Column
                                field="view"
                                header="View"
                                sortable
                                style="width: 25%"
                            />
                            <Column header="Group" sortable style="width: 25%">
                                <template #body="{ data }">
                                    <div class="flex flex-wrap gap-2">
                                        <Chip
                                            v-for="(item, index) in JSON.parse(
                                                data.group
                                            )"
                                            :key="index"
                                        >
                                            {{ item }}
                                        </Chip>
                                    </div>
                                </template>
                            </Column>

                            <Column header="Actions" style="width: 25%">
                                <template #body="slotProps">
                                    <Button
                                        outlined
                                        rounded
                                        severity="primary"
                                        icon="pi pi-pencil"
                                        @click="onEditMenu(slotProps.data)"
                                    />
                                    <Button
                                        outlined
                                        rounded
                                        severity="danger"
                                        icon="pi pi-trash"
                                        @click="onDeleteMenu(slotProps.data.id)"
                                    />
                                </template>
                            </Column>
                        </DataTable>
                        <div v-else class="text-center">No data available</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Menu Modal -->
        <Dialog header="Add Menu" v-model:visible="displayAddModal" modal>
            <div class="p-fluid flex gap-2 flex-col">
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="name">Name</label>
                    <InputText
                        id="name"
                        class="w-full"
                        v-model="newMenu.name"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="slug">Slug</label>
                    <InputText
                        id="slug"
                        class="w-full"
                        v-model="newMenu.slug"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="slug">Icon</label>
                    <div class="flex gap-2 w-full">
                        <InputText v-model="selectedIcon" />
                        <div
                            v-if="selectedIcon"
                            class="icon-preview p-2 flex items-center gap-2 border border-info rounded-lg w-full"
                        >
                            <i
                                :class="'pi pi-' + selectedIcon"
                                style="font-size: 1.5rem"
                            ></i>
                            <span class="capitalize">
                                {{ newMenu.name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="workbook">Workbook</label>
                    <InputText
                        id="workbook"
                        class="w-full"
                        v-model="newMenu.workbook"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="view">View</label>
                    <InputText
                        id="view"
                        class="w-full"
                        v-model="newMenu.view"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="group">Group</label>
                    <PickList
                        class="w-full"
                        v-model="roles"
                        breakpoint="1400px"
                    >
                        <template #option="{ option }">
                            {{ option }}
                        </template>
                    </PickList>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <Button
                    label="Cancel"
                    class="p-button-text"
                    @click="displayAddModal = false"
                />
                <Button
                    label="Save"
                    class="p-button-primary"
                    @click="onSaveMenu"
                />
            </div>
        </Dialog>

        <!-- Edit Menu Modal -->
        <Dialog header="Edit Menu" v-model:visible="displayEditModal" modal>
            <div class="p-fluid flex gap-2 flex-col">
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="name">Name</label>
                    <InputText
                        id="name"
                        class="w-full"
                        v-model="editMenu.name"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="slug">Slug</label>
                    <InputText
                        id="slug"
                        class="w-full"
                        v-model="editMenu.slug"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="slug">Icon</label>
                    <div class="flex gap-2 w-full">
                        <InputText v-model="selectedIcon" />
                        <div
                            v-if="selectedIcon"
                            class="icon-preview p-2 flex items-center gap-2 border border-info rounded-lg w-full"
                        >
                            <i
                                :class="'pi pi-' + selectedIcon"
                                style="font-size: 1.5rem"
                            ></i>
                            <span class="capitalize">
                                {{ editMenu.name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="workbook">Workbook</label>
                    <InputText
                        id="workbook"
                        class="w-full"
                        v-model="editMenu.workbook"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="view">View</label>
                    <InputText
                        id="view"
                        class="w-full"
                        v-model="editMenu.view"
                    />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label class="w-24" for="group">Group</label>
                    <PickList
                        class="w-full"
                        v-model="roles"
                        breakpoint="1400px"
                    >
                        <template #option="{ option }">
                            {{ option }}
                        </template>
                    </PickList>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <Button
                    label="Cancel"
                    class="p-button-text"
                    @click="displayEditModal = false"
                />
                <Button
                    label="Update"
                    class="p-button-primary"
                    @click="onUpdateMenu"
                />
            </div>
        </Dialog>
    </AuthenticatedLayout>
</template>
