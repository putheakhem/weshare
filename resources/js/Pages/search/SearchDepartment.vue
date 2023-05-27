<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";
import { defineProps } from 'vue';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

const toast = useToast();
const showWarn = () => {
    toast.add({
        severity: "warn",
        summary: "You are require to Login !",
        detail: "You have to login before upload, view or download file.",
        life: 5000,
    });
};

const props = defineProps({
    files: {
        type: Array,
        required:true
    },
});

const search = ref('');
const currentPage = ref(1);
const perPage = ref(20); // Number of items to display per page

const cancelSearch = () => {
    search.value = ''
}

onMounted(() => {
    fetchFiles();
});

const fetchFiles = () => {
    axios.get("/deparment/{id}").then((response) => {
        props.files = response.data;
    });
};

const searchFiles = () => {
    const searchQuery = search.value.toLowerCase().trim();
    filteredFiles.value = props.files.filter(
        (file) =>
            file.title.toLowerCase().includes(searchQuery) ||
            file.major_name.toLowerCase().includes(searchQuery) ||
            file.file_type.toLowerCase().includes(searchQuery)
    );
};

const filteredFiles = ref([]);

const computedFilteredFiles = computed(() => {
    if (search.value === "") {
        console.log(props.files)
        return props.files;
    } else {
        return filteredFiles.value;
    }
});

const paginatedFiles = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return computedFilteredFiles.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(computedFilteredFiles.value.length / perPage.value);
});

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};
const toggleFavorite = (file) => {
    file.is_favorite = !file.is_favorite;
    axios.post(route('items.favorite', { itemId: file.id }))
        .then((response) => {
            file.is_favorite = response.data.is_favorite;
        })
        .catch((error) => {
            console.error(error);
            file.is_favorite = !file.is_favorite;
        });
};

</script>

<template>
    <Head title="Display Files" />
    <AuthenticatedLayout>
       <div class="bg-white">
            <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
                    <Toast position="top-center" />
                    <div class="overflow-x-auto p-10">
                        <!-- Search Files -->
                        <div class="flex flex-wrap gap-2 items-center justify-center pl-3 mb-4">
                            <span class="relative">
                                <i
                                    class="pi pi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                                <input type="text" v-model="search" @input="searchFiles" placeholder="Search"
                                    class="bg-blue-50 pl-10 pr-4 py-2 w-full sm:w-64 md:w-96 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 focus:outline-none transition duration-150 ease-in-out">
                                <i v-if="search" @click="cancelSearch"
                                    class="pi pi-times absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:cursor-pointer"></i>
                            </span>
                        </div>

                        <!-- Display all files -->
                        <div class="flex justify-between pl-3 mt-4">
                            <p class="text-gray-500">
                                Displaying {{ (currentPage - 1) * perPage + 1 }} to {{ Math.min(currentPage * perPage,
                                    computedFilteredFiles.length) }} out of {{ computedFilteredFiles.length }} files
                            </p>
                        </div>
                        <div v-if="paginatedFiles.length > 0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <template v-for="(file , index) in paginatedFiles" :key="index">
                                    <div class="flex justify-center">
                                        <div
                                            class="border-2 border-blue-300 bg-white w-full rounded-lg mx-2 my-4 p-4 flex flex-col">
                                            <a :href="route('file_detail', file.id)">
                                                <div class="mb-3 flex justify-center">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                                                        class="w-24 h-auto" />
                                                </div>
                                                <div class="mb-2 flex justify-center">
                                                    <h2 class="text-xl font-bold text-blue-700">{{ file.title }}</h2>
                                                </div>
                                                <div class="mb-2 flex justify-center">
                                                    <h4 class="text-base text-blue-700">{{ file.major_name }}</h4>
                                                </div>
                                            </a>

                                            <div class="flex justify-between">
                                                <div v-if="$page.props.auth.user" class="text-xl hover:cursor-pointer"
                                                    @click="toggleFavorite(file)">
                                                    <i
                                                        :class="file.is_favorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-500'"></i>
                                                </div>
                                                <div v-else class="text-xl hover:cursor-pointer" @click="showWarn">
                                                    <i class="far fa-heart text-gray-500"></i>
                                                </div>
                                                <h4 class="mt-2 text-sm text-gray-700">{{ file.file_type }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>
                        <div v-else>
                            <div class="flex justify-center text-center">
                                <p class="text-gray-500 text-xl">No files found for : "{{ search }}"</p>
                            </div>
                        </div>

                        <!-- Display Paginator -->
                        <div class="flex justify-center mt-4" v-if="paginatedFiles.length > 0">
                            <nav>
                                <ul class="pagination flex items-center">
                                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                        <a class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100" href="#"
                                            @click="goToPage(currentPage - 1)">
                                            <i class="fas fa-chevron-left mr-2"></i> Previous
                                        </a>
                                    </li>
                                    <template v-for="page in totalPages" :key="page">
                                        <li class="page-item">
                                            <a class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100"
                                                :class="{ 'active': page === currentPage }" href="#"
                                                @click="goToPage(page)">
                                                {{ page }}
                                            </a>
                                        </li>
                                    </template>
                                    <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                        <a class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100" href="#"
                                            @click="goToPage(currentPage + 1)">
                                            Next <i class="fas fa-chevron-right ml-2"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
              
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.pagination .page-item a.active {
    background-color: blue;
    color: white;
}
</style>