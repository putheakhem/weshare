<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  props: ["data"],
  components: {
    AuthenticatedLayout,
  },
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-8">
      <form class="flex items-center py-6" @submit="submitSearch">
        <label for="simple-search" class="sr-only">Search</label>
        <div class="relative w-1/2">
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
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Search"
            required
          />
        </div>
        <button
          type="submit"
          class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            ></path>
          </svg>
          <span class="sr-only">Search</span>
        </button>
      </form>
      
        <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">
        Displaying 1 to 3 out of 3 schools
      </p>
  
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
            {{ item.id }}
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


