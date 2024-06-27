<template>
    <section class="grid md:grid-cols-2 xl:grid-cols-4 xl:grid-rows-3 xl:grid-flow-col gap-6 mx-auto
    px-8 py-8">
      <!-- Recent Recipes -->
      <div class="flex flex-col md:col-span-2 md:row-span-2 bg-white shadow rounded-lg border-2">
        <div class="px-6 py-5 font-semibold border-b border-gray-100">Recent Recipes</div>
        <div class="p-4 flex-grow overflow-y-auto" style="max-height: 44rem;">
          <div v-if=" sortedRecipes.length === 0">
            <p class="text-gray-600">No recipes found.</p>
          </div>

          
          <ul v-else class="divide-y divide-gray-200">
            <li v-for="recipe in sortedRecipes" :key="recipe.id"  >
                <router-link :to="recipeRoute(recipe.id)" class="py-4 flex justify-between items-center cursor-pointer">
                    <div class="flex items-center">
                <img :src="recipe.image_url" :alt="recipe.title" class="h-10 w-10 rounded-full">
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ recipe.title }}</div>
                  <div class="text-sm text-gray-500">Created on {{ recipe.created_at }}</div>
                </div>
              </div>
              <button class="text-blue-600 font-semibold" >View Recipe</button>
             </router-link> 
            </li>
          </ul>
      

        </div>
      </div>
  
      <!-- Most Viewed Recipes -->
      <div class="row-span-2 bg-white shadow rounded-lg border-2">
        <div class="flex items-center justify-between px-6 py-5 font-semibold border-b border-gray-100">
          <span>Most Liked Recipes</span>
        </div>
        <div class="overflow-y-auto" style="max-height: 38rem;">
          <div v-if="sortedByLikes.length === 0">
            <p class="text-gray-600">No recipes found.</p>
          </div>
          <ul class="p-6 space-y-6"> 
            <li v-for="recipe in sortedByLikes" :key="recipe.id" class="flex items-center">
              <div>
                <img :src="recipe.image_url" class="h-10 w-10 mr-3 object-cover rounded-md">
              </div>
              <span class="text-gray-600 px-2">{{ recipe.title }}</span>
              <span class="ml-auto font-semibold">{{ recipe.likes.length || 0 }}</span>
            </li>
          </ul>

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
      };
    },
    mounted() {
      this.fetchData();
    },
    computed: {
      sortedRecipes() {
        return this.recipes.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      },
      sortedByLikes() {
        return this.recipes.slice().sort((a, b) => {
          const likesA = a.likes && a.likes.length ? a.likes.length : 0;
          const likesB = b.likes && b.likes.length ? b.likes.length : 0;
          return likesB - likesA;
        });
      },
    },
    methods: {
      async fetchData() {
        try {
          const response = await axiosClient.get('/dashboard');
          this.recipes = response.data.recipes;
        } catch (error) {
          console.error('Error fetching data:', error.response ? error.response.data : error.message);
        }
      },
      recipeRoute(recipeId) {
      return `/show-recipe/${recipeId}`;
    }

    },
  };
  </script>
  
  
  <style scoped>
  /* Add any scoped styles if necessary */
  </style>
  