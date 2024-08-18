<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../../../stores/auth";

const authStore = useAuthStore();

// Create refs for the form fields
const name = ref("");
const email = ref("");

// const socialLinks = ref({
//   facebook: "",
//   twitter: "",
//   instagram: "",
//   linkedin: ""
// });

// Fetch the user's profile data on component mount
onMounted(async () => {
    await authStore.getProfile(authStore.user.id);
    name.value = authStore.profile.name;
    email.value = authStore.profile.email;
  // If you have social links stored in the profile, load them here
  // socialLinks.value = authStore.profile.socialLinks;
});

// Function to handle form submission
const saveProfile = async () => {
  // Construct the payload
  const payload = {
    name: name.value,
    email: email.value,
    // socialLinks: socialLinks.value
  };

  // Send the payload to the server
  await authStore.updateProfile(authStore.user.id, payload);
};
</script>

<template>
  <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg mt-10">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">Edit Profile</h1>

    <!-- Form -->
    <form @submit.prevent="saveProfile">
      <!-- Name -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input v-model="name" type="text" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="email" disabled type="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>

      <!-- Social Links -->

      <!-- <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Social Links</h2>
        <div class="mb-4">
          <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
          <input v-model="socialLinks.facebook" type="url" id="facebook" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label for="twitter" class="block text-sm font-medium text-gray-700">Twitter</label>
          <input v-model="socialLinks.twitter" type="url" id="twitter" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
          <input v-model="socialLinks.instagram" type="url" id="instagram" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
          <input v-model="socialLinks.linkedin" type="url" id="linkedin" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
      </div> -->

      <!-- Save Button -->
      <div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          Save Changes
        </button>
      </div>
    </form>
  </div>
</template>
