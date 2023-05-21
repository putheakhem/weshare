<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted } from 'vue';
const toast = useToast();
const submitted = ref(false);
const userDialog = ref(false);
const dt = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = () => {
    dt.value.exportCSV();
};
const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    user: {
        type: Object,
        default: () => ({}),
    },
});

let user = ref({});
onMounted(() => {
    user.value = user;
});

const openNew = (prod) => {
    user.value = { ...prod };
    userDialog.value = true;
};

const hideDialog = () => {
    userDialog.value = false;
};

const saveUser = () => {
    submitted.value = true;
    if (user.value.name) {
        if (user.value.id) {
            const form = useForm({
                id: user.value.id,
                name: user.value.name,
                email: user.value.email,
                status: user.value.status
            });
            form.patch(route("manage_users.update", user.value.id));
        }
    }
    userDialog.value = false;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Status Updated Successfully', life: 3000 });
};

const categories = ref([
    { name: 'Active', key: 'A' },
    { name: 'Inactive', key: 'I' },
]);
</script>

<template>
    <Head title="Manage Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
        </template>
        <Toast />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto p-10">
                        <Toolbar class="mb-4">
                            <template #start>
                                <div class="flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value" placeholder="Search..." />
                                    </span>
                                </div>
                            </template>
                            <template #end>
                                <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                            </template>
                        </Toolbar>
                        <DataTable ref="dt" :value="users" resizableColumns columnResizeMode="expand" showGridlines
                            dataKey="id" :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users">
                            <Column field="name" header="Name" sortable></Column>
                            <Column field="email" header="Email" sortable></Column>
                            <Column field="status" header="Status" sortable>
                                <template #body="slotProps">
                                    <Tag @click="openNew(slotProps.data)" outlined rounded
                                        class="mr-2 hover:cursor-pointer hover:bg-blue-700"
                                        v-if="slotProps.data.status == 'Active'"> Active
                                    </Tag>
                                    <Tag severity="danger" @click="openNew(slotProps.data)" outlined rounded
                                        class="mr-2 hover:cursor-pointer" v-else> Inactive
                                    </Tag>
                                </template>
                            </Column>
                            <Column field="created_at_formatted" header="Joined Date" sortable></Column>
                            <Column field="updated_at_formatted" header="Modified Date" sortable></Column>
                        </DataTable>

                        <Dialog v-model:visible="userDialog" :style="{ width: '450px' }" header="User Status" :modal="true"
                            class="p-fluid">

                            <div class="flex flex-column gap-3 mt-3">
                                <div v-for="category in categories" :key="category.key" class="flex align-items-center">
                                    <RadioButton v-model="user.status" name="status" :value="category.name" />
                                    <label :for="category.key" class="ml-2">{{ category.name }}</label>
                                </div>
                            </div>

                            <template #footer>
                                <div class="mt-4">
                                    <Button label="Cancel" icon="pi pi-times" class="p-button-secondary"
                                        @click="hideDialog" />
                                    <Button label="Save" icon="pi pi-check" class="p-button-info" @click="saveUser" />
                                </div>
                            </template>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>