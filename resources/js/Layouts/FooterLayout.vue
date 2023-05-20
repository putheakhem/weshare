<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>
<template>
  <div class="bg-white border-b border-gray-100 bottom-0 w-full">
    <div class="container mx-auto py-4 md:py-8 px-4 md:px-8">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-3 flex items-center">
          <ApplicationLogo></ApplicationLogo>
        </div>
        <div class="col-span-12 md:col-span-9 flex items-center">
          <div class="border-l-4 border-blue-500 pl-4 md:pl-8">
            <p class="text-gray-700">
              We will always respect discipline, ethics, trust, and responsibility for the company's services, including the privacy of our users.
            </p>
            <a href="#" class="flex items-center text-slateblue mt-2">
              <i class="pi pi-angle-right mr-2"></i>
              Policy and Privacy
            </a>
          </div>
        </div>
      </div>
      <div class="flex justify-center items-center mt-10">
        <p class="text-gray-500 text-sm">@2023 Weshare - All Rights Reserved</p>
      </div>
    </div>
  </div>
</template>