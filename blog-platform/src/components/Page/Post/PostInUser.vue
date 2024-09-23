<script setup>
import { onMounted ,watchEffect ,ref } from "vue";
import { useAuthStore } from "../../../stores/auth";
import { usePostStore } from "../../../stores/post";
import { useRoute } from "vue-router";
const route = useRoute();

const authStore = useAuthStore();
const postStore = usePostStore();
// const user = authStore.user
const userId = route.query.userId;

const isLoading = ref(false)


onMounted(async () => {
//   await authStore.getUser();
isLoading.value =true

  await postStore.getAllPostInUser(userId)
  isLoading.value =false

});

watchEffect(async () => {
  await postStore.getAllPostInUser(userId);
});
function formatDateTime(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString() + " " + date.toLocaleTimeString();
}
function truncatedContent(content) {
    return content.length > 80 ? content.substring(0, 100) + '...' : content;
    }
</script>

<template>
  <template v-if="authStore.user">
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
  <div class="loader"></div>
</div>
    <div class="bg-white py-12 sm:py-16">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
          <h2
            class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
          >
            From the blog
          </h2>
          <p class="mt-2 text-lg leading-8 text-gray-600">
            Learn how to grow your business with our expert advice.
          </p>
        </div>
        <div
          class="overflow-y-scroll max-h-[34rem] p-4 rounded-lg mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3 md:grid-cols-2"
        >
          <article
            v-for="post in postStore.postsInUser"
            :key="post.id"
            class="flex  max-w-xl flex-col items-start justify-between rounded-lg px-2 sm:text-center sm:items-center bg-slate-200 m-auto w-full h-full"
          >
            <div class="flex items-center gap-x-4 text-xs">
              <time :datetime="post.created_at" class="text-gray-500">
                {{ formatDateTime(post.created_at) }}
              </time>
              <router-link
                :to="{
                  name: 'postInCategory',
                  params: { categoryName: post.category.name },
                  query: { categoryId: post.category.id },
                }"
                class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100"
              >
                {{ post.category.name }}
              </router-link>
            </div>
            <router-link :to="{name:'postPage',
                 params: { postTitle: post.title },
                 query: { postId: post.id },
             }">
            <div class="group relative">
              <h3
                class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600"
              >

                  <span class="absolute inset-0" />
                  {{ post.title }}
                </h3>
                <!-- <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">
                    {{ truncatedContent(post.content )}}
                </p> -->
                <div v-html="truncatedContent(post.content)"></div>

            </div>
        </router-link>
            <div class="relative mt-8 flex items-center gap-x-4">
              <div class="text-sm leading-6">
                <p class="font-semibold text-gray-900">
                    <span class="absolute inset-0" />
                    {{ post.user.name }}
                </p>
                <p class="text-gray-600">{{ post.user.roles[0].name }}</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </template>

  <!-- </div> -->
  <template v-else>
    <router-link :to="{ name: 'login' }"> go to login </router-link>
  </template>
</template>
