<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted } from 'vue';
const toast = useToast();
const submitted = ref(false);
const typeDialog = ref(false);
const dt = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = () => {
    dt.value.exportCSV();
};
const props = defineProps({
    types: {
        type: Array,
        required: true,
    },
    type: {
        type: Object,
        default: () => ({}),
    },
});

let type = ref({});
onMounted(() => {
    type.value = type;
});

const openNew = (prod) => {
    type.value = { ...prod };
    typeDialog.value = true;
};

const hideDialog = () => {
    typeDialog.value = false;
};

const saveType = () => {
    submitted.value = true;
    if (type.value.name.trim()) {
        const form = useForm({
            id: type.value.id,
            name: type.value.name,
        });
        if (type.value.id) {
            form.patch(route("manage_types.update", type.value.id));
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Updated Successfully', life: 3000 });
        }
        else {
            form.post(route("manage_types.create"))
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Created Successfully', life: 3000 });
        }
    }
    typeDialog.value = false;
};
const editType = (prod) => {
    type.value = { ...prod };
    typeDialog.value = true;
};
const deleteTypeDialog = ref(false);
const confirmDeleteType = (prod) => {
    type.value = prod;
    deleteTypeDialog.value = true;
};
const deleteType = () => {
    const form = useForm({
        id: type.value.id,
        name: type.value.name,
    });
    form.delete(route("manage_types.delete", type.value.id));
    deleteTypeDialog.value = false;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Deleted Successfully', life: 3000 });
};
</script>

<template>
    <Head title="Manage Types" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Types</h2>
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
                                <div class="mr-4">
                                    <Button label="Add New Type" icon="pi pi-plus" severity="success" @click="openNew" />
                                </div>
                                <div>
                                    <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                                </div>
                            </template>
                        </Toolbar>
                        <DataTable ref="dt" :value="types" resizableColumns columnResizeMode="expand" showGridlines
                            dataKey="id" :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} types">
                            <Column field="name" header="Name" sortable></Column>
                            <Column field="created_at_formatted" header="Created Date" sortable></Column>
                            <Column field="updated_at_formatted" header="Modified Date" sortable></Column>
                            <Column header="Active" :exportable="false" style="min-width:8rem">
                                <template #body="slotProps">
                                    <div class=" inline-flex mr-2">
                                        <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                                        @click="editType(slotProps.data)" />
                                    </div>
                                    <Button icon="pi pi-trash" outlined rounded severity="danger"
                                        @click="confirmDeleteType(slotProps.data)" />
                                </template>
                            </Column>
                        </DataTable>

                        <Dialog v-model:visible="typeDialog" :style="{ width: '450px' }" header="Type Detail"
                            :modal="true" class="p-fluid">
                            <div class="field">
                                <label for="name">Name</label>
                                <InputText id="name" v-model.trim="type.name" required="true" autofocus
                                    :class="{ 'p-invalid': submitted && !type.name }" />
                                <small class="p-error" v-if="submitted && !type.name">Name is required.</small>
                            </div>
                            <template #footer>
                                <Button label="Cancel" icon="pi pi-times" class="p-button-secondary" @click="hideDialog" />
                                <Button label="Save" icon="pi pi-check" class="p-button-info" @click="saveType" />
                            </template>
                        </Dialog>

                        <Dialog v-model:visible="deleteTypeDialog" :style="{ width: '450px' }" header="Confirm"
                            :modal="true">
                            <div class="confirmation-content">
                                <span v-if="type">Are you sure you want to delete <b>{{ type.name }}</b>?</span>
                            </div>
                            <template #footer>
                                <Button label="No" icon="pi pi-times" text @click="deleteTypeDialog = false" />
                                <Button label="Yes" icon="pi pi-check" text @click="deleteType" />
                            </template>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>