<script >
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AsideBar from "@/Components/asidebar.vue";

export default {
  props: ["data"],
  components: {
    AuthenticatedLayout,
  },
};
</script>
<template>
  
  <AuthenticatedLayout> 
    <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Files</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto p-10">
              
                    </div>
                </div>
            </div>
        </div>
  </AuthenticatedLayout>
</template>
