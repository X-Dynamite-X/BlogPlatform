<script setup>
import { ref, onMounted } from 'vue';
import { useRoleStore } from '../../../stores/roles'; // استخدام الـ store الخاص بالأدوار (Roles)

const roleStore = useRoleStore();
const role = ref({ name: '' });
const errors = ref({});
const roles = ref([]);

onMounted(async () => {
  await fetchRoles();
});

// Fetch roles from the store
const fetchRoles = async () => {
  roles.value = await roleStore.getAllRoles();
  // إضافة حالة isEditing لكل دور
  roles.value.forEach(roleItem => roleItem.isEditing = false);
};

// Add a new role
const submitForm = async () => {
  try {
    await roleStore.addRole(role.value);
    role.value.name = '';
    errors.value = {};
    fetchRoles();
  } catch (error) {
    errors.value = error.response.data.errors || { general: 'An error occurred' };
  }
};

// Save the updated role
const saveRole = async (roleItem) => {
  try {
    await roleStore.updateRole(roleItem.id, { name: roleItem.name });
    roleItem.isEditing = false;
  } catch (error) {
    console.error('Error updating role:', error);
    // يمكنك هنا التعامل مع الخطأ بشكل مناسب، مثلاً عرض رسالة للمستخدم
  }
};

// Delete a role
const deleteRole = async (roleId) => {
  await roleStore.deleteRole(roleId);
  fetchRoles();
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg mt-10">
      <h2 class="text-2xl font-semibold text-gray-900 mb-6">Manage Roles</h2>

      <form @submit.prevent="submitForm" method="post">
        <!-- Role Name Input Field -->
        <div class="mb-4">
          <label for="roleName" class="block text-gray-700 font-medium mb-2">Role Name</label>
          <input
            v-model="role.name"
            type="text"
            id="roleName"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter role name"
          />
        </div>
        <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Add Role
          </button>
        </div>
      </form>

      <!-- Roles List -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Roles</h3>
        <ul>
          <li v-for="roleItem in roles" :key="roleItem.id" class="mb-2 flex justify-between items-center">
            <!-- عرض النص أو الحقل بناءً على حالة isEditing -->
            <div v-if="!roleItem.isEditing  ">
              <p>{{ roleItem.name }}</p>
              <button v-if="roleItem.name !== 'admin'" @click="roleItem.isEditing = true" class="text-blue-500 hover:underline mr-2">Edit</button>
              
            </div>
            <div v-else >
              <input
                v-model="roleItem.name"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button @click="saveRole(roleItem)" class="text-green-500 hover:underline mr-2">Save</button>
              <button @click="roleItem.isEditing = false" class="text-red-500 hover:underline">Cancel</button>
            </div>
            <button v-if ="roleItem.name !== 'admin'" @click="deleteRole(roleItem.id)" class="text-red-500 hover:underline">Delete</button>
          </li>
        </ul>
      </div>
    </div>
  </template>

