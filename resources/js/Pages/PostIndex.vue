<script setup>
import { ref } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import { Head, useForm, usePage } from "@inertiajs/vue3";
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
  title:""
});

const myServer = {
  process: (fieldName, file, metadata, load, error, progress, abort) => {
    const formData = new FormData();
    formData.append(fieldName, file);

    axios
      .post("/upload", formData, {
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

<template>
  <GuestLayout>
    <Head title="Store the Post" />

    <form @submit.prevent="form.post('/dashboard/upload')">
      <div>
        <label for="department" class="block text-900 font-medium mb-2"
          >Department</label
        >
        <Dropdown
          v-model="form.major_id"
          :options="majors"
          optionLabel="name"
          placeholder="Select a major"
          class="w-full md:w-14rem"
        />
        <InputError class="mt-2" :message="form.errors.major_id" />
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
          class="w-full md:w-14rem"
        />

        <InputError class="mt-2" :message="form.errors.filetype_id" />
      </div>
      <div>
        <InputLabel for="title" value="Title" />

        <TextInput
          id="title"
          type="title"
          class="mt-1 block w-full p-2 bg-slate-100 border"
          v-model="form.title"
          placeholder="Title"
        />

        <InputError class="mt-2" :message="form.errors.title" />
      </div>
      <div class="mt-4">
        <InputLabel for="description" value="Description" />

        <textarea
          id="message"
          rows="4"
          v-model="form.desc"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Write your thoughts here..."
        ></textarea>

        <InputError class="mt-2" :message="form.errors.desc" />
      </div>
      <div class="mt-4">
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

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton
          class="ml-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Store Post
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
