<template>
  <section class="grid md:grid-cols-2 xl:grid-cols-4 gap-6 pl-8">
    <!-- My Shared Recipes -->
    <div class="flex items-center p-8 bg-white shadow rounded-lg">
      <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-orange-600 bg-orange-100 rounded-full mr-6">
        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
      <div>
        <span class="block text-2xl font-bold">{{ recipeCount }}</span>
        <span class="block text-gray-500">My Shared Recipes</span>
      </div>
    </div>

    <!-- Total Comments -->
    <div class="flex items-center p-8 bg-gray-800 shadow rounded-lg">
      <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-orange-600 bg-orange-100 rounded-full mr-6">
        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 04 0z" />
        </svg>
      </div>
      <div>
        <span class="block text-2xl font-bold text-gray-200">{{ totalComments }}</span>
        <span class="block text-gray-400">Total Comments</span>
      </div>
    </div>

    <!-- Categories Used in My Recipes -->
    <div class="flex items-center p-8 bg-white shadow rounded-lg">
      <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6">
        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
      </div>
      <div v-if="usedCategories">
        <span class="block text-2xl font-bold">{{ usedCategories.length }}</span>
        <span class="block text-gray-500">Categories Used in My Recipes</span>
      </div>
    </div>

    <!-- Total Likes of My Recipes -->
    <div class="flex items-center p-8 bg-gray-800 shadow rounded-lg">
      <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-red-600 bg-red-100 rounded-full mr-6">
        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
        </svg>
      </div>
      <div>
        <span class="block text-2xl font-bold text-gray-200">{{ totalLikes }}</span>
        <span class="block text-gray-400">Total Likes of My Recipes</span>
      </div>
    </div>
  </section>
</template>

<script>
import axiosClient from '../../api/axios';

export default {
  data() {
    return {
      recipes: [],
      totalComments: 0,
      usedCategories: [],
      totalLikes: 0,
    };
  },
  computed: {
    recipeCount() {
      return this.recipes.length;
    }
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axiosClient.get('/dashboard');
        this.recipes = response.data.recipes;
        this.totalComments = response.data.totalComments;
        this.usedCategories = response.data.usedCategories;
        this.totalLikes = response.data.totalLikes;
      } catch (error) {
        console.error('Error fetching data:', error.response ? error.response.data : error.message);
      }
    }
  }
};
</script>

<style scoped>
/* Add scoped styles here if needed */
</style>
