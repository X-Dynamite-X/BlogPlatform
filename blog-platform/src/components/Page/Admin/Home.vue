<script setup>
import { ref, onMounted, computed } from "vue";
import { useAdminStore } from "../../../stores/admin";
import UserModal from "../../../components/Admin/Model/UserModal.vue";
import ConfirmationModal from "../../../components/Admin/Model/ConfirmationModal.vue";
import SideBar from "../../../components/Admin/layouts/SideBar.vue";

// Store and refs
const adminStore = useAdminStore();
const modalType = ref(null);
const selectedUser = ref(null);
const selectedUserId = ref(null);

// Filter states
const showFilters = ref({
  id: false,
  name: false,
  email: false,
  role: false
});

const columnFilters = ref({
  id: '',
  name: '',
  email: '',
  role: ''
});
const isLoading = ref(false);


// Additional states
const searchQuery = ref('');
const rowsPerPage = ref(10);
const rowsPerPageOptions = [10, 25, 50, 100];
const currentPage = ref(1);

// Methods
const toggleFilter = (column) => {
  showFilters.value[column] = !showFilters.value[column];
};

const openViewUserModal = (user) => {
  modalType.value = "view";
  selectedUser.value = user;
};

const openEditUserModal = (user) => {
  modalType.value = "edit";
  selectedUser.value = { ...user };
};

const openDeleteUserModal = (userId) => {
  modalType.value = "delete";
  selectedUserId.value = userId;
};

const closeModal = () => {
  modalType.value = null;
  selectedUser.value = null;
  selectedUserId.value = null;
};

const saveUser = async (user) => {

  try {
      closeModal();
    await adminStore.updateUser(user);
    await adminStore.getAllUsers(); // تحديث قائمة المستخدمين بعد التعديل
  } catch (error) {
    console.error("Error updating user:", error);
  }
};

const deleteUser = async (userId) => {

  try {
      closeModal();
    await adminStore.deleteUser(userId);
    await adminStore.getAllUsers(); // تحديث قائمة المستخدمين بعد الحذف


  } catch (error) {
    console.error("Error deleting user:", error);
  }
};

// Filtering logic
const filteredUsers = computed(() => {
  return adminStore.users.filter((user) => {
    const matchesSearchQuery = searchQuery.value === '' ||
      (user.id.toString().includes(searchQuery.value) ||
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (user.roles && user.roles[0] && user.roles[0].name.toLowerCase().includes(searchQuery.value.toLowerCase())));

    const matchesFilters = (
      (columnFilters.value.id === '' || user.id.toString().includes(columnFilters.value.id)) &&
      (columnFilters.value.name === '' || user.name.toLowerCase().includes(columnFilters.value.name.toLowerCase())) &&
      (columnFilters.value.email === '' || user.email.toLowerCase().includes(columnFilters.value.email.toLowerCase())) &&
      (columnFilters.value.role === '' || (user.roles && user.roles[0] && user.roles[0].name.toLowerCase().includes(columnFilters.value.role.toLowerCase())))
    );

    return matchesSearchQuery && matchesFilters;
  });
});

// Pagination logic
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return filteredUsers.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredUsers.value.length / rowsPerPage.value);
});

const goToPage = (page) => {
  if (page > 0 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

onMounted(async () => {
    isLoading.value = true; // Start loading on mount

  await adminStore.getAllUsers();
  isLoading.value = false; // Stop loading after fetching data

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
        placeholder="Search users..."
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

    <div class="overflow-x-auto">
      <table
        id="user_table"
        class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md"
      >
        <thead class="bg-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              ID
              <svg @click="toggleFilter('id')" class="inline-block w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
              </svg>
              <input v-if="showFilters.id" v-model="columnFilters.id" type="text" placeholder="Filter by ID" class="mt-2 w-full p-1 border border-gray-300 rounded-lg"/>
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Name
              <svg @click="toggleFilter('name')" class="inline-block w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
              </svg>
              <input v-if="showFilters.name" v-model="columnFilters.name" type="text" placeholder="Filter by Name" class="mt-2 w-full p-1 border border-gray-300 rounded-lg"/>
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Email
              <svg @click="toggleFilter('email')" class="inline-block w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
              </svg>
              <input v-if="showFilters.email" v-model="columnFilters.email" type="text" placeholder="Filter by Email" class="mt-2 w-full p-1 border border-gray-300 rounded-lg"/>
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Role
              <svg @click="toggleFilter('role')" class="inline-block w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
              </svg>
              <input v-if="showFilters.role" v-model="columnFilters.role" type="text" placeholder="Filter by Role" class="mt-2 w-full p-1 border border-gray-300 rounded-lg"/>
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              Action
            </th>
          </tr>
        </thead>
        <tbody id="tbodyUser" class="divide-y divide-gray-200">
          <tr
            v-for="(user, index) in paginatedUsers"
            :key="index"
            :class="index % 2 === 0 ? 'bg-stone-200' : 'bg-gray-100'"
            class="hover:bg-gray-200"
          >
            <td class="px-6 py-4 text-sm text-gray-900">{{ user.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ user.name }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ user.email }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">
              {{
                user.roles && user.roles.length > 0
                  ? user.roles[0].name
                  : "No role assigned"
              }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 flex space-x-2">
              <button
                @click="openViewUserModal(user)"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-lg"
              >
                View
              </button>
              <button
                @click="openEditUserModal(user)"
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded-lg"
              >
                Edit
              </button>
              <button
                v-if="user.roles[0] && user.roles[0].name !== 'admin'"
                @click="openDeleteUserModal(user.id)"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-lg"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination controls -->
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

    <UserModal
      v-if="modalType === 'view'"
      :user="selectedUser"
      @close="closeModal"
    />
    <UserModal
      v-if="modalType === 'edit'"
      :user="selectedUser"
      :isEditMode="true"
      @close="closeModal"
      @save="saveUser"

    />
    <ConfirmationModal
      v-if="modalType === 'delete'"
      @confirm="deleteUser(selectedUserId)"
      @close="closeModal"

      title="Delete User"
      message="Are you sure you want to delete this user?"
    />
  </div>
</template>
