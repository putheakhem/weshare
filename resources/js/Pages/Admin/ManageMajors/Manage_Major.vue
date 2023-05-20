<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted } from 'vue';
const toast = useToast();
const submitted = ref(false);
const majorDialog = ref(false);
const dt = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = () => {
    dt.value.exportCSV();
};
const props = defineProps({
    majors: {
        type: Array,
        required: true,
    },
    major: {
        type: Object,
        default: () => ({}),
    },
});

let major = ref({});
onMounted(() => {
    major.value = major;
});

const openNew = (prod) => {
    major.value = { ...prod };
    majorDialog.value = true;
};

const hideDialog = () => {
    majorDialog.value = false;
};

const saveMajor = () => {
    submitted.value = true;
    if (major.value.name.trim()) {
        const form = useForm({
            id: major.value.id,
            name: major.value.name,
            activated: major.value.activated
        });
        if (major.value.id) {
            form.patch(route("manage_majors.update", major.value.id));
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Major Updated Successfully', life: 3000 });
        }
        else {
            form.post(route("manage_majors.create"))
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Major Created Successfully', life: 3000 });
        }
    }
    majorDialog.value = false;
};
const editMajor = (prod) => {
    major.value = { ...prod };
    majorDialog.value = true;
};
const deleteMajorDialog = ref(false);
const confirmDeleteMajor = (prod) => {
    major.value = prod;
    deleteMajorDialog.value = true;
};
const deleteMajor = () => {
    const form = useForm({
        id: major.value.id,
        name: major.value.name,
        activated: major.value.activated
    });
    form.delete(route("manage_majors.delete", major.value.id));
    deleteMajorDialog.value = false;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Major Deleted Successfully', life: 3000 });
};
</script>

<template>
    <Head title="Manage Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Majors</h2>
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
                                    <Button label="Add New Major" icon="pi pi-plus" severity="success" @click="openNew" />
                                </div>
                                <div>
                                    <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                                </div>
                            </template>
                        </Toolbar>
                        <DataTable ref="dt" :value="majors" resizableColumns columnResizeMode="expand" showGridlines
                            dataKey="id" :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} majors">
                            <Column field="name" header="Name" sortable></Column>
                            <Column field="created_at_formatted" header="Created Date" sortable></Column>
                            <Column field="updated_at_formatted" header="Modified Date" sortable></Column>
                            <Column header="Active" :exportable="false" style="min-width:8rem">
                                <template #body="slotProps">
                                    <div class=" inline-flex mr-2">
                                        <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                                        @click="editMajor(slotProps.data)" />
                                    </div>
                                    <Button icon="pi pi-trash" outlined rounded severity="danger"
                                        @click="confirmDeleteMajor(slotProps.data)" />
                                </template>
                            </Column>
                        </DataTable>

                        <Dialog v-model:visible="majorDialog" :style="{ width: '450px' }" header="Major Detail"
                            :modal="true" class="p-fluid">
                            <div class="field">
                                <label for="name">Name</label>
                                <InputText id="name" v-model.trim="major.name" required="true" autofocus
                                    :class="{ 'p-invalid': submitted && !major.name }" />
                                <small class="p-error" v-if="submitted && !major.name">Name is required.</small>
                            </div>
                            <template #footer>
                                <Button label="Cancel" icon="pi pi-times" class="p-button-secondary" @click="hideDialog" />
                                <Button label="Save" icon="pi pi-check" class="p-button-info" @click="saveMajor" />
                            </template>
                        </Dialog>

                        <Dialog v-model:visible="deleteMajorDialog" :style="{ width: '450px' }" header="Confirm"
                            :modal="true">
                            <div class="confirmation-content">
                                <span v-if="major">Are you sure you want to delete <b>{{ major.name }}</b>?</span>
                            </div>
                            <template #footer>
                                <Button label="No" icon="pi pi-times" text @click="deleteMajorDialog = false" />
                                <Button label="Yes" icon="pi pi-check" text @click="deleteMajor" />
                            </template>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>