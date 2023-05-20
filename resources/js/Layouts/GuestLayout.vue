<template>
  <div
    class="container min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 p-10"
  >
    <div class="w-full max-w-md p-4 bg-white rounded-lg shadow-md">
      <div class="flex justify-center">
        <Link href="/">
          <ApplicationLogo
            class="w-20 h-20 fill-current text-gray-500 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
          />
        </Link>
      </div>
      <div class="px-4 py-5 sm:p-6">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
.container {
  max-width: 500px; /* set your desired max-width here */
  margin: 0 auto; /* center the container horizontally */
  padding: 0 20px; /* add some horizontal padding */
  box-sizing: border-box; /* include padding in the total width of the container */
}
.min-h-screen {
  min-height: calc(100vh - 4rem);
}
.max-w-md {
  max-width: 28rem;
}
@media (min-width: 640px) {
  .max-w-md {
    max-width: 36rem;
  }
}
@media (min-width: 768px) {
  .max-w-md {
    max-width: 42rem;
  }
}
@media (min-width: 1024px) {
  .max-w-md {
    max-width: 48rem;
  }
}
@media (min-width: 1280px) {
  .max-w-md {
    max-width: 56rem;
  }
}
@media (min-width: 1536px) {
  .max-w-md {
    max-width: 64rem;
  }
}
.bg-white {
  background-color: #fff;
}
.shadow-md {
  box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.06);
}
.transition {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
.ease-in-out {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
.transform {
  transform: none;
}
.hover\:transform\:none:hover {
  transform: none;
}
.hover\:translate-y-1:hover {
  transform: translateY(-1rem);
}
.hover\:scale-110:hover {
  transform: scale(1.1);
}
</style>