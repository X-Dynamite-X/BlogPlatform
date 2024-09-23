<script setup>
import { onMounted, watchEffect, ref } from "vue";
import { useAuthStore } from "../../../stores/auth";
import { usePostStore } from "../../../stores/post";
import { useRoute } from "vue-router";

const route = useRoute();
const authStore = useAuthStore();
const postStore = usePostStore();
const postId = route.query.postId;

const openDeleteModelPost = ref(false);
const isLoading = ref(false);
const newComment = ref("");
const dropdownOpen = ref(null);
const editingCommentId = ref(null);
const editedCommentContent = ref('');
const showDeleteCommentConfirmation = ref(false);
const confirmDeleteCommentId = ref(null);

function showAndHiddDeleteModel() {
  openDeleteModelPost.value = !openDeleteModelPost.value;
}

function cancelDeletePost() {
  openDeleteModelPost.value = false;
}

function deletePost(postId) {
  postStore.deletePost(postId);
}

function toggleDropdown(commentId) {
  dropdownOpen.value = dropdownOpen.value === commentId ? null : commentId;
}

function sendComment() {
  postStore.addComment(postId, newComment.value);
  newComment.value = "";
}

onMounted(async () => {
  isLoading.value = true;
  await postStore.getPost(postId);
  isLoading.value = false;
});

watchEffect(async () => {
  await postStore.getPost(postId);
});

function formatDateTime(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString() + " " + date.toLocaleTimeString();
}

const editComment = (comment) => {
  editingCommentId.value = comment.id;
  editedCommentContent.value = comment.content;
  dropdownOpen.value = null; // Close the options dropdown
};

const cancelEdit = () => {
  editingCommentId.value = null;
  editedCommentContent.value = '';
};

const updateComment = (commentId) => {
  postStore.editComment(postId, commentId, editedCommentContent.value);
  const comment = postStore.post.comments.find(c => c.id === commentId);
  if (comment) {
    comment.content = editedCommentContent.value;
  }
  editingCommentId.value = null;
  editedCommentContent.value = '';
};

const confirmDeleteComment = (commentId) => {
  confirmDeleteCommentId.value = commentId;
  showDeleteCommentConfirmation.value = true;
};

function deleteComment(commentId) {
  postStore.destroyComment(postId, commentId);
  showDeleteCommentConfirmation.value = false;
  postStore.post.comments = postStore.post.comments.filter(c => c.id !== commentId);
}

const cancelDeleteComment = () => {
  showDeleteCommentConfirmation.value = false;
  confirmDeleteCommentId.value = null;
};

</script>

<template>
  <div v-if="isLoading" class="flex justify-center items-center h-screen">
    <div class="loader"></div>
  </div>
  <div v-else>
    <!-- Post content -->
    <div class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow-md overflow-y-scroll max-h-[90vh]">
      <!-- Title and basic information -->
      <div class="mb-4">
        <h1 class="text-4xl font-bold text-gray-900">{{ postStore.post.title }}</h1>
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
          <time v-if="postStore.post.created_at" :datetime="postStore.post.created_at">
            {{ formatDateTime(postStore.post.created_at) }}
          </time>
        </div>
      </div>

      <!-- Post content -->
      <div class="mt-6 text-lg text-gray-800">
        <div v-html="postStore.post.content"></div>
      </div>

      <!-- Categories and tags -->
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

      <!-- Delete and update post buttons -->
      <div v-if="postStore.post.user_id == authStore.user.id">
        <router-link
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
                      d="M130.35,216 L132.45,216 L132.45,208 L130.35,208 L130.35,216 Z M134.55,216 L136.65,216 L136.65,208 L134.55,208 L134.55,216 Z M128.25,218 L138.75,218 L138.75,206 L128.25,206 L128.25,218 Z M136.78,204 L130.22,204 L130.22,202.5 L126.5,202.5 L126.5,204 L122,204 L122,206 L141,206 L141,204 L136.5,204 L136.5,202.5 L132.78,202.5 L132.78,204 Z"
                      id="delete-[#1487]"
                    ></path>
                  </g>
                </g>
              </g>
            </g>
          </svg>
        </button>
      </div>

      <!-- Comments -->
      <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-900">Comments</h2>
        <div
          v-for="comment in postStore.post.comments"
          :key="comment.id"
          class="mt-4 bg-gray-100 p-4 rounded-lg relative"
        >
          <div class="text-sm text-gray-500">
            {{ comment.user.name }} | {{ formatDateTime(comment.created_at) }}
          </div>
          <p
            v-if="editingCommentId !== comment.id"
            class="mt-2 text-gray-800"
          >
            {{ comment.content }}
          </p>
          <div v-else class="mt-2">
            <textarea
              v-model="editedCommentContent"
              class="w-full p-2 border border-gray-300 rounded-lg"
            ></textarea>
            <div class="mt-2 flex justify-end">
              <button
                @click="cancelEdit"
                class="bg-gray-500 text-white px-3 py-1 rounded-md mr-2"
              >
                Cancel
              </button>
              <button
                @click="updateComment(comment.id)"
                class="bg-blue-500 text-white px-3 py-1 rounded-md"
              >
                Save
              </button>
            </div>
          </div>
          <div class="absolute top-0 right-0 m-2" v-if="comment.user.id  === authStore.user.id">
            <div class="relative">
              <button
                @click="toggleDropdown(comment.id)"
                class="text-gray-500 hover:text-gray-700"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.75 12a.75.75 0 100-1.5.75.75 0 000 1.5zM12 12a.75.75 0 100-1.5.75.75 0 000 1.5zM17.25 12a.75.75 0 100-1.5.75.75 0 000 1.5z"
                  />
                </svg>
              </button>
              <ul
                v-if="dropdownOpen === comment.id"
                class="absolute right-0 mt-2 py-1 w-32 bg-white border rounded-md shadow-xl z-10"
              >
                <li>
                  <button
                    @click="editComment(comment)"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Edit
                  </button>
                </li>
                <li>
                  <button
                    @click="confirmDeleteComment(comment.id)"
                    class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100"
                  >
                    Delete
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Confirm delete comment -->
        <div
          v-if="showDeleteCommentConfirmation"
          class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
        >
          <div class="bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">
              Are you sure you want to delete this comment?
            </h3>
            <div class="mt-4 flex justify-end">
              <button
                @click="cancelDeleteComment"
                class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2"
              >
                Cancel
              </button>
              <button
                @click="deleteComment(confirmDeleteCommentId)"
                class="bg-red-500 text-white px-4 py-2 rounded-md"
              >
                Delete
              </button>
            </div>
          </div>
        </div>

        <!-- Add new comment -->
        <div class="mt-6">
          <textarea
            v-model="newComment"
            placeholder="Write a comment..."
            class="w-full p-4 border border-gray-300 rounded-lg"
          ></textarea>
          <button
            @click="sendComment"
            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md"
          >
            Send
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete post confirmation modal -->
  <div
    v-if="openDeleteModelPost"
    class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg p-6">
      <h3 class="text-lg font-semibold text-gray-800">
        Are you sure you want to delete this post?
      </h3>
      <div class="mt-4 flex justify-end">
        <button
          @click="cancelDeletePost"
          class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2"
        >
          Cancel
        </button>
        <button
          @click="deletePost(postStore.post.id)"
          class="bg-red-500 text-white px-4 py-2 rounded-md"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>
