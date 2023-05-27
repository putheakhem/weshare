<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, watch } from 'vue';
const toast = useToast();
const submitted = ref(false);
const feedbackDialog = ref(false);
const dt = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = () => {
    dt.value.exportCSV();
};
const props = defineProps({
    feedbacks: {
        type: Array,
        required: true,
    },
    feedback: {
        type: Object,
        default: () => ({}),
    },
    userCount: {
        type: Number,
        required: true
    },
    fileCount: {
        type: Number,
        required: true
    },
    feedbackCount: {
        type: Number,
        required: true
    },
    majorCounts: {
        type: Array,
        required: true
    }
});

let feedback = ref({});
onMounted(() => {
    feedback.value = feedback;
});

const hideDialog = () => {
    feedbackDialog.value = false;
};

const saveFeedback = () => {
    submitted.value = true;
    if (feedback.value.description) {
        const form = useForm({
            id: feedback.value.id,
            user_id: feedback.value.user_id,
            description: feedback.value.description,
            status: feedback.value.status
        });
        form.patch(route("feedbacks.update", feedback.value.id))
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Created Successfully', life: 3000 });
    }
    feedbackDialog.value = false;
};
const editFeedback = (prod) => {
    feedback.value = { ...prod };
    feedbackDialog.value = true;
};
const deleteFeedbackDialog = ref(false);
const confirmDeleteFeedback = (prod) => {
    feedback.value = prod;
    deleteFeedbackDialog.value = true;
};
const deleteFeedback = () => {
    const form = useForm({
        id: feedback.value.id,
        user_id: feedback.value.user_id,
        description: feedback.value.description,
    });
    form.delete(route("feedbacks.delete", feedback.value.id));
    deleteFeedbackDialog.value = false;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Type Deleted Successfully', life: 3000 });
};

const categories = ref([
    { name: 'Active', key: 'A' },
    { name: 'Inactive', key: 'I' },
]);
</script>

<template>
    <Head title="Home" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <!-- three large card -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4 p-10">
                        <div
                            class="bg-white text-blue-500 border-2 border-blue-500 shadow-md rounded-md p-6 animate-fade-in">
                            <div class="grid grid-cols-2">
                                <div>
                                    <h2 class="text-xl font-bold mb-4">Total Users</h2>
                                    <p class="text-5xl font-semibold mb-4">{{ userCount }}</p>
                                </div>
                                <div class="mt-3">
                                    <img src="https://static-00.iconduck.com/assets.00/user-group-illustration-2048x1508-3m8qw8mc.png"
                                        alt="User Image" class="w-full h-auto rounded-md">
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white text-blue-500 border-2 border-blue-500 shadow-md rounded-md p-6 animate-fade-in">
                            <div class="grid grid-cols-2">
                                <div>
                                    <h2 class="text-xl font-bold mb-4">Total FileUploads</h2>
                                    <p class="text-5xl font-semibold mb-4">{{ fileCount }}</p>
                                </div>
                                <div>
                                    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/file-upload-5562805-4642599.png"
                                        alt="File Upload Image" class="w-full h-auto rounded-md">
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white text-blue-500 border-2 border-blue-500 shadow-md rounded-md p-6 animate-fade-in">
                            <div class="grid grid-cols-2">
                                <div>
                                    <h2 class="text-xl font-bold mb-4">Total Feedbacks</h2>
                                    <p class="text-5xl font-semibold mb-4">{{ feedbackCount }}</p>
                                </div>
                                <div>
                                    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/feedback-message-5314531-4440386.png"
                                        alt="Feedback Image" class="w-full h-auto rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- small card -->
                    <div class="flex flex-wrap pl-10">
                        <div v-for="major in majorCounts" :key="major.name" class=" bg-blue-100 rounded-lg p-4 mr-4 mb-4">
                            <h3 class="text-lg font-bold text-blue-800">{{ major.name }}</h3>
                            <p class="text-sm text-green-600">Total Files: <span class=" text-lg font-bold text-blue-700">{{
                                major.total_items }}</span></p>
                        </div>
                    </div>

                    <!-- Feedback Table Section-->
                    <div class="overflow-x-auto p-10">
                        <Toast />
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
                                <div>
                                    <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                                </div>
                            </template>
                        </Toolbar>
                        <DataTable ref="dt" :value="feedbacks" resizableColumns columnResizeMode="expand" showGridlines
                            dataKey="id" :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} feedbacks">
                            <Column field="description" header="Feedback Message" sortable>
                                <template #body="slotProps">
                                    <p id="description" class="whitespace-pre-wrap line-clamp-3 text-justify">
                                        {{ slotProps.data.description }}
                                    </p>
                                </template>
                            </Column>
                            <Column field="status" header="Feedback Status" sortable>
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
                            <Column field="user_name" header="Feedback by" sortable></Column>
                            <Column field="user_email" header="Email" sortable></Column>
                            <Column field="created_at_formatted" header="Submitted Date" sortable></Column>
                            <Column header="Active" :exportable="false" style="min-width:8rem">
                                <template #body="slotProps">
                                    <div class=" inline-flex mr-2">
                                        <Button icon="pi pi-eye" outlined rounded class="mr-2"
                                            @click="editFeedback(slotProps.data)" />
                                    </div>
                                    <Button icon="pi pi-trash" outlined rounded severity="danger"
                                        @click="confirmDeleteFeedback(slotProps.data)" />
                                </template>
                            </Column>
                        </DataTable>

                        <Dialog v-model:visible="feedbackDialog" :style="{ width: '470px' }" header="Feedback Detail"
                            :modal="true" class="p-fluid">

                            <div class="field">
                                <label for="description" class="font-bold text-gray-700">Feedback Message</label>
                                <p class="bg-blue-100 mt-2 border border-blue-300 p-4 rounded-md shadow-md">{{
                                    feedback.description }}</p>
                            </div>

                            <div class="flex flex-column gap-3 mt-5">
                                <label for="feedback_status" class="font-bold text-gray-700 mt-3">Feedback Status </label>
                                <div v-for="category in categories" :key="category.key"
                                    class="flex align-items-center border border-blue-300 p-4 rounded-md">
                                    <RadioButton v-model="feedback.status" name="Feedback Status" :value="category.name" />
                                    <label :for="category.key" class="ml-2">{{ category.name }}</label>
                                </div>
                            </div>

                            <div class="field mt-2">
                                <label for="user_name" class="font-bold text-gray-700">Feedback by </label>
                                <p class="bg-gray-100 mt-2 border border-gray-300 p-4 rounded-md shadow-md">{{
                                    feedback.user_name }}</p>
                            </div>

                            <div class="field mt-2">
                                <label for="user_email" class="font-bold text-gray-700">Email </label>
                                <p class="bg-gray-100 mt-2 border border-gray-300 p-4 rounded-md shadow-md">{{
                                    feedback.user_email }}</p>
                            </div>


                            <template #footer>
                                <Button label="Cancel" icon="pi pi-times" class="p-button-secondary" @click="hideDialog" />
                                <Button label="Save" icon="pi pi-check" class="p-button-info" @click="saveFeedback" />
                            </template>
                        </Dialog>

                        <Dialog v-model:visible="deleteFeedbackDialog" :style="{ width: '450px' }" header="Confirm"
                            :modal="true">
                            <div class="confirmation-content">
                                <span>Are you sure you want to delete this feedback ?</span>
                            </div>
                            <template #footer>
                                <Button label="No" icon="pi pi-times" text @click="deleteFeedbackDialog = false" />
                                <Button label="Yes" icon="pi pi-check" text @click="deleteFeedback" />
                            </template>
                        </Dialog>
                    </div>
                    <!-- End Feedback Table Section -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
