<script setup>
import { ref, computed, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Select from "primevue/select";
import { useToast } from "primevue/usetoast";
import PickList from "primevue/picklist";

const toast = useToast();
const props = defineProps({
    users: Array,
    role_list: Array,
    permission_list: Array,
});

const visible = ref(false);
const isEditMode = ref(false);
const selectedUser = ref(null);
const searchQuery = ref("");
const deleteConfirmation = ref(false);

const form = useForm({
    id: null,
    name: "",
    email: "",
    role_id: "",
    permissions: [],
});

const filteredUsers = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return props.users.filter((user) => {
        return (
            user.name.toLowerCase().includes(query) ||
            user.email.toLowerCase().includes(query) ||
            (user.role || "").toLowerCase().includes(query)
        );
    });
});

const handleShow = (user) => {
    const role = props.role_list.find((role) => role.name === user.role);
    if (role) {
        user.role_id = role.id;
    }

    permissions.value = [props.permission_list, user.permissions];

    selectedUser.value = user;
    isEditMode.value = false;
    visible.value = true;
};

const handleEdit = (user) => {
    const role = props.role_list.find((role) => role.name === user.role);
    if (role) {
        user.role_id = role.id;
    }
    permissions.value = [props.permission_list, user.permissions];

    selectedUser.value = user;
    isEditMode.value = true;
    visible.value = true;
};

const handleDelete = (user) => {
    deleteConfirmation.value = true;
    selectedUser.value = user;
};

const confirmDelete = (user) => {
    form.id = user.id;

    form.delete(route("users.destroy", form.id), {
        onSuccess: () => {
            deleteConfirmation.value = false;
            selectedUser.value = null;
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "User deleted successfully",
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete user",
                life: 3000,
            });
        },
    });
};

const handleSave = () => {
    form.id = selectedUser.value.id;
    form.permissions = permissions.value[1].map((permission) => permission.id);
    form.put(route("users.save", form.id), {
        onSuccess: () => {
            visible.value = false;
            selectedUser.value = null;
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "User changed successfully",
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to change user",
                life: 3000,
            });
        },
    });
};

const roles = ref(props.role_list.filter((role) => role.name !== "admin"));
const permissions = ref([props.permission_list, []]);

watch(
    selectedUser,
    (newUser) => {
        if (newUser) {
            form.name = newUser.name;
            form.email = newUser.email;
            form.role_id = newUser.role_id;
            form.permissions = newUser.permissions.map(
                (permission) => permission.id
            );
        }
    },
    { immediate: true }
);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Superstore
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5"
                >
                    <!-- Search Input -->
                    <div class="mb-4">
                        <InputText
                            v-model="searchQuery"
                            placeholder="Search by name, email, or role"
                            class="w-full"
                        />
                    </div>

                    <!-- DataTable -->
                    <DataTable
                        :value="filteredUsers"
                        paginator
                        :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} to {last} of {totalRecords}"
                        v-if="filteredUsers.length > 0"
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
                            field="email"
                            header="Email"
                            sortable
                            style="width: 25%"
                        />
                        <Column
                            field="role"
                            header="Group"
                            sortable
                            class="capitalize"
                            style="width: 25%"
                        >
                            <template #body="{ data }">
                                <span>{{
                                    data.role === null ? "-" : data.role
                                }}</span>
                            </template>
                        </Column>
                        <Column
                            field="action"
                            header="Action"
                            style="width: 25%"
                        >
                            <template #body="{ data }">
                                <div class="flex gap-2">
                                    <Button
                                        type="button"
                                        outlined
                                        rounded
                                        severity="info"
                                        icon="pi pi-eye"
                                        @click="handleShow(data)"
                                    />
                                    <Button
                                        type="button"
                                        outlined
                                        rounded
                                        severity="success"
                                        icon="pi pi-pencil"
                                        @click="handleEdit(data)"
                                    />
                                    <Button
                                        type="button"
                                        outlined
                                        rounded
                                        severity="danger"
                                        icon="pi pi-trash"
                                        @click="handleDelete(data)"
                                    />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                    <div v-else class="text-center">No data available</div>
                </div>
            </div>
        </div>

        <!-- User Profile Dialog -->
        <Dialog
            v-model:visible="visible"
            modal
            header="User Profile"
            :style="{ width: 'auto' }"
        >
            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{
                    isEditMode
                        ? "Update your information."
                        : "View user information."
                }}
            </span>
            <div class="flex items-center gap-4 mb-4">
                <label for="username" class="font-semibold w-24"
                    >Username</label
                >
                <InputText
                    id="username"
                    v-model="form.name"
                    class="flex-auto"
                    :disabled="!isEditMode"
                    autocomplete="off"
                />
            </div>
            <div class="flex items-center gap-4 mb-4">
                <label for="role" class="font-semibold w-24">Group</label>
                <Select
                    v-model="form.role_id"
                    editable
                    :options="roles"
                    optionLabel="name"
                    placeholder="Select role"
                    class="rounded"
                    optionValue="id"
                    :disabled="!isEditMode"
                />
            </div>
            <!-- <div class="flex items-center gap-4 mb-4">
                <label for="permission" class="font-semibold w-24"
                    >Permissions</label
                >
                <PickList
                    v-model="permissions"
                    dataKey="id"
                    breakpoint="1400px"
                    scrollHeight="20rem"
                    :disabled="!isEditMode"
                >
                    <template #option="{ option, selected }">
                        {{ option.name }}
                    </template>
                </PickList>
            </div> -->
            <div class="flex items-center gap-4 mb-8">
                <label for="email" class="font-semibold w-24">Email</label>
                <InputText
                    id="email"
                    v-model="form.email"
                    class="flex-auto"
                    :disabled="!isEditMode"
                    autocomplete="off"
                />
            </div>
            <div class="flex justify-end gap-2">
                <Button
                    type="button"
                    label="Cancel"
                    severity="secondary"
                    @click="visible = false"
                />
                <Button
                    type="button"
                    label="Save"
                    v-if="isEditMode"
                    @click="handleSave"
                />
            </div>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog
            v-model:visible="deleteConfirmation"
            modal
            header="Delete Confirmation"
        >
            <div class="text-center">
                <p>Are you sure you want to delete this user?</p>
                <div class="flex justify-center gap-4 mt-4">
                    <Button
                        label="Yes"
                        icon="pi pi-check"
                        severity="danger"
                        @click="() => confirmDelete(selectedUser.value)"
                    />
                    <Button
                        label="No"
                        icon="pi pi-times"
                        @click="
                            () => {
                                deleteConfirmation.value = false;
                            }
                        "
                    />
                </div>
            </div>
        </Dialog>
    </AuthenticatedLayout>
</template>
