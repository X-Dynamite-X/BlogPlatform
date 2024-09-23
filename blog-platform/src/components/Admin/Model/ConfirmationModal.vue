<template>

<div   class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">

    <div v-if="isLoading" :class="[isLoading ? '  ' :'']" class="fixed justify-center items-center z-50 ">
      <div class="loader"></div> <!-- You can replace this with a spinner component or CSS animation -->
    </div>
        <div :class="[isLoading ? 'bg-gray-900 bg-opacity-50' :'']" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

        <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
        <p class="text-gray-800 mb-6">{{ message }}</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="$emit('confirm')"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
          >
            Delete
          </button>
          <button
            @click="$emit('close')"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
          >
            Cancel
          </button>
        </div>
      </div>

    </div>

  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';

  const props = defineProps({
    title: String,
    message: String,
    isLoading:{
        type:Boolean,
        default:false
    }
  });

  const emit = defineEmits(['close', 'confirm']);
  </script>

