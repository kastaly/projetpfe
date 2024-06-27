<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300" v-if="recipe">
    <div class="bg-white shadow-2xl rounded-lg p-8 w-full max-w-5xl my-20">
      <h1 class="text-4xl font-bold mb-10 text-center text-red-600">Edit Your Delicious Recipe</h1>
      <form @submit.prevent="submitRecipe">
        <!-- Step 1: Image Upload, Title, and Category -->
        <div v-if="currentStep === 1">
          <div class="flex flex-col md:flex-row justify-center items-start gap-8">
            <div class="w-full md:w-1/3">
              <label for="image-upload" class="block text-lg font-medium text-gray-700 mb-2">Upload Image for Recipe</label>
              <div class="relative">
                <img v-if="imageUrl" :src="imageUrl" id="uploaded-image" class="w-full h-52 mb-4 rounded-lg shadow-md" alt="Uploaded Image">
                <img v-else src="/images/upload.png" id="uploaded-image" class="w-full h-52 mb-4 rounded-lg shadow-md" alt="Placeholder Image">
                <input type="file" id="image-upload" @change="previewImage" accept="image/*" class="hidden">
                <label for="image-upload" class="absolute bottom-0 left-0 w-full px-4 py-2 bg-gray-200 text-center cursor-pointer rounded-b-lg">Select Image</label>
              </div>
            </div>
            <div class="w-full md:w-2/3">
              <h3 class="text-2xl font-semibold text-gray-800 mb-4">Recipe Information</h3>
              <div class="mb-6">
                <label for="title" class="block text-lg font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" v-model="title" :placeholder="recipe.title" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
              </div>
              <div class="mb-6">
                <label for="category" class="block text-lg font-medium text-gray-700 mb-2">Category</label>
                <select id="category" v-model="category" required class="form-select w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                  <option value="" selected disabled>Select a category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>                
              </div>
            </div>
          </div>
          <div class="flex justify-end mt-8">
            <button type="button" @click="nextStep" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">Next</button>
          </div>
        </div>
        <!-- Step 2: Ingredients and Steps -->
        <div v-if="currentStep === 2" class="bg-white shadow-md rounded-lg p-8">
          <div class="mb-6">
            <label for="ingredients" class="block text-lg font-medium text-gray-700 mb-2">Ingredients <small>(please type each ingredient on a separate line)</small></label>
            <textarea id="ingredients" v-model="ingredients" :placeholder="recipe.ingredients" rows="6" required class="form-textarea w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
          </div>
          <div class="mb-6">
            <label for="steps" class="block text-lg font-medium text-gray-700 mb-2">Steps <small>(please write down each step on a separate line)</small></label>
            <textarea id="steps" v-model="steps" :placeholder="recipe.steps" rows="4" required class="form-textarea w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
              <label for="total_time" class="block text-lg font-medium text-gray-700 mb-2">Total Time (minutes)</label>
              <input type="number" id="total_time" v-model="totalTime" :placeholder="recipe.total_time" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
              <label for="servings" class="block text-lg font-medium text-gray-700 mb-2">Servings</label>
              <input type="number" id="servings" v-model="servings" :placeholder="recipe.servings" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
          </div>
          <div class="flex justify-between">
            <button type="button" @click="previousStep" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Previous</button>
            <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">Submit Recipe</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axiosClient from '../../api/axios';

export default {
  data() {
    return {
      recipe: null,
      currentStep: 1,
      title: '',
      category: '',
      ingredients: '',
      servings: 1,
      steps: '',
      totalTime: '',
      image: null,
      imageUrl: '',
      categories: [],
      recipeId: null
    };
  },
  mounted() {
    const recipeId = this.$route.params.recipeId;
    this.recipeId = recipeId; // Ensure recipeId is correctly set
    this.fetchCategories();
    this.fetchRecipe(recipeId);
  },
  methods: {
    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.image = file;
        this.imageUrl = URL.createObjectURL(file);
      }
    },
    previousStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
      }
    },
    nextStep() {
      if (this.currentStep < 2) {
        this.currentStep++;
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
    async fetchRecipe(recipeId) {
      try {
        const response = await axiosClient.get(`/recipes/${recipeId}`);
        this.recipe = response.data.recipe;

        // Populate the form fields with the fetched recipe data
        this.title = this.recipe.title;
        this.category = this.recipe.category_id;
        this.ingredients = this.recipe.ingredients;
        this.servings = this.recipe.servings;
        this.steps = this.recipe.steps;
        this.totalTime = this.recipe.total_time;
        this.imageUrl = this.recipe.image_url;
      } catch (error) {
        console.error('Error loading recipe data:', error);
      }
    },
  async submitRecipe() {
    try {
      const formData = new FormData();
      formData.append('title', this.title);
      formData.append('category_id', this.category);
      formData.append('ingredients', this.ingredients);
      formData.append('servings', this.servings);
      formData.append('steps', this.steps);
      formData.append('total_time', this.totalTime);
      if (this.image) {
        formData.append('image', this.image);
      }

      const response = await axiosClient.put(`/recipes/${this.recipeId}/update`, formData);
      console.log('Recipe updated:', response.data);
      this.$router.push('/recipes');


      // Optionally re-fetch the updated recipe to update the UI
      this.fetchRecipe(this.recipeId);
    } catch (error) {
      console.error('Error updating recipe:', error.response || error);
    }
  }
}

};
</script>

<style scoped>
/* Your styles here */
</style>
