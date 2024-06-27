<template>

  <div class="w-3/4 mx-auto my-12">
    <form @submit.prevent="searchRecipes" class="flex items-center  my-8">
      <label for="voice-search" class="sr-only">Search</label>
      <div class="relative w-full">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 21 21">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.15 5.6h.01m3.337 1.913h.01m-6.979 0h.01M5.541 11h.01M15 15h2.706a1.957 1.957 0 0 0 1.883-1.325A9 9 0 1 0 2.043 11.89 9.1 9.1 0 0 0 7.2 19.1a8.62 8.62 0 0 0 3.769.9A2.013 2.013 0 0 0 13 18v-.857A2.034 2.034 0 0 1 15 15Z"/>
          </svg>
        </div>
        <input v-model="searchQuery" type="text" id="voice-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Recipes..." required />
        <button type="submit" class="absolute inset-y-0 end-0 flex items-center pe-3">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7v3a5.006 5.006 0 0 1-5 5H6a5.006 5.006 0 0 1-5-5V7m7 9v3m-3 0h6M7 1h2a3 3 0 0 1 3 3v5a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V4a3 3 0 0 1 3-3Z"/>
          </svg>
        </button>
      </div>
      <button type="submit" class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-yellow-500 rounded-lg border border-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>Search
      </button>
      <button @click="clearSearch" class=" items-center  px-10 ms-2 text-sm font-medium text-white bg-yellow-500 rounded-lg border border-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
        Clear Search
      </button>
    </form>

    <!-- Filter by Categories -->
    <div class="flex mb-4 md:space-x-3 space-x-3">
      <div>
        <label for="category-filter" class="text-lg font-semibold text-gray-700 dark:text-gray-700">Filter by Category:</label>
        <select
          v-model="selectedCategory"
          id="category-filter"
          class="block w-full mt-1 py-2.5 px-16 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
      </div>
      <!-- Sort by -->
      <div>
        <label for="sort-by" class="text-lg font-semibold text-gray-700 dark:text-gray-700">Sort by:</label>
        <select
          v-model="sortBy"
          id="sort-by"
          class="block w-full mt-1 py-2.5 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
          <option value="latest">Latest Recipes</option>
          <option value="likes">Most Liked</option>
          <!-- Add more options based on your sorting criteria -->
        </select>
      </div>
    </div>
    <!-- Loader -->
    <div v-if="loading" class="loader-wrapper flex justify-center items-center h-screen">
      <div role="status">
        <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
          <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
        </svg>
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <!-- Content -->
    <div v-else>
      <!-- Latest Recipes Carousel -->
      <div class="container mx-auto md:flex h-full ">
        <div class="left md:w-8/12 pr-4">
          <div class="bg-white p-5 rounded border border-yellow-400 mb-6" >
            <h2 class="text-xl font-semibold mb-4 font-serif">Latest Recipes</h2>
            <div id="latest-recipes-carousel" class="owl-carousel owl-theme" v-if="recipes && recipes.length > 0">
              <div v-for="recipe in recipes" :key="recipe.id" class="item">
                <div class="recipe-card">
                  <img v-if="recipe.image_url" :src="recipe.image_url" alt="Recipe Image" class="w-full h-64 object-cover rounded-t">
                  <div class="p-4">
                    <h3 class="text-lg font-semibold">Title: {{ recipe.title }}</h3>
                    <p class="text-gray-600">Category: {{ recipe.category?.name || 'N/A' }}</p>
                    <a :href="`/recipes/${recipe.id}`" class="text-blue-500 dark:text-blue-400 hover:underline">Show Recipe</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <!-- Sidebar -->
      <div class="w-full md:w-4/12 px-4">
        <!-- User Profile -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
          <div v-if="userConnected" class="flex items-center mb-4">
            <!-- Display user profile picture and name -->
            <img :src="userConnected.profile_picture_url" alt="User Profile Picture" class="object-fit w-14 h-14 rounded-full mr-4 border-2 border-orange-600">
            <p class="text-lg font-semibold">{{ userConnected.name }}</p>
          </div>
          <div v-else>
            <!-- Prompt to login -->
            <p class="text-lg font-semibold">Please <router-link to="/login" class="text-blue-500 hover:underline">login</router-link> to display your profile.</p>
          </div>
        </div>

      <!-- Suggested Chefs -->
      <div class="bg-gray-700 p-6 rounded-lg shadow-md">
        <h2 class="text-gray-100 text-xl font-bold mb-4 ">Suggested Chefs</h2>
        <div class="max-h-64 overflow-y-auto" v-if="suggestedChefs">
          <div v-for="chef in suggestedChefs" :key="chef.id" class="flex items-center mb-4">
            <img :src="chef.profile_picture_url" alt="Chef Profile Picture" class="w-12 h-12 rounded-full mr-4 object-fit">
            <div class="flex-grow">
              <p class="text-lg font-semibold text-gray-200">{{ chef.name }}</p>
              <a :href="`/user/${chef.id}`" class="text-blue-300 hover:underline">Check Profile</a>
            </div>
          </div>
        </div>
      </div>
    </div></div>


      <!-- Recipes Feed -->
      <div class="feed-wrapper mb-4 md:w-8/12 ">
        <div v-if="recipes && recipes.length > 0">
          <!-- Iterate over recipes -->
          <div v-for="recipe in recipes" :key="recipe.id" class="mb-8">
            <!-- User Info -->
            <div class="bg-white  shadow-md rounded-lg p-6 mb-4 my-1">
              <div class="md:flex justify-between ">
              <div class="flex items-center mb-4 ">
                <router-link :to="`/user/${recipe.user.id}`" class="flex items-center">
                  <div class="user-img h-12 w-12 border rounded-full overflow-hidden mr-4">
                    <img draggable="false" v-if="recipe.user.profile_picture_url" :src="recipe.user.profile_picture_url" alt="User Image" class="h-full w-full object-fit">
                  </div>
                  <div class="user-name-and-place flex flex-col">
                    <span class="text-sm font-bold text-gray-700">{{ recipe.user.name }}</span>
                    <span class="text-xs font-light text-gray-500">Category: {{ recipe.category?.name || 'N/A' }}</span>
                  </div>
                </router-link>
              </div>
              <div class=" items-center text-blue-500 py-2 px-4 my-2 rounded-sm md:text-white md:bg-gray-600 hover:bg-yellow-600">
                <a :href="`/show-recipe/${recipe.id}#recipe-section`">Show Recipe</a>
              </div>
              </div>
              <!-- Recipe Details -->

                <div class="relative">
                  <img v-if="recipe.image_url" :src="recipe.image_url" alt="Recipe Image" class="w-full h-80 object-cover rounded-lg">
                  <div class="absolute bottom-0 left-0 p-2 bg-gray-800 text-white rounded-tr-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ recipe.total_time }} min
                  </div>
                </div>

                <!-- comment && likes .. -->
                <div class="flex pt-4 space-x-3">
                 
                 <div class="comment space-x-3"> 
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 inline-block">
                    <path d="M19 2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12l4 4V6a4 4 0 0 0-4-4z"/>
                  </svg>  
                  <small v-if="recipe.comments.length ==0">0 comments</small>
                  <small v-else>
                    {{ recipe.comments.length }} comments
                  </small>
                </div>
                
                <div class="flex">
                  <like-component 
      :recipe-id="recipe.id"
      :liked="recipe.liked"
      @like-status-changed="handleLikeStatusChanged"
    ></like-component>
                

                 <small class="items-center pt-1" v-if="recipe.likes.length == 0">0 likes</small>
                  <small class="items-start pt-1 flex" v-else>
                    {{ recipe.likes.length }} likes
                  </small>
                </div>
              </div>
              <div class="flex space-x-3">
                <p>liked by</p>
                <div class="flex space-x-1">
                  <template v-for="(like, index) in recipe.likes.slice(0, 3)" :key="like.user.id">
                    <img :src="like.user.profile_picture_url" class="rounded-full w-8 h-8 object-fit" alt="">
                  </template>
                  <template v-if="recipe.likes.length > 2">
                    <span class="rounded-full w-8 h-8 bg-gray-100 flex items-center justify-center text-gray-400">
                      +{{ recipe.likes.length - 3 }}
                    </span>
                  </template>
                </div>
              </div>
              
                
                <!-- steps -->
                <div class="p-4"><a :href="`/show-recipe/${recipe.id}#recipe-section`">
                  <p class="text-gray-700">{{ recipe.steps.substring(0, 150) }}<span v-if="recipe.steps.length > 200">... <span class="text-blue-400">See more</span></span></p>
                </a></div>

            </div>
          </div>
        </div>
        
        <div v-else-if="!loading">
          <div class="text-center text-gray-500">No recipes found matching your search criteria.</div>
        </div>
        <div v-else>
          <div class="text-center text-gray-500">Loading...</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosClient from '../../api/axios';
import likeComponent from '../../components/recipecomponents/partials/likeComponent.vue';
import { mapState } from 'vuex';

export { likeComponent }
export default {
  components: {
    likeComponent,
  },
  data() {
    return {
      recipes: [],
      loading: true,
      searchQuery: '',
      selectedCategory: '', // for category filtering
      sortBy: 'latest', // for sorting
      suggestedChefs: [],
      userConnected: null, // Placeholder for logged-in user data
      categories: [], // list of categories
    };
  },
  mounted() {
    this.fetchRecipes();
    this.fetchCategories();
    this.fetchLoggedInUser(); // Fetch logged-in user data
  },
  computed: {
    filteredRecipes() {
      let filtered = this.recipes;

      if (this.selectedCategory) {
        filtered = filtered.filter(
          (recipe) => recipe.categoryId === this.selectedCategory
        );
      }

      if (this.searchQuery) {
        const searchRegex = new RegExp(this.searchQuery, 'i');
        filtered = filtered.filter(
          (recipe) => searchRegex.test(recipe.title) || searchRegex.test(recipe.description)
        );
      }

      if (this.sortBy === 'latest') {
        filtered = filtered.sort(
          (a, b) => new Date(b.createdAt) - new Date(a.createdAt)
        );
      } else if (this.sortBy === 'likes') {
        filtered = filtered.sort((a, b) => b.likes - a.likes);
      }

      return filtered;
    },
  },
  methods: {
    async fetchRecipes() {
      this.loading = true;
      try {
        const response = await axiosClient.get('/recipes', {
          params: {
            search: this.searchQuery,
            category: this.selectedCategory,
            sort: this.sortBy
          }
        });
        this.recipes = response.data.recipes;

        // Fetch logged-in user data if not fetched already
        if (!this.userConnected) {
          await this.fetchLoggedInUser();
        }

        // Initialize liked status for each recipe based on current user's likes
        this.recipes.forEach(recipe => {
          recipe.liked = this.userConnected ? recipe.likes.some(like => like.user.id === this.userConnected.id) : false;
        });

        this.suggestedChefs = response.data.users;
      } catch (error) {
        console.error('Error fetching recipes:', error);
      } finally {
        this.loading = false;
        this.$nextTick(this.initializeCarousel);
      }
    },
    async fetchCategories() {
      try {
        const response = await axiosClient.get('/categories');
        this.categories = response.data.categories;
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    async fetchLoggedInUser() {
      try {
        // Fetch logged-in user data from your API or localStorage
        const response = await axiosClient.get('/dashboard'); // Replace with your API endpoint
        this.userConnected = response.data.user;
      } catch (error) {
        console.error('Error fetching logged-in user:', error);
        // Handle error fetching user data
      }
    },
    handleLikeStatusChanged(recipeId, newLikedStatus) {
      // Update the local recipes array with the new liked status
      const index = this.recipes.findIndex(recipe => recipe.id === recipeId);
      if (index !== -1) {
        this.recipes[index].liked = newLikedStatus;

        // Update like count
        if (newLikedStatus) {
          this.recipes[index].like_count++;
        } else {
          this.recipes[index].like_count--;
        }

        // Add or remove user who liked the recipe
        if (newLikedStatus) {
          // Assuming this.userConnected is defined and contains necessary user data
          this.recipes[index].likes.push({
            user: {
              id: this.userConnected.id,
              profile_picture_url: this.userConnected.profile_picture_url
            }
          });
        } else {
          // Remove user from likes array if unliked (optional, depending on your logic)
          const userIndex = this.recipes[index].likes.findIndex(like => like.user.id === this.userConnected.id);
          if (userIndex !== -1) {
            this.recipes[index].likes.splice(userIndex, 1);
          }
        }
      }
    },
    clearSearch() {
      this.searchQuery = '';
      this.fetchRecipes();
    },
    async searchRecipes() {
      this.loading = true;
      try {
        const response = await axiosClient.get('/recipes', {
          params: {
            search: this.searchQuery,
            category: this.selectedCategory,
            sort: this.sortBy
          }
        });
        this.recipes = response.data.recipes;
      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        this.loading = false;
      }
    },
    initializeCarousel() {
      if ($('#latest-recipes-carousel').length) {
        $('#latest-recipes-carousel').owlCarousel({
          loop: true,
          margin: 20,
          nav: true,
          dots: false,
          responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 3 }
          }
        });
      }
    },
  },
// Adjusted watch section to log changes and ensure they trigger fetchRecipes
watch: {
  selectedCategory(newValue, oldValue) {
    console.log('selectedCategory changed:', newValue);
    this.fetchRecipes();
  },
  sortBy(newValue, oldValue) {
    console.log('sortBy changed:', newValue);
    this.fetchRecipes();
  }
}


};

</script>



<style scoped>
/* Loader styles */
.loader-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.8);
  z-index: 9999;
}
</style>
