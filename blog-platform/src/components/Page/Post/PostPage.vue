<script setup>
import { onMounted, watchEffect, ref } from "vue";
import { useAuthStore } from "../../../stores/auth";
import { usePostStore } from "../../../stores/post";
import { useRoute } from "vue-router";
const route = useRoute();
const authStore = useAuthStore();
const postStore = usePostStore();
const postId = route.query.postId;
const openDeleteModel = ref(false);
function showAndHiddDeleteModel() {
  openDeleteModel.value = !openDeleteModel.value;
}

// const user = authStore.user
onMounted(async () => {
  await postStore.getPost(postId);
});

watchEffect(async () => {
  await postStore.getPost(postId);
});
function formatDateTime(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString() + " " + date.toLocaleTimeString();
}
</script>
<template>
  <div
    v-if="postStore.post"
    class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow-md overflow-y-scroll max-h-[90vh]"
  >
    <div class="mb-4">
      <h1 class="text-4xl font-bold text-gray-900">
        {{ postStore.post.title }}
      </h1>
      <div class="mt-2 text-sm text-gray-500">
        <span>Written by </span>
        <router-link
          v-if="postStore.post.user"
          :to="{
            name: 'postInUser',
            params: { userName: postStore.post.user.name },
            query: { userId: postStore.post.user.id },
          }"
        >
          <span>{{ postStore.post.user.name }}</span>
        </router-link>
        |
        <time
          v-if="postStore.post.created_at"
          :datetime="postStore.post.created_at"
          >{{ formatDateTime(postStore.post.created_at) }}</time
        >
      </div>
    </div>

    <div class="mt-6 text-lg text-gray-800">
      <!-- <p>{{ postStore.post.content }}</p> -->
      <div v-html="postStore.post.content"></div>
    </div>

    <div v-if="postStore.post.category" class="mt-8">
      <router-link
        :to="{
          name: 'postInCategory',
          params: { categoryName: postStore.post.category.name },
          query: { categoryId: postStore.post.category.id },
        }"
      >
        <h2 class="text-xl font-semibold text-gray-900">
          Category: {{ postStore.post.category.name }}
        </h2>
      </router-link>
      <p class="text-gray-600">Tags:</p>
      <div class="flex flex-wrap mt-2">
        <span
          v-for="tag in postStore.post.tags"
          :key="tag.id"
          class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full"
        >
          {{ tag.name }}
        </span>
      </div>
    </div>
    <div v-if="postStore.user_id === authStore.user.id">
      <div v-if="openDeleteModel">
        <div
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40"
        >
          <section
            class="w-full max-w-lg m-auto bg-white rounded-2xl shadow-lg drop-shadow p-4"
          >
            <div class="py-3">
              <div class="border-b-4 border-gray-600 my-2">
                <h1 class="text-center text-red-700 text-3xl font-bold">
                  Delete Post
                </h1>
              </div>
              <div class="p-2 rounded-lg">
                <form action="">
                  <div class="p-4 text-center">
                    <p class="text-gray-700 text-lg">
                      Are you sure you want to delete this Post? This action
                      cannot be undone.
                    </p>
                  </div>
                  <div class="flex items-center justify-center mt-4">
                    <button
                      @click="postStore.deletePost(postId)"
                      type="button"
                      class="bg-red-500 mx-2 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:shadow-outline"
                    >
                      Delete
                    </button>
                    <button
                      @click="showAndHiddDeleteModel"
                      type="button"
                      class="bg-orange-500 mx-2 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-600 focus:outline-none focus:shadow-outline"
                    >
                      Exit
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>

      <router-link
        v-if="postStore.post.user_id === authStore.user.id"
        :to="{
          name: 'EditPost',
          params: { userId: postStore.post.title },
          query: { postId: postStore.post.id },
        }"
      >
        <button
          class="fixed bottom-4 right-4 flex items-center justify-center w-12 h-12 bg-yellow-500 text-white rounded-full shadow-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-200 ease-in-out"
          style="position: fixed; bottom: 5rem; right: 1rem"
        >
          <svg
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            fill="#000000"
            class="w-8 h-8"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <title></title>
              <g id="Complete">
                <g id="edit">
                  <g>
                    <path
                      d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                      fill="none"
                      stroke="#000000"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                    ></path>
                    <polygon
                      fill="none"
                      points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                      stroke="#000000"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                    ></polygon>
                  </g>
                </g>
              </g>
            </g>
          </svg>
        </button>
      </router-link>
      <button
        @click="showAndHiddDeleteModel"
        class="fixed bottom-12 right-4 flex items-center justify-center w-12 h-12 bg-red-500 text-white rounded-full shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition duration-200 ease-in-out"
        style="position: fixed; bottom: 1rem; right: 1rem"
      >
        <svg
          class="w-8 h-8"
          viewBox="0 -0.5 21 21"
          version="1.1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          fill="#000000"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <title>delete [#1487]</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g
              id="Page-1"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
            >
              <g
                id="Dribbble-Light-Preview"
                transform="translate(-179.000000, -360.000000)"
                fill="#000000"
              >
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path
                    d="M130.35,216 L132.45,216 L132.45,208 L130.35,208 L130.35,216 Z M134.55,216 L136.65,216 L136.65,208 L134.55,208 L134.55,216 Z M128.25,218 L138.75,218 L138.75,206 L128.25,206 L128.25,218 Z M130.35,204 L136.65,204 L136.65,202 L130.35,202 L130.35,204 Z M138.75,204 L138.75,200 L128.25,200 L128.25,204 L123,204 L123,206 L126.15,206 L126.15,220 L140.85,220 L140.85,206 L144,206 L144,204 L138.75,204 Z"
                    id="delete-[#1487]"
                  ></path>
                </g>
              </g>
            </g>
          </g>
        </svg>
      </button>
    </div>
  </div>

  <div v-else class="text-center">Loading...</div>
</template>
