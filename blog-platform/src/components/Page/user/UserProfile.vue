<script setup>
import { onMounted, ref ,computed } from "vue";
import { useAuthStore } from "../../../stores/auth";
import { usePostStore } from "../../../stores/post";
import { useRoute } from "vue-router";
const route = useRoute();
const authStore = useAuthStore();
const postStore = usePostStore();
const isLoading = ref(false)

// Fetch the user profile when the component is mounted
onMounted(async () => {
//   await authStore.getProfile(authStore.user.id);
isLoading.value =true
  await postStore.getAllPostInUser(authStore.user.id);
  isLoading.value =false

});

// Compute the initials based on the user's name
const initials = computed(() => {

  return authStore.user?.name
    ? authStore.user.name.charAt(0).toUpperCase()
    : "";
});
function formatDateTime(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString() + " " + date.toLocaleTimeString();
}
function truncatedContent(content) {
  return content.length > 80 ? content.substring(0, 100) + "..." : content;
}
</script>

<template>
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
  <div class="loader"></div>
</div>
  <div
    v-else
    class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg mt-10"
  >
    <!-- Profile Header -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div
          class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center"
        >
          <!-- Placeholder for User's Avatar -->
          <span class="text-3xl font-bold text-gray-700">{{ initials }}</span>
        </div>
        <div>
          <h1 class="text-2xl font-semibold text-gray-900">
            {{ authStore.user.name }}
          </h1>
          <p class="text-gray-600">{{ authStore.user.email }}</p>
          <div class="mt-2">
            <span
              v-for="role in authStore.user.roles"
              :key="role.id"
              class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full"
            >
              {{ role.name }}
            </span>
          </div>
        </div>
      </div>
      <!-- Edit Profile Button -->
      <div>
        <router-link :to="{ name: 'editProfile' }">
          <button
            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            Edit Profile
          </button>
        </router-link>
      </div>
    </div>

    <!-- Social Links -->
    <div class="mt-6 py-4">
      <h2 class="text-lg font-semibold text-gray-900">
        Connect with {{ authStore.user.name }}
      </h2>
      <div class="flex space-x-4 mt-2">
        <a href="#" class="text-gray-500 hover:text-blue-600">
          <svg
            class="w-8 h-8"
            viewBox="0 0 14 14"
            role="img"
            focusable="false"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="#000000"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                fill="#485a96"
                d="M12.3377569 13.000045c.36572 0 .662243-.2965683.662243-.66228789V1.6622429c0-.3658549-.296568-.6622879-.662243-.6622879H1.66228797c-.36585448 0-.66228788.296478-.66228788.6622879v10.67546925c0 .36576455.2964334.66228785.66228788.66228785H12.3377569z"
              ></path>
              <path
                fill="#ffffff"
                d="M9.2798126 13.000045V8.3529692h1.5598161l.2335322-1.8110629H9.2797676V5.3856227c0-.5243449.1455865-.8816567.8975283-.8816567l.958991-.0004496V2.8837212c-.165864-.022031-.735171-.071354-1.3974139-.071354-1.3827115 0-2.329338.8439787-2.329338 2.3939481v1.3355914H5.8457173v1.8110629H7.409535v4.6470308h1.8702776z"
              ></path>
            </g>
          </svg>
        </a>
        <a href="#" class="text-gray-500 hover:text-blue-400">
          <svg
            class="w-8 h-8"
            viewBox="0 0 16 16"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                fill="#1D9BF0"
                d="M13.567 5.144c.008.123.008.247.008.371 0 3.796-2.889 8.173-8.172 8.173v-.002A8.131 8.131 0 011 12.398a5.768 5.768 0 004.25-1.19 2.876 2.876 0 01-2.683-1.995c.431.083.875.066 1.297-.05A2.873 2.873 0 011.56 6.348v-.036c.4.222.847.345 1.304.36a2.876 2.876 0 01-.89-3.836 8.152 8.152 0 005.92 3 2.874 2.874 0 014.895-2.619 5.763 5.763 0 001.824-.697 2.883 2.883 0 01-1.262 1.588A5.712 5.712 0 0015 3.656a5.834 5.834 0 01-1.433 1.488z"
              ></path>
            </g>
          </svg>
        </a>
        <a href="#" class="text-gray-500 hover:text-pink-600">
          <svg
            class="w-8 h-8"
            viewBox="0 0 32 32"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <rect
                x="2"
                y="2"
                width="28"
                height="28"
                rx="6"
                fill="url(#paint0_radial_87_7153)"
              ></rect>
              <rect
                x="2"
                y="2"
                width="28"
                height="28"
                rx="6"
                fill="url(#paint1_radial_87_7153)"
              ></rect>
              <rect
                x="2"
                y="2"
                width="28"
                height="28"
                rx="6"
                fill="url(#paint2_radial_87_7153)"
              ></rect>
              <path
                d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z"
                fill="white"
              ></path>
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z"
                fill="white"
              ></path>
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z"
                fill="white"
              ></path>
              <defs>
                <radialGradient
                  id="paint0_radial_87_7153"
                  cx="0"
                  cy="0"
                  r="1"
                  gradientUnits="userSpaceOnUse"
                  gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"
                >
                  <stop stop-color="#B13589"></stop>
                  <stop offset="0.79309" stop-color="#C62F94"></stop>
                  <stop offset="1" stop-color="#8A3AC8"></stop>
                </radialGradient>
                <radialGradient
                  id="paint1_radial_87_7153"
                  cx="0"
                  cy="0"
                  r="1"
                  gradientUnits="userSpaceOnUse"
                  gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"
                >
                  <stop stop-color="#E0E8B7"></stop>
                  <stop offset="0.444662" stop-color="#FB8A2E"></stop>
                  <stop offset="0.71474" stop-color="#E2425C"></stop>
                  <stop offset="1" stop-color="#E2425C" stop-opacity="0"></stop>
                </radialGradient>
                <radialGradient
                  id="paint2_radial_87_7153"
                  cx="0"
                  cy="0"
                  r="1"
                  gradientUnits="userSpaceOnUse"
                  gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"
                >
                  <stop offset="0.156701" stop-color="#406ADC"></stop>
                  <stop offset="0.467799" stop-color="#6A45BE"></stop>
                  <stop offset="1" stop-color="#6A45BE" stop-opacity="0"></stop>
                </radialGradient>
              </defs>
            </g>
          </svg>
        </a>
        <a href="#" class="text-gray-500 hover:text-blue-700">
          <svg
            class="w-8 h-8"
            fill="#000000"
            viewBox="0 0 32 32"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <title>github</title>
              <path
                d="M16 1.375c-8.282 0-14.996 6.714-14.996 14.996 0 6.585 4.245 12.18 10.148 14.195l0.106 0.031c0.75 0.141 1.025-0.322 1.025-0.721 0-0.356-0.012-1.3-0.019-2.549-4.171 0.905-5.051-2.012-5.051-2.012-0.288-0.925-0.878-1.685-1.653-2.184l-0.016-0.009c-1.358-0.93 0.105-0.911 0.105-0.911 0.987 0.139 1.814 0.718 2.289 1.53l0.008 0.015c0.554 0.995 1.6 1.657 2.801 1.657 0.576 0 1.116-0.152 1.582-0.419l-0.016 0.008c0.072-0.791 0.421-1.489 0.949-2.005l0.001-0.001c-3.33-0.375-6.831-1.665-6.831-7.41-0-0.027-0.001-0.058-0.001-0.089 0-1.521 0.587-2.905 1.547-3.938l-0.003 0.004c-0.203-0.542-0.321-1.168-0.321-1.821 0-0.777 0.166-1.516 0.465-2.182l-0.014 0.034s1.256-0.402 4.124 1.537c1.124-0.321 2.415-0.506 3.749-0.506s2.625 0.185 3.849 0.53l-0.1-0.024c2.849-1.939 4.105-1.537 4.105-1.537 0.285 0.642 0.451 1.39 0.451 2.177 0 0.642-0.11 1.258-0.313 1.83l0.012-0.038c0.953 1.032 1.538 2.416 1.538 3.937 0 0.031-0 0.061-0.001 0.091l0-0.005c0 5.761-3.505 7.029-6.842 7.398 0.632 0.647 1.022 1.532 1.022 2.509 0 0.093-0.004 0.186-0.011 0.278l0.001-0.012c0 2.007-0.019 3.619-0.019 4.106 0 0.394 0.262 0.862 1.031 0.712 6.028-2.029 10.292-7.629 10.292-14.226 0-8.272-6.706-14.977-14.977-14.977-0.006 0-0.013 0-0.019 0h0.001z"
              ></path>
            </g>
          </svg>
        </a>
      </div>
    </div>

  <div class="bg-stone-50 overflow-y-scroll  max-h-[58vh]">
    <div class="mx-auto  w-full  px-6 lg:px-8">
      <div class="mx-auto  lg:mx-0  p-4">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl text-center items-center">
          My Posts
        </h2>
      </div>
      <div
        class="px-4 rounded-lg mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 lg:mx-0 lg:max-w-none lg:grid-cols-3 md:grid-cols-2"
      >
        <article
          v-for="post in postStore.postsInUser"
          :key="post.id"
          class="flex max-w-xl h-full flex-col items-start justify-between rounded-lg px-2 sm:text-center sm:items-center bg-slate-200 m-auto w-full"
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
          <router-link
            :to="{
              name: 'postPage',
              params: { postTitle: post.title },
              query: { postId: post.id },
            }"
          >
            <div class="group relative">
              <h3
                class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600"
              >
                <span class="absolute inset-0" />
                {{ post.title }}
              </h3>
              <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">
                {{ truncatedContent(post.content) }}
                <!-- truncatedContent -->
              </p>
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
  </div></div>
  <!-- Loading State or Error Handling -->

</template>
