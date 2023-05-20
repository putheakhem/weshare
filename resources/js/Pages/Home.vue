<template>
  <AuthenticatedLayout>
    <section
      class="relative bg-[url('Images/hompic1.png')] bg-cover bg-center bg-no-repeat backdrop-contrast-50 bg-white/30"
    >
      <div
        class="absolute inset-0 bg-white/75 sm:bg-transparent sm:from-white/95 sm:to-white/25 ltr:sm:bg-gradient-to-r rtl:sm:bg-gradient-to-l"
      ></div>
      <div
        class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8"
      >
        <div class="max-w-xl text-center ltr:sm:text-left rtl:sm:text-right">
          <h1 class="text-3xl font-extrabold sm:text-5xl">
            Find solutions to your

            <strong class="block font-extrabold text-rose-700">
              toughest homework .
            </strong>
          </h1>

          <p class="mt-4 max-w-lg sm:text-md/relaxed">
            Join a community of students sharing knowledge and expertise.
          </p>
          <div class="mt-8 flex flex-wrap gap-4 text-center">
            <div>
              <TabView class="rounded-lg">
                <TabPanel
                  v-for="tab in tabs"
                  :key="tab.title"
                  :header="tab.title"
                  class="rounded-lg"
                >
                  <p>{{ tab.content }}</p>
                </TabPanel>
              </TabView>
            </div>
          </div>
        </div>
      </div>
    </section>
    <AboutUs id="aboutus" class="mb-6"></AboutUs>
  </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AboutUs from "./AboutUs.vue";
import { ref } from "vue";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";

import FileUpload from "primevue/fileupload";

const selectedMajor = ref(null);
const selectedFileType = ref(null);
export default {
  components: {
    TabPanel,
    TabView,
    FileUpload,
    AuthenticatedLayout,
    AboutUs,
  },
  setup() {
    const tabs = ref([
      {
        title: "Compuster Science",
        content: "Find Study resource, class note and more.",
      },
      {
        title: "Telecome and Networking",
        content: "Find Study resource, class note and more.",
      },
      {
        title: "E-Commerce",
        content: "Find Study resource, class note and more..",
      },
    ]);

    return {
      tabs,
    };
  },
  methods: {
    jumpToTop() {
      window.scrollTo({ top: 0, behavior: "smooth" });
    },
    jumpToaboutus() {
      const visionSection = document.getElementById("aboutus");
      if (visionSection) {
        window.scrollTo({
          top: visionSection.offsetTop,
          behavior: "smooth",
        });
      }
    },
  },
};
</script>
<style>
.footer-background {
  background: linear-gradient(
    89.94deg,
    #0172f4 0.06%,
    rgba(0, 119, 255, 0.125) 99.4%
  );
}
.rounded-lg {
  border-radius: 10px;
}
.jump-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 10px 15px;
  background-color: #000;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.jump-to-top:hover {
  background-color: #333;
}
</style>
