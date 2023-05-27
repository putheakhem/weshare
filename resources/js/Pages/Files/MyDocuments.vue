<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { useToast } from "primevue/usetoast";
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import MultiSelect from "primevue/multiselect";
const toast = useToast();

const props = defineProps({
  files: {
    type: Array,
    default: () => ({}),
  },
  majors: {
    type: Array,
    required: true,
  },
  types: {
    type: Array,
    required: true,
  },
});

// Search and Paginator
const search = ref("");
const currentPage = ref(1);
const perPage = ref(20); // Number of items to display per page

const cancelSearch = () => {
  search.value = "";
};

onMounted(() => {
  fetchFiles();
});

const fetchFiles = () => {
  axios.get("/my_documents").then((response) => {
    props.files = response.data;
  });
};

const searchFiles = () => {
  const searchQuery = search.value.toLowerCase().trim();
  filteredFiles.value = props.files.filter(
    (file) =>
      file.title.toLowerCase().includes(searchQuery) ||
      file.major_name.toLowerCase().includes(searchQuery) ||
      file.file_type.toLowerCase().includes(searchQuery) ||
      file.filename.toLowerCase().includes(searchQuery)
  );
};

const filteredFiles = ref([]);

const computedFilteredFiles = computed(() => {
  if (search.value === "") {
    console.log(props.files);
    return props.files;
  } else {
    return filteredFiles.value;
  }
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

// Delete and Update File
const submitted = ref(false);
const fileDialog = ref(false);

let file = ref({});
let fileupload = ref({});

onMounted(() => {
  file.value = file;
});

const hideDialog = () => {
  fileDialog.value = false;
};

const onChange = (e) => {
  fileupload.value = e.target.files[0];
  console.log(fileupload.value);
};

const saveFile = () => {
  submitted.value = true;
  if (file.value.title.trim()) {
    const form = useForm({
      id: file.value.id,
      file_de_id: file.value.file_de_id,
      filename: file.value.filename,
      path: file.value.path,
      fileupload: fileupload.value,
      title: file.value.title,
      description: file.value.description,
      major_id: file.value.major_id,
      type_id: file.value.type_id,
      user_id: file.value.user_id,
    });
    if (file.value.id) {
      form.post(route("my_documents.update", file.value.id));
      console.log("Hello Form", form);
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "File Updated Successfully",
        life: 3000,
      });
    } else {
      form.post(route("my_documents.upload"));
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "File Created Successfully",
        life: 3000,
      });
    }
  }
  fileDialog.value = false;
};
const editFile = (prod) => {
  file.value = { ...prod };
  fileDialog.value = true;
};
const deleteFileDialog = ref(false);

const confirmDeleteFile = (prod) => {
  file.value = prod;
  deleteFileDialog.value = true;
};
const deleteFile = () => {
  const form = useForm({
    id: file.value.id,
    file_de_id: file.value.file_de_id,
  });
  form.delete(route("my_documents.delete", file.value.id));
  deleteFileDialog.value = false;
  toast.add({
    severity: "success",
    summary: "Successful",
    detail: "Type Deleted Successfully",
    life: 3000,
  });
};
</script>

<template>
  <Head title="My Favourite" />
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="pl-4 sm:pl-8 md:pl-12 lg:pl-36 mb-8">
            <Toast />

            <!-- Search Files -->
            <div
              class="flex flex-wrap gap-2 items-center justify-center pl-3 mb-4 mt-8"
            >
              
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText
                  v-model="search"
                  @input="searchFiles"
                  placeholder="Search"
                  class="md:w-50rem"
                />
                <i
                  v-if="search"
                  @click="cancelSearch"
                  class="pi pi-times absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:cursor-pointer"
                ></i>
              </span>

              <!-- Filter menu -->

              <MultiSelect
                v-model="selectedCountries"
                :options="countries"
                optionLabel="name"
                placeholder="Select Countries"
                display="chip"
                class="md:w-20rem"
              >
                <template #option="slotProps">
                  <div class="flex align-items-center">
                    <img
                      :alt="slotProps.option.name"
                      src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                      :class="`flag flag-${slotProps.option.code.toLowerCase()} mr-2`"
                      style="width: 18px"
                    />
                    <div>{{ slotProps.option.name }}</div>
                  </div>
                </template>
                
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
                      <div class="inline-flex mr-2">
                        <Button
                          icon="pi pi-pencil"
                          outlined
                          rounded
                          class="mr-2"
                          @click="editFile(file)"
                        />
                      </div>
                      <Button
                        icon="pi pi-trash"
                        outlined
                        rounded
                        severity="danger"
                        @click="confirmDeleteFile(file)"
                      />
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

            <Dialog
              enctype="multipart/form-data"
              v-model:visible="fileDialog"
              :style="{ width: '600px' }"
              header="File Detail"
              :modal="true"
              class="p-fluid"
            >
              <div class="field" v-if="file.id">
                <label for="name" class="font-bold text-gray-700">Name</label>
                <div class="mt-1 text-gray-900">{{ file.filename }}</div>
              </div>

              <div class="field mt-2">
                <label for="name" v-if="file.id" class="font-bold text-gray-700"
                  >Path</label
                >
                <div class="mt-1 mb-2">
                  <a
                    class="text-blue-700 hover:underline"
                    :href="`${file.path}`"
                    target="_blank"
                    >{{ file.path }}</a
                  >
                </div>
                <label for="name" v-if="file.id" class="font-bold text-gray-700"
                  >If you want to update uploaded file</label
                >
                <div v-else>
                  <label for="name" class="font-bold text-gray-700"
                    >Please choose a file to upload</label
                  >
                </div>
                <input
                  class="bg-white w-full p-4 border-gray-200 mt-2 rounded-md border-2 shadow-md hover:bg-blue-700 hover:text-white hover:cursor-pointer"
                  type="file"
                  v-on:change="onChange"
                  required="true"
                  autofocus
                  :class="{ 'p-invalid': submitted && !fileupload.value }"
                />
                <div v-if="!file.id">
                  <small class="p-error" v-if="submitted && !fileupload.value"
                    >File is required.</small
                  >
                </div>
              </div>

              <div class="field mt-2">
                <label for="title" class="font-bold text-gray-700">Title</label>
                <div class="mt-2">
                  <InputText
                    id="title"
                    v-model.trim="file.title"
                    required="true"
                    autofocus
                    :class="{ 'p-invalid': submitted && !file.title }"
                  />
                  <small class="p-error" v-if="submitted && !file.title"
                    >Title is required.</small
                  >
                </div>
              </div>

              <div class="field mt-2">
                <label for="description" class="font-bold text-gray-700"
                  >Description</label
                >
                <div class="mt-2">
                  <Textarea id="description" v-model="file.description" />
                </div>
              </div>

              <div class="card flex flex-col justify-center mt-2">
                <label for="major" class="font-bold text-gray-700 mb-2"
                  >Major</label
                >
                <div class="w-full md:w-14rem">
                  <Dropdown
                    v-model="file.major_id"
                    editable
                    :options="majors"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Select a Major"
                    required="true"
                    autofocus
                    :class="{ 'p-invalid': submitted && !file.major_id }"
                  />
                  <small class="p-error" v-if="submitted && !file.major_id"
                    >Major is required.</small
                  >
                </div>
              </div>

              <div class="card flex flex-col justify-center mt-2">
                <label for="type" class="font-bold text-gray-700 mb-2"
                  >Type</label
                >
                <div class="w-full md:w-14rem">
                  <Dropdown
                    v-model="file.type_id"
                    editable
                    :options="types"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Select a Type"
                    required="true"
                    autofocus
                    :class="{ 'p-invalid': submitted && !file.type_id }"
                  />
                  <small class="p-error" v-if="submitted && !file.type_id"
                    >Type is required.</small
                  >
                </div>
              </div>

              <div class="field mt-2" v-if="file.id">
                <label for="user_name" class="font-bold text-gray-700"
                  >Uploaded by</label
                >
                <div class="mt-2 text-gray-900">
                  <Tag outlined class="mr-2">{{ file.user_name }}</Tag>
                </div>
              </div>

              <div class="field mt-2 hidden sr-only" v-if="file.id">
                <InputText
                  id="name"
                  v-model="file.file_de_id"
                  required="true"
                  autofocus
                ></InputText>
              </div>

              <template #footer>
                <Button
                  label="Cancel"
                  icon="pi pi-times"
                  class="p-button-secondary"
                  @click="hideDialog"
                />
                <Button
                  label="Save"
                  icon="pi pi-check"
                  class="p-button-info"
                  @click="saveFile"
                />
              </template>
            </Dialog>

            <Dialog
              v-model:visible="deleteFileDialog"
              :style="{ width: '450px' }"
              header="Confirm"
              :modal="true"
            >
              <div class="confirmation-content">
                <span v-if="file"
                  >Are you sure you want to delete <b>{{ file.filename }}</b
                  >?</span
                >
              </div>
              <template #footer>
                <Button
                  label="No"
                  icon="pi pi-times"
                  text
                  @click="deleteFileDialog = false"
                />
                <Button
                  label="Yes"
                  icon="pi pi-check"
                  text
                  @click="deleteFile"
                />
              </template>
            </Dialog>
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