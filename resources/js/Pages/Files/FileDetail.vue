<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import Carousel from "primevue/carousel";
import { useToast } from "primevue/usetoast";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
const showWarn = () => {
    toast.add({
        severity: "warn",
        summary: "You are require to Login !",
        detail: "You have to login before uplaod, view or download file.",
        life: 5000,
    });
};

const toast = useToast();

const props = defineProps({
    files: {
        type: Array,
        required: true,
    },
    file: {
        type: Object,
        default: () => ({}),
    },
});

const responsiveOptions = ref([
    {
        breakpoint: "1199px",
        numVisible: 3,
        numScroll: 3,
    },
    {
        breakpoint: "991px",
        numVisible: 2,
        numScroll: 2,
    },
    {
        breakpoint: "767px",
        numVisible: 1,
        numScroll: 1,
    },
]);

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
    <Head title="File Detail" />
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <Toast position="top-center" />

                    <!-- Display File Detail -->
                    <div class="pl-10 flex items-center">
                        <a href="\" class="text-3xl font-bold mt-8 text-blue-700 pr-3 relative">
                            <i class="fas fa-chevron-left mr-3"></i>
                        </a>
                        <h1 class="text-3xl font-bold mt-8 text-blue-700 relative">
                            File Detail
                        </h1>
                    </div>

                    <div class="flex justify-center">
                        <div class="card mt-5">
                            <div class="grid grid-cols-2 gap-4">
                                <a v-if="$page.props.auth.user" :href="`/storage/files/${file.path}`" target="_blank">
                                    <div
                                        class="border-2 border-blue-300 bg-white rounded-lg mx-2 my-4 p-4 flex flex-col items-center">
                                        <div class="mb-3">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                                                class="w-24 h-auto" />
                                        </div>
                                        <p class="text-base font-bold text-blue-700">
                                            {{ file.filename }}
                                        </p>
                                    </div>
                                </a>
                                <a v-else @click="showWarn" class="hover:cursor-pointer">
                                    <div
                                        class="border-2 border-blue-300 bg-white rounded-lg mx-2 my-4 p-4 flex flex-col items-center">
                                        <div class="mb-3">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                                                class="w-24 h-auto" />
                                        </div>
                                        <p class="text-base font-bold text-blue-700">
                                            {{ file.filename }}
                                        </p>
                                    </div>
                                </a>
                                <div>
                                    <div class="mt-3">
                                        <label for="title" class="text-base text-blue-700 font-bold mr-2">Title :</label>
                                        <span id="title" class="text-base text-black-700">{{ file.title }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <label for="description" class="text-base text-blue-700 font-bold mr-2">Description
                                            :</label>
                                        <span id="description" class="text-base text-black-700">{{ file.description
                                        }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <label for="major" class="text-base text-blue-700 font-bold mr-2">Major :</label>
                                        <span id="major" class="text-base text-black-700">{{ file.major_name }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <label for="type" class="text-base text-blue-700 font-bold mr-2">Type :</label>
                                        <span id="type" class="text-base text-black-700">{{ file.file_type }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <label for="user" class="text-base text-blue-700 font-bold mr-2">Uploaded by
                                            :</label>
                                        <span id="user" class="text-base text-black-700">{{ file.user_name }}</span>
                                    </div>
                                    <div v-if="$page.props.auth.user" class="mt-2" @click="toggleFavorite(file)">
                                        <SecondaryButton v-if="!file.is_favorite">
                                            <span class="mr-2">
                                                <i class="far fa-heart text-white"></i>
                                            </span>
                                            <span>Add to Favorite</span>
                                        </SecondaryButton>
                                        <SecondaryButton v-else class="bg-red-500">
                                            <span class="mr-2">
                                                <i class="pi pi-trash text-white"></i>
                                            </span>
                                            <span>Remove from Favorite</span>
                                        </SecondaryButton>
                                    </div>
                                    <div v-else class="mt-2" @click="showWarn">
                                        <SecondaryButton>
                                            <span class="mr-2">
                                                <i class="far fa-heart text-white"></i>
                                            </span>
                                            <span>Add to Favorite</span>
                                        </SecondaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display File Carousel -->
                    <h1 class="text-3xl font-bold my-11 flex text-blue-700 pl-10">
                        Explore More Files
                    </h1>
                    <div class="card">
                        <Carousel :value="files" :numVisible="4" :numScroll="1" :responsiveOptions="responsiveOptions"
                            circular :autoplayInterval="3000">
                            <template #item="slotProps">
                                <div class="border-2 border-blue-300 bg-white rounded-lg mx-2 my-4 p-4 flex flex-col">
                                    <a :href="route('file_detail', slotProps.data.id)
                                        ">
                                        <div class="mb-3 flex justify-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                                                class="w-24 h-auto" />
                                        </div>
                                        <div class="mb-2 flex justify-center">
                                            <h4 class="text-xl font-bold text-blue-700">
                                                {{ slotProps.data.title }}
                                            </h4>
                                        </div>
                                        <div class="mb-2 flex justify-center">
                                            <h4 class="text-base text-blue-700">
                                                {{ slotProps.data.major_name }}
                                            </h4>
                                        </div>
                                    </a>
                                    <div class="flex justify-between">
                                        <div v-if="$page.props.auth.user" class="text-xl hover:cursor-pointer"
                                            @click="toggleFavorite(slotProps.data)">
                                            <i
                                                :class="slotProps.data.is_favorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-500'"></i>
                                        </div>
                                        <div v-else class="text-xl hover:cursor-pointer" @click="showWarn">
                                            <i class="far fa-heart text-gray-500"></i>
                                        </div>
                                        <h4 class="mt-2 text-sm text-gray-700">{{ slotProps.data.file_type }}</h4>
                                    </div>
                                </div>
                            </template>
                        </Carousel>
                    </div>

                    <!-- SEE MORE Button -->
                    <div class="flex justify-center mb-5">
                        <a :href="route('all_files')" :active="route().current('all_files')">
                            <PrimaryButton>See More</PrimaryButton>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>