<template>
  <AuthenticatedLayout>
    <section class="bg-white dark:bg-gray-900">
      <div
        class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-5 lg:grid-cols-12"
      >
        <div class="mr-auto place-self-center lg:col-span-7">
          <form @submit.prevent="form.post('/upload')">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="w-56">
                  <label
                    for="department"
                    class="block text-900 font-medium mb-2"
                    >Department</label
                  >
                  <Dropdown
                    v-model="form.major_id"
                    :options="majors"
                    optionLabel="name"
                    placeholder="Select a major"
                    class="w-full"
                  />
                </div>
                <div class="w-56">
                  <label
                    for="Type of file"
                    value="filetype"
                    class="block text-900 font-medium mb-2"
                    >Type of file</label
                  >
                  <Dropdown
                    v-model="form.filetype_id"
                    :options="filetype"
                    optionLabel="name"
                    placeholder="Select a Type of file"
                    class="w-full"
                  />
                </div>
              </div>

              <div class="mb-4">
                <label for="department" class="block text-900 font-medium mb-2"
                  >Title</label
                >
                <InputText
                  type="text"
                  class="w-full block bg-slate-100"
                  placeholder="Title"
                  v-model="form.title"
                />

                <div v-if="$page.props.errors.author" class="text-red-500">
                  {{ $page.props.errors.author }}
                </div>
              </div>
              <div class="mb-4">
                <label for="department" class="block text-900 font-medium mb-2"
                  >Description</label
                >
                <textarea
                  v-model="form.desc"
                  id="message"
                  rows="4"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Write your thoughts here..."
                ></textarea>

                <div v-if="$page.props.errors.title" class="text-red-500">
                  {{ $page.props.errors.title }}
                </div>
              </div>

              <div class="mb-4">
                <label
                  for="formFile"
                  class="block text-gray-700 text-sm font-bold mb-2"
                  >File:</label
                >
                <file-pond
                  name="image"
                  ref="pond"
                  label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
                  v-bind:allow-multiple="true"
                  accepted-file-types="application/pdf"
                  v-bind:files="myFiles"
                  v-bind:server="myServer"
                  v-on:initfile="handleFilePondInitFile"
                />
              </div>
            </div>
            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <PrimaryButton
                  class="ml-4 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Store File
                </PrimaryButton>
              </span>

              <span
                class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto"
              >
              </span>
            </div>
          </form>
        </div>
        <div class="hidden lg:mb-auto lg:col-span-5 lg:flex">
          <img
            src="https://img.freepik.com/premium-vector/student-woman-with-laptop-studying-online-course-online-education-concept-vector-illustration-flat_186332-1147.jpg?w=2000"
            alt="mockup"
          />
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import { useForm, usePage } from "@inertiajs/vue3";
import vueFilePond from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js";
import FilePondPluginImagePreview from "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js";

// Import styles
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import { computed } from "vue";

// Create FilePond component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);
const props = defineProps({
  majors: {
    type: Array,
    required: true,
  },
  filetype: {
    type: Object,
    required: true,
  },
});
const page = usePage();
const pond = ref(null);
const files = ref([]);
const csrf = computed(() => page.props.csrf_token);

const form = useForm({
  major_id: "",
  filetype_id: "",
  desc: "",
  title: "",
});

const myServer = {
  process: (fieldName, file, metadata, load, error, progress, abort) => {
    const formData = new FormData();
    formData.append(fieldName, file);

    axios
      .post("/uploadfile", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
          "X-CSRF-TOKEN": page.props.csrf_token,
        },
        onUploadProgress: (event) => {
          progress(event.lengthComputable, event.loaded, event.total);
        },
      })
      .then((response) => {
        load(response.data.folder);
      })
      .catch((err) => {
        error(err);
      });
  },
  revert: "/revert",
};
const handleFilePondInitFile = (item) => {
  console.log(item);
  item.setMetadata("hello", "world");
};
</script>