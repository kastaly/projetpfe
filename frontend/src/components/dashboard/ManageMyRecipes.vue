<template>
  <div class="w-full sm:px-24 pb-24 overflow-auto" v-if="recipes">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="bg-gray-800 text-gray-100">
          <tr>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">Title Recipe</th>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">Servings</th>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">Category</th>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">likes</th>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">Action</th>
            <th scope="col" class="px-6 py-3 text-xs font-medium uppercase tracking-wider">View Recipe</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="recipe in recipes" :key="recipe.id" class="hover:bg-gray-50 text-center dark:hover:bg-gray-600">
            <td class="px-6 py-4 whitespace-nowrap ">
              <div class="flex items-center text-start">
                <img class="w-12 h-12 sm:w-14 sm:h-14 rounded-md" :src="recipe.image_url" :alt="recipe.title">
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ recipe.title }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">Created at: {{ recipe.created_at }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ recipe.servings }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ recipe.category.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ recipe.likes.length }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex space-x-2">
                <a :href="`/modify-recipe/${recipe.id}`" class="text-blue-600 dark:text-blue-500 hover:underline">Modify</a>
                <button @click="confirmDelete(recipe.id)" class="text-red-600 dark:text-red-500 hover:underline">Delete</button>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <a :href="`/show-recipe/${recipe.id}#recipe-section`" class="text-orange-400 hover:text-orange-600">View Recipe</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <delete-modal v-if="showDeleteModal" @confirm="deleteRecipe" @close="showDeleteModal = false" />
  </div>
</template>

<script>
import axiosClient from '../../api/axios';
import DeleteModal from '../modals/deleteModal.vue';

export default {
  components: {
    DeleteModal,
  },
  data() {
    return {
      recipes: [],
      totalComments: 0,
      usedCategories: [],
      showDeleteModal: false,
      recipeToDelete: null,
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
      } catch (error) {
        console.error('Error fetching data:', error.response ? error.response.data : error.message);
      }
    },
    confirmDelete(recipeId) {
      this.recipeToDelete = recipeId;
      this.showDeleteModal = true;
    },
    async deleteRecipe() {
      try {
        await axiosClient.delete(`/recipes/${this.recipeToDelete}`);
        this.recipes = this.recipes.filter(recipe => recipe.id !== this.recipeToDelete);
        this.showDeleteModal = false;
      } catch (error) {
        console.error('Error deleting recipe:', error.response ? error.response.data : error.message);
        this.showDeleteModal = false;
      }
    }
  }
};
</script>

<style scoped>
/* Additional styles can be added here */
</style>
