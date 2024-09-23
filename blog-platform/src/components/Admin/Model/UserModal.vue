<template>
    <div  class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
      <div  :class="[isLoading ? ' bg-opacity-70 bg-gray-900' :'']" class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4">
          {{ isEditMode ? 'Edit User' : 'View User' }}
        </h2>
        <div v-if="user">
          <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input
              v-if="isEditMode"
              v-model="user.name"
              type="text"
              class="w-full p-2 border rounded-lg"
            />
            <p v-else class="text-gray-800">{{ user.name }}</p>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input
              v-if="isEditMode"
              v-model="user.email"
              type="email"
              class="w-full p-2 border rounded-lg"
            />
            <p v-else class="text-gray-800">{{ user.email }}</p>

          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Role</label>
            <p v-if = "isEditMode && user.roles[0].name === 'admin'" class="text-gray-800">
              {{ user.roles && user.roles.length > 0 ? user.roles[0].name : 'No role assigned' }}
            </p>
            <select
              v-else-if="isEditMode && user.roles[0].name !== 'admin'"
              v-model="selectedRole"
              class="w-full p-2 border rounded-lg"
            >
              <option v-for="role in adminStore.allRoles" :key="role.id" :value="role.name">
                {{ role.name }}
              </option>
            </select>


            <p v-else class="text-gray-800">
              {{ user.roles && user.roles.length > 0 ? user.roles[0].name : 'No role assigned' }}
            </p>
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-2">
          <button
            v-if="isEditMode"
            @click="save"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
          >
            Save
          </button>
          <button
            @click="$emit('close')"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
          >
            Close
          </button>
        </div>
      </div>
      <div  v-if="isLoading" class="fixed justify-center items-center z-50">
      <div class="loader"></div> <!-- You can replace this with a spinner component or CSS animation -->
    </div>
    </div>
  </template>

  <script setup>
  import { defineProps, defineEmits, ref, watch } from 'vue';
  import { useAdminStore } from '@/stores/admin';

  const adminStore = useAdminStore();
  const props = defineProps({
    user: Object,
    isEditMode: {
      type: Boolean,
      default: false,
    },
    isLoading:{
        type:Boolean,
        default:false
    }
  });

  const emit = defineEmits(['close', 'save']);

  const selectedRole = ref(props.user ? props.user.roles[0]?.name : '');

  // Watch for changes in user roles
  watch(() => props.user, (newUser) => {
    if (newUser && newUser.roles && newUser.roles.length > 0) {
      selectedRole.value = newUser.roles[0].name;
    }
  }, { immediate: true });

  function save() {
    // Create a new user object to pass to the save event
    const updatedUser = { ...props.user, role: selectedRole.value };
    emit('save', updatedUser);
  }
  </script>
