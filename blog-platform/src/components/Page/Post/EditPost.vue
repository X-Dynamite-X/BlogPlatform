
<script setup>
import { onMounted, ref, computed, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePostStore } from '../../../stores/post';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// تهيئة المسارات والمتاجر
const route = useRoute();
const router = useRouter();
const postStore = usePostStore();
const isLoading = ref(false);

// تهيئة بيانات النموذج
const form = ref({
  title: '',
  content: '',
  category_id: null,
  tags: [],
});

// حالة القائمة المنسدلة
const dropdownOpen = ref(false);

// مرجع لمحرر Quill
const editor = ref(null);

// تحميل بيانات المشاركة والفئات/العلامات عند تحميل المكون
onMounted(async () => {
  isLoading.value = true;

  const postId = route.query.postId;
  await postStore.getPost(postId);
  isLoading.value = false;

  form.value = {
    title: postStore.post.title,
    content: postStore.post.content,
    category_id: postStore.post.category_id,
    tags: postStore.post.tags.map(tag => tag.id),
  };

  // استخدم nextTick لضمان تحميل المحرر بالكامل قبل تهيئة Quill
  await nextTick(() => {
    editor.value = new Quill('#editor', {
      theme: 'snow',
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, 4, 5, 6, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ color: [] }, { background: [] }],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link', 'image'],
        ],
      },
    });

    // إعداد المحتوى المبدئي للمحرر
    editor.value.root.innerHTML = form.value.content;

    // تحديث المحتوى في النموذج عند تغيير المحرر
    editor.value.on('text-change', () => {
      form.value.content = editor.value.root.innerHTML;
    });
  });

  await postStore.getAllTag();
  await postStore.getAllCategory();
});

// الخصائص المحسوبة للفئات والعلامات
const categories = computed(() => postStore.categories);
const tags = computed(() => postStore.tags);

// تبديل القائمة المنسدلة
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// توليد نص العلامات المحددة
const selectedTagsText = computed(() => {
  return form.value.tags.length
    ? tags.value
        .filter(tag => form.value.tags.includes(tag.id))
        .map(tag => tag.name)
        .join(', ')
    : 'Select tags';
});

// دالة إرسال النموذج
async function submitForm() {
  // الوصول إلى محتوى محرر Quill من خلال `editor.value`
  form.value.content = editor.value.root.innerHTML;

  await postStore.updatePost(form.value, route.query.postId);
}
</script>


<template>
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
  <div class="loader"></div>
</div>
    <div v-else class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg mt-10">
      <h2 class="text-2xl font-semibold text-gray-900 mb-6">Edit Post</h2>
      <form @submit.prevent="submitForm" method="post">
        <!-- Title Input Field -->
        <div class="mb-4">
          <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
          <input
            v-model="form.title"
            type="text"
            id="title"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter the post title"
          />
        </div>
        <span v-if="postStore.errors.title" class="text-red-500 text-sm">{{
        postStore.errors.title[0]
      }}</span>

        <!-- Content Input Field -->
        <div class="mb-4">
          <label for="content" class="block text-gray-700 font-medium mb-2">Content</label>
          <div id="editor" class="w-full px-4 py-2 border rounded-lg"></div>
        </div>
        <span v-if="postStore.errors.content" class="text-red-500 text-sm">{{
        postStore.errors.content[0]
      }}</span>

        <!-- Category Dropdown -->
        <div class="mb-4">
          <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
          <select
            v-model="form.category_id"
            id="category"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>
        <span v-if="postStore.errors.category_id" class="text-red-500 text-sm">{{
        postStore.errors.category_id[0]
      }}</span>

        <!-- Tags Multiselect Dropdown -->
        <div class="mb-4 relative">
          <label class="block text-gray-700 font-medium mb-2">Tags</label>
          <div class="w-full">
            <div
              @click="toggleDropdown"
              class="border rounded-lg px-4 py-2 cursor-pointer flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <span class="text-gray-700">{{ selectedTagsText }}</span>
              <svg
                class="w-4 h-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </div>
            <div
              v-if="dropdownOpen"
              class="absolute w-full mt-1 bg-white border rounded-lg shadow-lg z-10"
            >
              <div class="p-2 max-h-48 overflow-y-auto">
                <label
                  v-for="tag in tags"
                  :key="tag.id"
                  class="flex items-center py-1 px-2 hover:bg-gray-100 cursor-pointer"
                >
                  <input
                    type="checkbox"
                    :value="tag.id"
                    v-model="form.tags"
                    class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                  />
                  <span class="ml-2 text-gray-700">{{ tag.name }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Update Post
          </button>
        </div>
      </form>
    </div>
  </template>
