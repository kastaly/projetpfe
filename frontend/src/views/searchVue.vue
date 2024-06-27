<template>
  <div class="bg-gray-800 pb-5">
    <!-- Search form -->
    <div class="relative p-12 w-full md:w-3/4 mx-auto">
      <div class="overflow-hidden z-0 rounded-full relative p-3 glow-container">
        <div class="flex items-center space-x-5">
          <!-- Search input and button -->
          <form @submit.prevent="searchUsers" role="form" class="relative flex z-50 bg-white rounded-full">
            <input v-model="searchQuery" type="text" placeholder="Looking for a chef? Enter your search here ..." class="rounded-full px-16 py-3 text-gray-700 flex-1 focus:outline-none">
            <button type="submit" class="bg-yellow-600 text-white rounded-full font-semibold px-6 py-3 hover:bg-indigo-400 focus:bg-indigo-600 focus:outline-none">Search</button>
          </form>
          
          <!-- Clear button -->
          <button type="button" @click="clearSearch" class="bg-white text-gray-700 rounded-full font-semibold px-6 py-3 hover:bg-gray-400 focus:bg-gray-500 focus:outline-none">
            Clear Search
          </button>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div id="app" class="md:flex w-3/4 mx-auto justify-between " v-if="suggestedChefs && suggestedChefs.length > 0">
      <!-- Suggested Chefs -->
      <div class="md:w-4/12">
        <main>
          <div v-for="chef in suggestedChefs" :key="chef.id" class="card mb-4">
            <header class="card__header">
              <img :src="chef.profile_picture_url" class="card__profile" />
              <div class="card__info">
                <p class="card__info__name"><strong>{{ chef.name }}</strong></p>
                <p class="card__info__misc">Account created on {{ new Date(chef.created_at).toLocaleString() }}</p>
              </div>
            </header>
            <footer class="card__footer">
              <router-link :to="`/user/${chef.id}`" class="text-blue-500 hover:underline">Check Profile</router-link>
            </footer>
          </div>
        </main>
      </div>

      <!-- Latest Recipes Carousel -->
      <div class="md:w-8/12">
        <div class="bg-white p-5 rounded border border-yellow-400 mt-10">
          <h2 class="text-xl font-semibold mb-4 font-serif">Latest Recipes</h2>
          <div id="latest-recipes-carousel" class="owl-carousel owl-theme">
            <div v-for="recipe in recipes" :key="recipe.id" class="item">
              <div class="recipe-card">
                <img v-if="recipe.image_url" :src="recipe.image_url" alt="Recipe Image" class="w-full h-64 object-cover rounded-t">
                <div class="p-4">
                  <h3 class="text-lg font-semibold">Title: {{ recipe.title }}</h3>
                  <p class="text-gray-600">Category: {{ recipe.category?.name || 'N/A' }}</p>
                  <router-link :to="`/recipes/${recipe.id}`" class="text-blue-500 hover:underline">Show Recipe</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosClient from '../api/axios';

export default {
  data() {
    return {
      recipes: [],
      userConnected: null,
      searchQuery: '',
      suggestedChefs: [],
    };
  },
  mounted() {
    this.fetchRecipes();
  },
  methods: {
    async fetchRecipes() {
      try {
        const response = await axiosClient.get('/recipes');
        this.recipes = response.data.recipes;
        this.userConnected = response.data.user;
        this.suggestedChefs = response.data.users;
        this.$nextTick(this.initializeCarousel);
      } catch (error) {
        console.error('Error fetching recipes:', error);
      }
    },
    async searchUsers() {
      try {
        const response = await axiosClient.get('/dashboard', {
          params: { search: this.searchQuery }
        });
        this.suggestedChefs = response.data.users;
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    clearSearch() {
      this.searchQuery = ''; // Clear the search query
      this.fetchRecipes(); // Fetch initial list of suggested chefs 
    },
    initializeCarousel() {
      if ($('#latest-recipes-carousel').length) {
        $('#latest-recipes-carousel').owlCarousel({
          loop: true,
          margin: 20,
          nav: true,
          dots: false,
          autoplay: true, // Enable autoplay
          autoplayTimeout: 3000, // Autoplay interval in milliseconds (3 seconds)
          responsive: {
            0: { items: 1 },
            600: { items: 2 },
          }
        });
      }
    },
  }
};
</script>

<style src="./../Explore.css"></style>
