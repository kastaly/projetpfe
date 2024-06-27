<template>
  <main class="bg-gray-100 text-gray-800 py-8" v-if="user">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 items-center">
      <!-- Profile Header -->
      <div class="flex flex-col items-center justify-center md:flex-row md:items-center md:justify-center md:space-x-14 mb-12">
        <!-- Profile Image -->
        <div class="w-32 h-32 md:w-48 md:h-48 relative mb-6 md:mb-0">
          <img
            class="w-full h-full object-cover rounded-full border-4 border-yellow-400"
            v-if="user.profile_picture_url"
            :src="user.profile_picture_url"
            alt="Profile Picture"
          >
        </div>
        <!-- Profile Info -->
        <div class="text-center md:text-left">
          <h2 class="text-3xl font-semibold text-gray-900">{{ user.name }}</h2>
          <div class="flex justify-center md:justify-start space-x-6 mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-600">{{ recipeCount }} Recipes</p>
            <p class="text-sm text-gray-600 dark:text-gray-600">{{ totalComments }} Comments</p>
            <p class="text-sm text-gray-600 dark:text-gray-600">{{ totalLikes }} Likes</p>
          </div>
          <!-- Edit Profile Button -->
          <div v-if="isCurrentUser" class="mt-6">
            <router-link to="/user-dashboard" class="inline-block bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out">
              Check Dashboard
            </router-link>
          </div>
        </div>
      </div>

      <!-- Recipes Section -->
      <section>
        <!-- Section Title -->
        <div class="text-center mb-12">
          <h3 class="text-2xl font-semibold mb-2">Recipes</h3>
          <div class="border-b-2 border-yellow-400 w-24 mx-auto"></div>
        </div>
        <!-- Recipes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
          <!-- Single Recipe Card -->
          <router-link v-for="recipe in recipes" :key="recipe.id" :to="recipeRoute(recipe.id)" class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-1">
            <img class="w-full h-48 object-cover rounded-t-lg" :src="recipe.image_url" :alt="recipe.title">
            <div class="p-6">
              <h4 class="text-lg font-semibold leading-tight mb-2 group-hover:text-yellow-500 dark:text-gray-200">{{ recipe.title }}</h4>
              <p class="text-sm text-gray-600 mb-4 dark:text-gray-400">Category: {{ recipe.category.name }}</p>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 2a8 8 0 1 1-8 8 8 0 0 1 8-8zm0 14a6 6 0 1 0 0-12 6 6 0 0 0 0 12zm0-11a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm3.707 4.707a1 1 0 0 0-1.414-1.414L9 9.586V5a1 1 0 1 0-2 0v5.586a1 1 0 0 0 1.707.707L10 9.414l2.293 2.293a1 1 0 0 0 1.414-1.414z"/>
                </svg>
                <span>{{ recipe.total_time }} min</span>
              </div>
            </div>
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <button class="bg-yellow-500 text-white px-6 py-2 rounded-full hover:bg-yellow-600 transition duration-300 ease-in-out">Show Recipe</button>
            </div>
          </router-link>
          <p v-if="recipes.length === 0" class="text-center text-gray-500 col-span-full">No recipes found.</p>
        </div>
      </section>
    </div>
  </main>
</template>

<script>
import axiosClient from '../../api/axios';

export default {
  data() {
    return {
      user: null,
      recipes: [],
      totalComments: 0,
      totalLikes: 0,
    };
  },
  computed: {
    recipeCount() {
      return this.recipes.length;
    },
    isCurrentUser() {
      // Check if the logged-in user's ID matches the current profile's user ID
      return this.$store.state.user.id === this.user.id; // Replace with your actual logic to compare IDs
    },
  },
  mounted() {
    const userId = this.$route.params.userId;
    this.fetchUser(userId);
  },
  methods: {
    async fetchUser(userId) {
      try {
        const response = await axiosClient.get(`/user/${userId}`);
        this.user = response.data.user;
        this.recipes = response.data.recipes;
        this.totalComments = response.data.totalComments;
        this.totalLikes = response.data.totalLikes;
      } catch (error) {
        console.error('Error fetching user profile:', error.response ? error.response.data : error.message);
      }
    },
    recipeRoute(recipeId) {
      return `/show-recipe/${recipeId}`;
    }
  },
};
</script>

<style scoped>
/* Profile Image */
.profile-image {
  width: 8rem;
  height: 8rem;
  border-radius: 50%;
  border: 4px solid #FBBF24;
}

/* Profile Info */
.profile-info {
  margin-left: 1.5rem;
  text-align: center;
}

/* Recipe Card */
.recipe-card {
  background-color: #FFF;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}
.recipe-card:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  transform: translateY(-4px);
}
.recipe-card img {
  height: 12rem;
  object-fit: cover;
}
.recipe-card h4 {
  color: #2D3748;
}
.recipe-card h4:hover {
  color: #FBBF24;
}
.recipe-card p {
  color: #718096;
}
.recipe-card .recipe-time {
  color: #4A5568;
}
.recipe-card .recipe-button {
  background-color: #FBBF24;
  border-radius: 9999px;
  color: #FFF;
  padding: 0.5rem 1rem;
  transition: background-color 0.3s ease-in-out;
}
.recipe-card .recipe-button:hover {
  background-color: #F59E0B;
}
</style>
