<script setup>
import axios from "axios";
import DropdownLink from "@/Components/DropdownLink.vue";
import Echo from "laravel-echo";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
const props = defineProps({
  auth: Object,
});
const messagesData = ref([]);
onMounted(() => {
  form.value.user_id = usePage().user;
  window.Echo.channel("sent-message").listen("SentMessage", (e) => {
    if (e.message._value.message) {
      messagesData.value.push({ text: e.message._value.message });
    }
    if (e.message._value.file) {
      messagesData.value.push({ image: e.message._value.file });
      form.value.file = "";
    }
  });
});

const form = ref({
  message: "",
  file: null,
  name: props.auth.user.name,
});

const fileInput = ref(null);
const data = ref([]);
const addNote = (name) => {
  form.value.name = name;
  axios
    .post("/chat", { message: form })
    .then((response) => {})
    .catch((error) => {
      console.log(error);
    });
  form.value.message = "";
  fileInput.value.value = "";
  form.value.name = "";
};

const compressImage = (e) => {
  console.log(e.target.files[0]);

  const file = e.target.files[0];
  if (file) {
    const img = new Image();
    img.onload = () => {
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");
      const MAX_WIDTH = 600;
      const MAX_HEIGHT = 300;
      let width = img.width;
      let height = img.height;
      if (height > width) {
        if (height > MAX_HEIGHT) {
          width *= MAX_HEIGHT / height;
          height = MAX_HEIGHT;
        }
      } else {
        if (width > MAX_WIDTH) {
          height *= MAX_WIDTH / width;
          width = MAX_WIDTH;
        }
      }
      canvas.width = width;
      canvas.height = height;
      ctx.drawImage(img, 0, 0, width, height);
      const dataUrl = canvas.toDataURL("image/jpeg", 1);
      form.value.file = dataUrl;
    };
    img.src = URL.createObjectURL(file);
  }
};
</script>
<template>
  <div class="flex">
    <div class="flex-1 mt-0 text-right">
      <DropdownLink :href="route('logout')" method="post" as="button">
        Log Out
      </DropdownLink>
    </div>
  </div>
  <div class="flex flex-col h-screen justify-between p-10 bg-gray-100">
    <div class="flex-1 overflow-auto mb-4">
      <div v-for="(item, index) in messagesData" :key="index" class="mb-2">
        <div v-if="form.name === $page.props.auth.user.name">
          <div class="flex">
            <p class="mr-3 rounded-l bg-blue-500">{{ $page.props.auth.user.name }}</p>
            <p v-if="item.text">{{ item.text }}</p>
            <img v-if="item.image" :src="item.image" alt="Image attached" />
          </div>
        </div>
        <div v-else>
          <div class="flex">
            <p class="mr-3 rounded-l bg-blue-500">{{ form.name }}</p>
            <p v-if="item.text">{{ item.text }}</p>
            <img v-if="item.image" :src="item.image" alt="Image attached" />
          </div>
        </div>
      </div>
    </div>
    {{ $page.props.auth.user.name }}
    <div class="w-full">
      <form
        @submit.prevent="addNote($page.props.auth.user.name)"
        class="flex items-center"
      >
        <input
          v-model="form.message"
          type="text"
          class="flex-1 p-2 rounded border"
          placeholder="Type a message..."
        />

        <input
          @change="compressImage"
          ref="fileInput"
          type="file"
          class="p-2 w-60 rounded border"
        />
        <button type="submit" class="ml-2 bg-blue-500 text-white p-2 rounded-lg">
          Send
        </button>
      </form>
    </div>
  </div>
</template>
