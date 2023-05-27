<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AboutUs from "./AboutUs.vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Carousel from "primevue/carousel";
import { defineProps, ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const toast = useToast();
const searchQuery = ref("");

function submitSearch(event) {
  event.preventDefault();
  window.location.href =
    "/search?query=" + encodeURIComponent(searchQuery.value);
}

function getDepartmentLink(name) {
  const departmentUrl = `/department/${name}`;
  window.location.href = departmentUrl;
}

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
  major: {
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
  axios
    .post(route("items.favorite", { itemId: file.id }))
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
  <Head title="Home" />
  <AuthenticatedLayout>
    <div class="bg-white">
      <Toast position="top-center" />
      <!-- Hero -->
      <section
        class="relative"
        style="
          background-image: url('Images/hompic1.png');
          background-repeat: no-repeat;
          background-position: center;
          background-size: cover;
        "
      >
        <div
          class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8"
        >
          <section class="lg:pt-12 dark:bg-gray-900">
            <div
              class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"
            >
              <h1
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"
              >
                Find solutions to your
                <strong class="font-extrabold text-rose-700">
                  toughest homework .
                </strong>
              </h1>
              <p
                class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400"
              >
                Join a community of students sharing knowledge and expertise.
              </p>

              <div
                class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36"
              >
                <div
                  class="flex flex-wrap justify-center items-center text-gray-500 sm:justify-between"
                >
                  <a
                    v-for="major in majors"
                    :key="major.id"
                    @click="getDepartmentLink(major.id)"
                    class="cursor-pointer w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                  >
                    <svg
                      class="w-7 h-7"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.5"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"
                      ></path>
                    </svg>
                    <div class="pl-2 text-left">
                      <div class="font-sans text-sm font-semibold">
                        {{ major.name }}
                      </div>
                    </div>
                  </a>
                  <!-- ---------- -->
                </div>

                <form
                  class="flex items-center justify-center lg:pt-10"
                  @submit="submitSearch"
                >
                  <label for="simple-search" class="sr-only">Search</label>
                  <div class="relative w-5/6">
                    <div
                      class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                      <svg
                        aria-hidden="true"
                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </div>
                    <input
                      type="text"
                      id="simple-search"
                      v-model="searchQuery"
                      class="h-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Search"
                      required
                    />
                  </div>
                
                </form>
              </div>
            </div>
          </section>
        </div>
      </section>

      <!-- Display File Carousel -->
      <h1 class="text-4xl font-bold my-12 flex text-blue-700 pl-10">
        Files Highlight
      </h1>
      <div class="card">
        <Carousel
          :value="files"
          :numVisible="4"
          :numScroll="1"
          :responsiveOptions="responsiveOptions"
          circular
          :autoplayInterval="3000"
        >
          <template #item="slotProps">
            <div
              class="border-2 border-blue-300 bg-white rounded-lg mx-2 my-4 p-4 flex flex-col"
            >
              <a :href="route('file_detail', slotProps.data.id)">
                <div class="mb-3 flex justify-center">
                  <img
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                    class="w-24 h-auto"
                  />
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
                <div
                  v-if="$page.props.auth.user"
                  class="text-xl hover:cursor-pointer"
                  @click="toggleFavorite(slotProps.data)"
                >
                  <i
                    :class="
                      slotProps.data.is_favorite
                        ? 'fas fa-heart text-red-500'
                        : 'far fa-heart text-gray-500'
                    "
                  ></i>
                </div>
                <div
                  v-else
                  class="text-xl hover:cursor-pointer"
                  @click="showWarn"
                >
                  <i class="far fa-heart text-gray-500"></i>
                </div>
                <h4 class="mt-2 text-sm text-gray-700">
                  {{ slotProps.data.file_type }}
                </h4>
              </div>
            </div>
          </template>
        </Carousel>
      </div>
      <!-- SEE MORE Button -->
      <div class="flex justify-center">
        <a :href="route('all_files')" :active="route().current('all_files')">
          <PrimaryButton>See More</PrimaryButton>
        </a>
      </div>

      <!-- About Us -->
      <AboutUs id="aboutus"></AboutUs>
    </div>
  </AuthenticatedLayout>
</template>

