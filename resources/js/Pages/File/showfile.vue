<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
export default {
  props: ["data"],
  components: {
    AuthenticatedLayout,
  },
  data() {
    return {
      itemsData: [],
    };
  },
  methods: {
    addToFavorite(itemId) {
      // Make a POST request to the Laravel route for adding to favorites
      axios
        .post(`/favorite`, { id: itemId })
        .then((response) => {
          const index = this.itemsData.findIndex((item) => item.id === itemId);
          if (index !== -1) {
            this.itemsData[index].isFav = true;
          }
        })
        .catch((error) => {
          console.error(error);
          if (error.response) {
            // Handle error response
          }
        });
    },

  
  },
  mounted() {
    // Fetch items from Laravel backend
    axios
      .get("/dashboard/doc")
      .then((response) => {
        this.itemsData = response.data; // Assign the fetched data to 'itemsData'
      })
      .catch((error) => {
        console.error(error);
      });
  },
};
</script>



<template>
  <AuthenticatedLayout>
    <div class="p-8">
      <div
        class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
      >
        <div
          v-for="item in data"
          :key="item.id"
          class="bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 shadow-md"
        >
          <img
            class="object-cover w-full h-32 md:h-40 rounded-t-lg"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
            alt=""
          />
          <div class="p-4">
            <h5 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
              {{ item.filename }}
            </h5>
            <p class="mb-2 text-xs text-gray-700 dark:text-gray-400">
              {{ item.file_details.desc }}
            </p>
            <button
              @click="addToFavorite(item.id)"
              v-if="!item.isFav"
              class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 rounded-lg hover:text-blue-900 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
              <svg
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
                width="20"
                height="20"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                ></path>
              </svg>
              <span class="ml-1">Add to Favorites</span>
            </button>
            <button @click="removeFromFavorite(item)" v-else>
              Remove from Favorites
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

  <!-- Pagination -->
</template>


