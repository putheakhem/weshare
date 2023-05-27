<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { useToast } from "primevue/usetoast";
import { ref, onMounted, computed, watch } from "vue";
import axios from "axios";
import MultiSelect from "primevue/multiselect";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const toast = useToast();

const props = defineProps({
  favourites: {
    type: Array,
    default: () => ({}),
  },
  majors: {
    type: Array,
    default: () => ({}),
  },
  types: {
    type: Array,
    default: () => ({}),
  },
});

const toggleFavorite = (file) => {
  file.is_favorite = !file.is_favorite;
  axios
    .post(route("favorites.delete", { itemId: file.id }))
    .then((response) => {
      file.is_favorite = response.data.is_favorite;
    })
    .catch((error) => {
      console.error(error);
      file.is_favorite = !file.is_favorite;
    })
    .finally(() => {
      window.location.href = "/favourite_items"; // Replace '/redirect-route' with the actual URL you want to redirect to
    });
  toast.add({
    severity: "success",
    summary: "Successful",
    detail: "File Removed Successfully",
    life: 3000,
  });
};

const search = ref("");
const currentPage = ref(1);
const perPage = ref(20); // Number of items to display per page
const selectedMajors = ref("");
const selectedTypes = ref("");
const cancelSearch = () => {
  search.value = "";
};

onMounted(() => {
  fetchFiles();
});

const fetchFiles = () => {
  axios.get("/all_files").then((response) => {
    props.favourites = response.data;
  });
};

const searchFiles = () => {
  const searchQuery = search.value.toLowerCase().trim();
  filteredFiles.value = props.favourites.filter(
    (file) =>
      file.title.toLowerCase().includes(searchQuery) ||
      file.major_name.toLowerCase().includes(searchQuery) ||
      file.file_type.toLowerCase().includes(searchQuery) ||
      file.filename.toLowerCase().includes(searchQuery)
  );
};

const filteredFiles = ref([]);

const computedFilteredFiles = computed(() => {
  let result = props.favourites;

  if (search.value !== "") {
    const searchQuery = search.value.toLowerCase().trim();
    result = result.filter(
      (file) =>
        file.title.toLowerCase().includes(searchQuery) ||
        file.major_name.toLowerCase().includes(searchQuery) ||
        file.file_type.toLowerCase().includes(searchQuery) ||
        file.filename.toLowerCase().includes(searchQuery)
    );
  }

  if (selectedMajors.value.length > 0) {
    result = result.filter((file) =>
      selectedMajors.value.some((major) => major.name === file.major_name)
    );
  }
  if (selectedTypes.value.length > 0) {
    result = result.filter((file) =>
      selectedTypes.value.some((type) => type.name === file.file_type)
    );
  }
  return result;
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
</script>

<template>
  <Head title="My Favourite" />
  <AuthenticatedLayout>
    <div class="bg-white">
      <div class="max-w-screen-lg mx-auto sm:px-6 lg:px-8">
        <div class=" overflow-hidden shadow-sm sm:rounded-lg">
          <div class="pl-4 sm:pl-8 md:pl-12 lg:pl-36 mb-8">
            <Toast />

            <!-- Search Files -->
            <div
              class="flex flex-wrap gap-2 items-center justify-center pl-3 mb-4 mt-8"
            >
              <span class="p-input-icon-left w-1/2">
                <i class="pi pi-search" />
                <InputText
                  v-model="search"
                  @input="searchFiles"
                  placeholder="Search"
                  class="w-full md:w-50rem"
                />
                <i
                  v-if="search"
                  @click="cancelSearch"
                  class="pi pi-times absolute pt-3 top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:cursor-pointer"
                ></i>
              </span>
              <!-- Filter Major-->
              <MultiSelect
                v-model="selectedMajors"
                :options="majors"
                optionLabel="name"
                placeholder="Select Majors"
                display="chip"
                class="md:w-20rem"
              >
              </MultiSelect>
               <!-- Filter file type-->
              <MultiSelect
                v-model="selectedTypes"
                :options="types"
                optionLabel="name"
                placeholder="Select Doc Types"
                display="chip"
                class="md:w-20rem"
              >
              </MultiSelect>
            </div>

            <!-- Display Favourite Files -->
            <div class="flex justify-between pl-3 mt-4">
              <p class="text-gray-500">
                Displaying {{ (currentPage - 1) * perPage + 1 }} to
                {{
                  Math.min(currentPage * perPage, computedFilteredFiles.length)
                }}
                out of {{ computedFilteredFiles.length }} files
              </p>
            </div>
            <div v-if="paginatedFiles.length > 0">
              <div v-for="file in paginatedFiles" :key="file.id">
                <div class="grid grid-cols-2 gap-4">
                  <a :href="`/storage/${file.path}`" target="_blank">
                    <div
                      class="border-2 border-blue-300 bg-white rounded-lg mx-2 my-4 p-4 flex flex-col items-center"
                    >
                      <div class="mb-3">
                        <img
                          src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                          class="w-24 h-auto"
                        />
                      </div>
                      <p class="text-base font-bold text-blue-700">
                        {{ file.filename }}
                      </p>
                    </div>
                  </a>
                  <div>
                    <div class="mt-3">
                      <label
                        for="title"
                        class="text-base text-blue-700 font-bold mr-2"
                        >Title :</label
                      >
                      <span id="title" class="text-base text-black-700">{{
                        file.title
                      }}</span>
                    </div>
                    <div class="mt-2">
                      <label
                        for="description"
                        class="text-base text-blue-700 font-bold mr-2"
                        >Description :</label
                      >
                      <span id="description" class="text-base text-black-700">{{
                        file.description
                      }}</span>
                    </div>
                    <div class="mt-2">
                      <label
                        for="major"
                        class="text-base text-blue-700 font-bold mr-2"
                        >Major :</label
                      >
                      <span id="major" class="text-base text-black-700">{{
                        file.major_name
                      }}</span>
                    </div>
                    <div class="mt-2">
                      <label
                        for="type"
                        class="text-base text-blue-700 font-bold mr-2"
                        >Type :</label
                      >
                      <span id="type" class="text-base text-black-700">{{
                        file.file_type
                      }}</span>
                    </div>
                    <div class="mt-2">
                      <label
                        for="user"
                        class="text-base text-blue-700 font-bold mr-2"
                        >Uploaded by :</label
                      >
                      <span id="user" class="text-base text-black-700">{{
                        file.user_name
                      }}</span>
                    </div>
                    <div class="mt-2">
                      <SecondaryButton
                        class="bg-red-500"
                        @click="toggleFavorite(file)"
                      >
                        <span class="mr-2">
                          <i class="pi pi-trash text-white"></i>
                        </span>
                        <span>Remove from Favorite</span>
                      </SecondaryButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div class="flex justify-center text-center">
                <p class="text-gray-500 text-xl">
                  No files found for : "{{ search }}"
                </p>
              </div>
            </div>

            <!-- Display Paginator -->
            <div
              class="flex justify-center mt-4"
              v-if="paginatedFiles.length > 0"
            >
              <nav>
                <ul class="pagination flex items-center">
                  <li
                    class="page-item"
                    :class="{ disabled: currentPage === 1 }"
                  >
                    <a
                      class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100"
                      href="#"
                      @click="goToPage(currentPage - 1)"
                    >
                      <i class="fas fa-chevron-left mr-2"></i> Previous
                    </a>
                  </li>
                  <template v-for="page in totalPages" :key="page">
                    <li class="page-item">
                      <a
                        class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100"
                        :class="{ active: page === currentPage }"
                        href="#"
                        @click="goToPage(page)"
                      >
                        {{ page }}
                      </a>
                    </li>
                  </template>
                  <li
                    class="page-item"
                    :class="{ disabled: currentPage === totalPages }"
                  >
                    <a
                      class="page-link px-4 py-2 border border-gray-300 hover:bg-gray-100"
                      href="#"
                      @click="goToPage(currentPage + 1)"
                    >
                      Next <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
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