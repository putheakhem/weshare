<template>
  <div class="modal" v-if="show">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <div class="modal-header">
        <h2>Login</h2>
        <button class="modal-close" @click="close">&times;</button>
      </div>
      <div class="modal-body">
        <Register/>
      </div>
    </div>
  </div>
</template>

<script setup>
import Register from "@/components/Register.vue";
  
const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
});

const close = () => {
  emit("close");
};

</script>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: 9999;
}
.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.6);
}
.modal-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
  max-width: 500px;
  width: 100%;
}
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #888;
}
.modal-body {
  margin-bottom: 20px;
}
</style>
