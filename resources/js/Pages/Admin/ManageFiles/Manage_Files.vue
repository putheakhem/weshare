<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import Textarea from 'primevue/textarea';
import { ref, onMounted } from 'vue';
const toast = useToast();
const submitted = ref(false);
const fileDialog = ref(false);
const dt = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = () => {
    dt.value.exportCSV();
};
const props = defineProps({
    files: {
        type: Array,
        required: true,
    },
    file: {
        type: Object,
        default: () => ({}),
    },
    majors: {
        type: Array,
        required: true,
    },
    types: {
        type: Array,
        required: true,
    },
});

let file = ref({});
let fileupload = ref({});

onMounted(() => {
    file.value = file;
});

const openNew = (prod) => {
    file.value = { ...prod };
    fileDialog.value = true;
};

const hideDialog = () => {
    fileDialog.value = false;
};

const onChange = (e) => {
    fileupload.value = e.target.files[0];
    console.log(fileupload.value)
};

const saveFile = () => {
    submitted.value = true;
    if (file.value.title.trim()) {
        const form = useForm({
            id: file.value.id,
            file_de_id: file.value.file_de_id,
            filename: file.value.filename,
            path: file.value.path,
            fileupload: fileupload.value,
            title: file.value.title,
            description: file.value.description,
            major_id: file.value.major_id,
            type_id: file.value.type_id,
            user_id: file.value.user_id
        });
        if (file.value.id) {
            form.post(route("manage_files.update", file.value.id));
            console.log("Hello Form", form)
            toast.add({ severity: 'success', summary: 'Successful', detail: 'File Updated Successfully', life: 3000 });
        }
        else {
            form.post(route("manage_files.upload"))
            toast.add({ severity: 'success', summary: 'Successful', detail: 'File Created Successfully', life: 3000 });
        }
    }
    fileDialog.value = false;
};
const editFile = (prod) => {
    file.value = { ...prod };
    fileDialog.value = true;
};
const deleteFileDialog = ref(false);
const confirmDeleteFile = (prod) => {
    file.value = prod;
    deleteFileDialog.value = true;
};
const deleteFile = () => {
    const form = useForm({
        id: file.value.id,
        file_de_id: file.value.file_de_id,
    });
    form.delete(route("manage_files.delete", file.value.id));
    deleteFileDialog.value = false;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Deleted Successfully', life: 3000 });
}; 
</script>

<template>
    <Head title="Manage Files" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Files</h2>
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
                                    <Button label="Add New File" icon="pi pi-plus" severity="success" @click="openNew" />
                                </div>
                                <div>
                                    <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                                </div>
                            </template>
                        </Toolbar>
                        <DataTable ref="dt" :value="files" resizableColumns columnResizeMode="expand" showGridlines
                            dataKey="id" :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} files">
                            <Column field="filename" header="Name" sortable></Column>
                            <Column field="path" header="Path" sortable>
                                <template #body="slotProps">
                                    <a class="text-blue-700 hover:underline" :href="`/storage/${slotProps.data.path}`"
                                        target="_blank">{{ slotProps.data.path }}</a>
                                </template>
                            </Column>
                            <Column field="title" header="Title" sortable></Column>
                            <Column field="description" header="Description" sortable></Column>
                            <Column field="user_name" header="Uploaded by" sortable></Column>
                            <Column field="major_name" header="Major" sortable></Column>
                            <Column field="file_type" header="Type" sortable></Column>
                            <Column field="created_at_formatted" header="Uploaded Date" sortable></Column>
                            <Column field="updated_at_formatted" header="Modified Date" sortable></Column>
                            <Column header="Active" :exportable="false" style="min-width:8rem">
                                <template #body="slotProps">
                                    <div class=" inline-flex mr-2">
                                        <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                                            @click="editFile(slotProps.data)" />
                                    </div>
                                    <Button icon="pi pi-trash" outlined rounded severity="danger"
                                        @click="confirmDeleteFile(slotProps.data)" />
                                </template>
                            </Column>
                        </DataTable>

                        <Dialog enctype="multipart/form-data" v-model:visible="fileDialog" :style="{ width: '600px' }"
                            header="File Detail" :modal="true" class="p-fluid">
                            <div class="field" v-if="file.id">
                                <label for="name" class="font-bold text-gray-700">Name</label>
                                <div class="mt-1 text-gray-900">{{ file.filename }}</div>
                            </div>
                            
                            <div class="field mt-2">
                                <label for="name" v-if="file.id" class="font-bold text-gray-700">Path</label>
                                <div class="mt-1 mb-2">
                                    <a class="text-blue-700 hover:underline" :href="`${file.path}`" target="_blank">{{
                                        file.path }}</a>
                                </div>
                                <label for="name" v-if="file.id" class="font-bold text-gray-700">If you want to update
                                    uploaded file</label>
                                <div v-else>
                                    <label for="name" class="font-bold text-gray-700">Please choose a file to upload</label>
                                </div>
                                <input
                                    class="bg-white w-full p-4 border-gray-200 mt-2 rounded-md border-2 shadow-md hover:bg-blue-700 hover:text-white hover:cursor-pointer"
                                    type="file" v-on:change="onChange" required="true" autofocus
                                    :class="{ 'p-invalid': submitted && !fileupload.value }" />
                                <div v-if="!file.id">
                                    <small class="p-error" v-if="submitted && !fileupload.value">File is required.</small>
                                </div>
                            </div>

                            <div class="field mt-2">
                                <label for="title" class="font-bold text-gray-700">Title</label>
                                <div class="mt-2">
                                    <InputText id="title" v-model.trim="file.title" required="true" autofocus
                                        :class="{ 'p-invalid': submitted && !file.title }" />
                                    <small class="p-error" v-if="submitted && !file.title">Title is required.</small>
                                </div>
                            </div>

                            <div class="field mt-2">
                                <label for="description" class="font-bold text-gray-700">Description</label>
                                <div class="mt-2">
                                    <Textarea id="description" v-model="file.description" />
                                </div>
                            </div>

                            <div class="card flex flex-col justify-center mt-2">
                                <label for="major" class="font-bold text-gray-700 mb-2">Major</label>
                                <div class="w-full md:w-14rem">
                                    <Dropdown v-model="file.major_id" editable :options="majors" optionLabel="name"
                                        optionValue="id" placeholder="Select a Major" required="true" autofocus
                                        :class="{ 'p-invalid': submitted && !file.major_id }" />
                                    <small class="p-error" v-if="submitted && !file.major_id">Major is required.</small>
                                </div>
                            </div>

                            <div class="card flex flex-col justify-center mt-2">
                                <label for="type" class="font-bold text-gray-700 mb-2">Type</label>
                                <div class="w-full md:w-14rem">
                                    <Dropdown v-model="file.type_id" editable :options="types" optionLabel="name"
                                        optionValue="id" placeholder="Select a Type" required="true" autofocus
                                        :class="{ 'p-invalid': submitted && !file.type_id }" />
                                    <small class="p-error" v-if="submitted && !file.type_id">Type is required.</small>
                                </div>
                            </div>

                            <div class="field mt-2" v-if="file.id">
                                <label for="user_name" class="font-bold text-gray-700">Uploaded by</label>
                                <div class="mt-2 text-gray-900">
                                    <Tag outlined class="mr-2">{{ file.user_name }}</Tag>
                                </div>
                            </div>

                            <div class="field mt-2 hidden sr-only" v-if="file.id">
                                <InputText id="name" v-model="file.file_de_id" required="true" autofocus></InputText>
                            </div>

                            <template #footer>
                                <Button label="Cancel" icon="pi pi-times" class="p-button-secondary" @click="hideDialog" />
                                <Button label="Save" icon="pi pi-check" class="p-button-info" @click="saveFile" />
                            </template>
                        </Dialog>

                        <Dialog v-model:visible="deleteFileDialog" :style="{ width: '450px' }" header="Confirm"
                            :modal="true">
                            <div class="confirmation-content">
                                <span v-if="file">Are you sure you want to delete <b>{{ file.filename }}</b>?</span>
                            </div>
                            <template #footer>
                                <Button label="No" icon="pi pi-times" text @click="deleteFileDialog = false" />
                                <Button label="Yes" icon="pi pi-check" text @click="deleteFile" />
                            </template>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>