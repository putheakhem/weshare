<script setup>
</script>
<template>
  <div class="bg-white mx-auto py-8 px-4">
    <h1
      class="text-4xl font-bold my-12 flex text-blue-700 items-center justify-center"
    >
      About Us
    </h1>
    <div class="flex flex-col md:flex-row">
      <div class="grid grid-cols-1 md:grid-cols-12">
        <div class="col-span-12 md:col-span-6 flex items-center">
          <svg viewBox="0 0 1200 600" xmlns="http://www.w3.org/2000/svg">
            <image
              xlink:href="/assets/aboutus.jpg"
              x="0"
              y="0"
              width="100%"
              height="100%"
            />
          </svg>
        </div>
        <div class="col-span-12 md:col-span-6 flex items-center">
          <div>
            <p class="px-4 text-xl text-gray-700">
              Welcome to WeShare! We are a community-driven platform that allows
              users to upload and share files related to their studies. Whether
              you need study notes, assignments, or practice exercises, you'll
              find a wide range of resources uploaded by our users.
            </p>
          </div>
        </div>
      </div>
    </div>

    <h1
      class="text-4xl font-bold my-12 flex text-blue-700 items-center justify-center"
    >
      Our Vision
    </h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <div class="flex items-center justify-center flex-col mx-4">
        <div class="rounded-full w-50 h-50 overflow-hidden shadow-lg">
          <div class="border-2 border-blue-500 rounded-full shadow">
            <div class="flex items-center justify-center w-40 h-40 mx-auto">
              <img
                src="/assets/vision1.jpg"
                alt="WeShare Logo"
                class="w-28 h-28"
              />
            </div>
          </div>
        </div>
        <h2 class="mt-4 text-xl font-bold text-blue-700 text-center">
          Central platform for educational resources
        </h2>
        <p class="mt-2 text-lg text-gray-700 mx-auto text-center">
          Our platform serves as a central hub for students to easily access and
          share a wide range of educational resources, making it convenient for
          them to find the materials they need for their studies.
        </p>
      </div>

      <div class="flex items-center justify-center flex-col mx-4">
        <div class="rounded-full w-50 h-50 overflow-hidden shadow-lg">
          <div class="border-2 border-blue-500 rounded-full">
            <div class="flex items-center justify-center w-40 h-40 mx-auto">
              <img
                src="/assets/vision2.png"
                alt="WeShare Logo"
                class="w-28 h-28"
              />
            </div>
          </div>
        </div>
        <h2 class="mt-4 text-xl font-bold text-blue-700 text-center">
          Enabling lifelong learning
        </h2>
        <p class="mt-2 text-lg text-gray-700 mx-auto text-center">
          We support lifelong learning by providing a platform where individuals
          can continue to learn and acquire new knowledge and skills throughout
          their lives, empowering personal and professional growth.
        </p>
      </div>

      <div class="flex items-center justify-center flex-col">
        <div class="rounded-full w-50 h-50 overflow-hidden shadow-lg">
          <div class="border-2 border-blue-500 rounded-full shadow">
            <div class="flex items-center justify-center w-40 h-40 mx-auto">
              <img
                src="https://www.learningfolks.in/static/media/mainl.6be8abb78c5a28dc60b3.gif"
                alt="WeShare Logo"
                class="w-28 h-28"
              />
            </div>
          </div>
        </div>
        <h2 class="mt-4 text-xl font-bold text-blue-700 text-center">
          Facilitating peer-to-peer learning
        </h2>
        <p class="mt-2 text-lg text-gray-700 mx-auto text-center">
          Our platform encourages collaboration among students, allowing them to
          connect, share study materials, and learn from each other's insights,
          fostering a dynamic peer-to-peer learning environment.
        </p>
      </div>
    </div>
  </div>
</template>