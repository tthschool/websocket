<script setup>
import axios from "axios";
import Echo from "laravel-echo";
import { onMounted, ref } from "vue";

const props = defineProps({
    messages: {
    type: Array,
    required: true,
  },
});
const messagesData = ref(props.messages);
onMounted(() => {
  window.Echo.channel("sent-message").listen("SentMessage", (e) => {
    //code for displaying the serve data
    log(e.data);
    messagesData.value.push(e.data);
  });
});

const data = ref("");
const addNote = () => {
  axios
    .post("/chat", {  message: data.value })
    .then((response) => {
    })
    .catch((error) => {
      console.log(error);
    });
    data.value = "";
};
</script>
<template>
  <div class="flex flex-col h-screen justify-between p-6 bg-gray-100">
    <div class="flex-1 overflow-auto mb-4">
      <div v-for="message in messagesData" :key="message.id" class="mb-2">
        <div class="flex">
          <div 
            class="bg-blue-500 text-white rounded-lg p-3 max-w-xs w-fit"
            :class="message.isUser ? 'ml-auto bg-gray-300 text-black' : ''"
          >
            {{message.name}} : {{ message.message }}
          </div>
        </div>
      </div>
    </div>
    <div class="w-full">
      <form @submit.prevent="addNote" class="flex items-center">
        <input 
          v-model="data"
          type="text"
          class="flex-1 p-2 rounded border"
          placeholder="Type a message..."
        />
        <button
          type="submit"
          class="ml-2 bg-blue-500 text-white p-2 rounded-lg"
        >
          Send
        </button>
      </form>
    </div>
  </div>
</template>