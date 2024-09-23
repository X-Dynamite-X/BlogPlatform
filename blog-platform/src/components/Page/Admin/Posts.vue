<script setup>
import { ref, computed, onMounted } from "vue";
import { useAdminStore } from "../../../stores/admin";
import ConfirmationModal from "../../../components/Admin/Model/ConfirmationModal.vue";
import dayjs from "dayjs"; // Import dayjs for date formatting

// Store and refs
const adminStore = useAdminStore();
const modalType = ref(null);
const selectedPostId = ref(null);
const searchQuery = ref(""); // Reactive property for the search input

// Pagination refs
const currentPage = ref(1);
const rowsPerPage = ref(10);
const rowsPerPageOptions = [10, 25, 50, 100];
const isLoading = ref(false)

// Filter states
const showFilters = ref({
  id: false,
  userId: false,
  userName: false,
  title: false,
  category: false,
  tags: false,
  role: false,
  createdAt: false,
  updatedAt: false,
});

const columnFilters = ref({
  id: "",
  userId: "",
  userName: "",
  title: "",
  category: "",
  tags: "",
  role: "",
  createdAt: "",
  updatedAt: "",
});

// Methods
const openDeletePostModal = (postId) => {
  modalType.value = "delete";
  selectedPostId.value = postId;
};

const closeModal = () => {
  modalType.value = null;
  selectedPostId.value = null;
};

const deletePost = async (postId) => {
  try {
    await adminStore.deletePost(postId);
    await adminStore.getAllPost(); // Update post list after deletion
    closeModal();
  } catch (error) {
    console.error("Error deleting post:", error);
  }
};

const toggleFilter = (column) => {
  showFilters.value[column] = !showFilters.value[column];
};

// Computed property to filter and paginate posts
const filteredPosts = computed(() => {
  let filtered = adminStore.posts;

  // Global search query
  if (searchQuery.value) {
    const lowerCaseQuery = searchQuery.value.toLowerCase();
    filtered = filtered.filter((post) => {
      const matchesTitle = post.title.toLowerCase().includes(lowerCaseQuery);
      const matchesUserName = post.user.name
        .toLowerCase()
        .includes(lowerCaseQuery);
      const matchesPostId = post.id.toString().includes(lowerCaseQuery);
      const matchesUserId = post.user_id.toString().includes(lowerCaseQuery);

      return matchesTitle || matchesUserName || matchesPostId || matchesUserId;
    });
  }

  // Column-specific filters
  Object.keys(columnFilters.value).forEach((key) => {
    if (columnFilters.value[key]) {
      const lowerCaseFilter = columnFilters.value[key].toLowerCase();
      filtered = filtered.filter((post) => {
        switch (key) {
          case "id":
            return post.id.toString().includes(lowerCaseFilter);
          case "userId":
            return post.user_id.toString().includes(lowerCaseFilter);
          case "userName":
            return post.user.name.toLowerCase().includes(lowerCaseFilter);
          case "title":
            return post.title.toLowerCase().includes(lowerCaseFilter);
          case "category":
            return post.category.name.toLowerCase().includes(lowerCaseFilter);
          case "tags":
            return post.tags.some((tag) =>
              tag.name.toLowerCase().includes(lowerCaseFilter)
            );
          case "role":
            return post.user.roles && post.user.roles.length > 0
              ? post.user.roles[0].name.toLowerCase().includes(lowerCaseFilter)
              : false;
          case "createdAt":
            return dayjs(post.created_at)
              .format("DD/MM/YYYY HH:mm")
              .includes(lowerCaseFilter);
          case "updatedAt":
            return dayjs(post.updated_at)
              .format("DD/MM/YYYY HH:mm")
              .includes(lowerCaseFilter);
          default:
            return true;
        }
      });
    }
  });

  // Paginate filtered posts
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(adminStore.posts.length / rowsPerPage.value);
});

const goToPage = (page) => {
  currentPage.value = page;
};

onMounted(async () => {
    isLoading.value =true

  await adminStore.getAllPost();
  isLoading.value =false
});
</script>

<template>
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
  <div class="loader"></div>
</div>
  <div v-else class="flex-grow p-4 container mx-auto mt-4">
    <!-- Search input field -->
    <div class="mb-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search posts..."
        class="w-full p-2 border border-gray-300 rounded-lg"
      />
    </div>

    <!-- Rows per page selector -->
    <div class="mb-4">
      <label for="rowsPerPage" class="mr-2">Rows per page:</label>
      <select
        v-model="rowsPerPage"
        id="rowsPerPage"
        class="p-2 border border-gray-300 rounded-lg"
      >
        <option
          v-for="option in rowsPerPageOptions"
          :key="option"
          :value="option"
        >
          {{ option }}
        </option>
      </select>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table
        id="post_table"
        class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md"
      >
        <thead class="bg-gray-200 ">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Post ID
              <svg
                @click="toggleFilter('id')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.id"
                v-model="columnFilters.id"
                type="text"
                placeholder="Filter by ID"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              User ID
              <svg
                @click="toggleFilter('userId')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.userId"
                v-model="columnFilters.userId"
                type="text"
                placeholder="Filter by User ID"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="py-3 text-center text-sm font-semibold">
              User Name
              <svg
                @click="toggleFilter('userName')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.userName"
                v-model="columnFilters.userName"
                type="text"
                placeholder="Filter by User Name"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Title Post
              <svg
                @click="toggleFilter('title')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.title"
                v-model="columnFilters.title"
                type="text"
                placeholder="Filter by Title"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Category
              <svg
                @click="toggleFilter('category')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.category"
                v-model="columnFilters.category"
                type="text"
                placeholder="Filter by Category"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Tags
              <svg
                @click="toggleFilter('tags')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.tags"
                v-model="columnFilters.tags"
                type="text"
                placeholder="Filter by Tags"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Role
              <svg
                @click="toggleFilter('role')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.role"
                v-model="columnFilters.role"
                type="text"
                placeholder="Filter by Role"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Created At
              <svg
                @click="toggleFilter('createdAt')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.createdAt"
                v-model="columnFilters.createdAt"
                type="text"
                placeholder="Filter by Created At"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Updated At
              <svg
                @click="toggleFilter('updatedAt')"
                class="inline-block w-4 h-4 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />

              </svg>

              <input
                v-if="showFilters.updatedAt"
                v-model="columnFilters.updatedAt"
                type="text"
                placeholder="Filter by Updated At"
                class="mt-2 w-full p-1 border border-gray-300 rounded-lg"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
          <tr
            v-for="(post, index) in filteredPosts"
            :key="index"
            :class="index % 2 == 0 ? 'bg-gray-100' : 'bg-white'"
            class="hover:bg-gray-200"
          >
            <td class="px-6 py-4 text-sm text-gray-700">{{ post.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ post.user_id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">
              {{ post.user.name }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ post.title }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">
              <router-link
                :to="{
                  name: 'postInCategory',
                  params: { categoryName: post.category.name },
                  query: { categoryId: post.category.id },
                }"
                class="text-blue-500 hover:underline"
              >
                {{ post.category.name }}
              </router-link>
            </td>
            <td class="px-6 py-4 text-sm text-gray-700">
              <div v-if="post.tags.length > 0">
                <p
                  class="text-blue-500 hover:underline"
                  v-for="tag in post.tags"
                  :key="tag.id"
                >
                  {{ tag.name }}<span v-if="!$last"> | </span>
                </p>
              </div>
              <div v-else>
                <p class="text-red-700">No Tags assigned</p>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-700">
              {{
                post.user.roles && post.user.roles.length > 0
                  ? post.user.roles[0].name
                  : "No role assigned"
              }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-700">
              {{ dayjs(post.created_at).format("DD/MM/YYYY HH:mm") }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-700">
              {{
                post.created_at === post.updated_at
                  ? "not updated"
                  : dayjs(post.updated_at).format("DD/MM/YYYY HH:mm")
              }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-700 flex space-x-2">
              <button
                @click="openDeletePostModal(post.id)"
                class="bg-red-500 hover:bg-red-600 focus:ring-2 focus:ring-red-400 text-white font-bold py-1 px-3 rounded-lg transition duration-300 ease-in-out"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <button
        @click="goToPage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg"
      >
        Previous
      </button>
      <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="goToPage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg"
      >
        Next
      </button>
    </div>

    <!-- Confirmation Modal -->
    <ConfirmationModal
      v-if="modalType === 'delete' && selectedPostId"
      :postId="selectedPostId"
      @close="closeModal"
      @confirm="deletePost(selectedPostId)"
    />
  </div>
</template>

<style scoped>
.hover\:bg-gray-200:hover {
  background-color: #e5e7eb;
}
</style>
ش
