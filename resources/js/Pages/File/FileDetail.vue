
<script >
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AsideBar from "@/Components/asidebar.vue";

export default {
  props: ["data"],
  components: {
    AsideBar,
  },
};
</script>
<template>
  <AsideBar />
  <div class="p-4 sm:ml-64">
    <div
      class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700"
    >
      <div class="grid grid-cols-3 gap-4 mb-4">
        <div
          v-for="item in data"
          :key="item.id"
          class="flex items-center justify-center bg-gray-50 dark:bg-gray-800"
        >
          <a
            href="/doc/file"
            class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
          >
            <img
              class="object-cover w-full rounded-t-lg h-20 md:h-auto md:w-28 md:rounded-none md:rounded-l-lg"
              src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
              alt=""
            />
            <div class="flex flex-col justify-between p-4 leading-normal">
              <h5
                class="mb-2 text-base font-bold tracking-tight text-gray-900 dark:text-white"
              >
                {{ item.filename }}
              </h5>
              <p class="mb-3 text-xs text-gray-700 dark:text-gray-400">
                Here are the biggest enterprise technology acquisitions of 2021
                so far, in reverse chronological order.
                {{ item.desc }}
              </p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Pagination -->
</template>

