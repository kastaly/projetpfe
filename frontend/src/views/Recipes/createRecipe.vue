<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-700 via-gray-700 to-gray-800">
    <div class="bg-white shadow-2xl rounded-lg p-8 w-full max-w-5xl my-20">
      <h1 class="text-4xl font-bold mb-10 text-center text-red-600">Create Your Delicious Recipe</h1>
      <form @submit.prevent="submitRecipe">
        <!-- Step 1: Image Upload, Title, and Category -->
        <div v-if="currentStep === 1">
          <div class="flex flex-col md:flex-row justify-center items-start gap-8">
            <div class="w-full md:w-1/3">
              <label for="image-upload" class="block text-lg font-medium text-gray-700 mb-2">Upload Image for Recipe</label>
              <div class="relative">
                <img v-if="imageUrl" :src="imageUrl" id="uploaded-image" class="w-full h-56 mb-4 rounded-lg shadow-md" alt="Uploaded Image">
                <img v-else src="/images/upload.png" id="uploaded-image" class="w-full h-56 mb-4 rounded-lg shadow-md" alt="Placeholder Image">
                <input type="file" id="image-upload" @change="previewImage" accept="image/*" class="hidden">
                <label for="image-upload" class="absolute bottom-0 left-0 w-full px-4 py-2 bg-gray-200 text-center cursor-pointer rounded-b-lg">Select Image</label>
              </div>

              <div>
                <input type="file" @change="handleFileUpload" accept="video/*" />
                <button type="submit">Submit Recipe</button>
              </div>

            </div>
            <div class="w-full md:w-2/3">
              <h3 class="text-2xl font-semibold text-gray-800 mb-4">Recipe Information</h3>
              <div class="mb-6">
                <label for="title" class="block text-lg font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" v-model="title" placeholder="Enter the title of your recipe" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
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
        <!-- Step 2: Ingredients Selection -->
        <div v-if="currentStep === 2" class="flex flex-col md:flex-row justify-center items-start gap-8">
          <!-- Highlighted Ingredients Section -->
          <div class="w-full md:w-1/2 bg-gray-800 rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-gray-100 mb-4">Select Highlight Ingredients</h3>
            <div v-for="(group, groupName) in ingredientGroups" :key="groupName" class="mb-6">
              <h4 class="text-xl font-semibold text-gray-200 mb-4">{{ groupName }}</h4>
              <div class="grid grid-cols-4 gap-4">
                <div v-for="ingredient in group" :key="ingredient.id" class="relative">
                  <div @click="toggleIngredient(ingredient)" class="flex flex-col items-center cursor-pointer w-20 sm:w-24 h-20 sm:h-24 rounded-full shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <img :src="ingredient.image" class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-slate-50" :alt="ingredient.name" :class="{'opacity-80': !ingredient.selected}">
                    <div v-if="ingredient.selected" class="absolute top-0 right-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">✓</div>
                  </div>
                  <p class="text-sm text-center text-gray-200" style="margin-top:-17px">{{ ingredient.name }}</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Ingredients Input Section -->
          <div class="w-full md:w-1/2 bg-white rounded-lg p-6 shadow-md">
            <label for="ingredients" class="block text-lg font-medium text-gray-700 mb-2">Ingredients <small>(please type each ingredient on a separate line)</small></label>
            <textarea id="ingredients" v-model="ingredients" rows="6" placeholder="Enter the ingredients required" required class="form-textarea w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            <div class="flex justify-between mt-6">
              <button type="button" @click="previousStep" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Previous</button>
              <button type="button" @click="nextStep" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">Next</button>
            </div>
          </div>
        </div>
        <!-- Step 3: Rest of the Form Fields -->
        <div v-if="currentStep === 3">
          <div class="bg-white shadow-md rounded-lg p-8">
            <div class="mb-6">
              <label for="steps" class="block text-lg font-medium text-gray-700 mb-2">Steps <small>(please write down each step in separate line)</small></label>
              <textarea id="steps" v-model="steps" rows="4" placeholder="Enter the steps to prepare the recipe" required class="form-textarea w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div>
                <label for="total_time" class="block text-lg font-medium text-gray-700 mb-2">Total Time (minutes)</label>
                <input type="number" id="total_time" v-model="totalTime" placeholder="Enter total preparation time" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
              </div>
              <div>
                <label for="servings" class="block text-lg font-medium text-gray-700 mb-2">Servings</label>
                <input type="number" id="servings" v-model="servings" placeholder="Enter the number of servings" required class="form-input w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
              </div>
            </div>
            <div class="flex justify-between">
              <button type="button" @click="previousStep" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Previous</button>
              <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">Submit Recipe</button>
            </div>
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
      currentStep: 1,
      title: '',
      category: '',
      ingredients: '',
      servings: 1,
      steps: '',
      totalTime: '',
      image: null,
      imageUrl: '',
      videoFile: null,
      categories: [],
      ingredientGroups: {
        "Meat": [],
        "Vegetables": [],
        "Oils": []
      },
      highlightedIngredientIds: [] // Initialize highlightedIngredientIds as an empty array
    };
  },
  mounted() {
    this.fetchCategories();
    this.fetchHighlightedIngredients();
  },
  methods: {
    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.image = file;
        this.imageUrl = URL.createObjectURL(file);
      }
    },
    handleFileUpload(event) {
      this.videoFile = event.target.files[0];
    },
    previousStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
      }
    },
    nextStep() {
      if (this.currentStep < 3) {
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
    async fetchHighlightedIngredients() {
      try {
        const response = await axiosClient.get('/highlighted-ingredients');
        const ingredients = response.data;

        // Assuming each ingredient has id, type, name, and image properties
        this.ingredientGroups = ingredients.reduce((groups, ingredient) => {
          const { id, type, name, image } = ingredient;
          if (!groups[type]) {
            groups[type] = [];
          }
          groups[type].push({ id, name, image, selected: false });
          return groups;
        }, {});
      } catch (error) {
        console.error('Error fetching highlighted ingredients:', error);
      }
    },
    toggleIngredient(ingredient) {
      ingredient.selected = !ingredient.selected;
      const ingredientId = ingredient.id;

      if (ingredient.selected) {
        // Add ingredient ID to the array if not already present
        if (!this.highlightedIngredientIds.includes(ingredientId)) {
          this.highlightedIngredientIds.push(ingredientId);
        }
      } else {
        // Remove ingredient ID from the array if present
        const index = this.highlightedIngredientIds.indexOf(ingredientId);
        if (index !== -1) {
          this.highlightedIngredientIds.splice(index, 1);
        }
      }
    },
    async submitRecipe() {
      try {
        // Create form data and append recipe details
        const formData = new FormData();
        formData.append('title', this.title);
        formData.append('category_id', this.category);
        formData.append('ingredients', this.ingredients);
        formData.append('servings', this.servings);
        formData.append('steps', this.steps);
        formData.append('total_time', this.totalTime);
        formData.append('image', this.image);
        if (this.videoFile) {
          formData.append('video', this.videoFile);
        }
        // Append each highlighted ingredient ID to the form data
        this.highlightedIngredientIds.forEach(id => {
          formData.append('highlighted_ingredient_ids[]', id);
        });

        // Submit recipe to the backend
        await axiosClient.post('/recipes/store', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        // Redirect to recipes page after successful submission
        this.$router.push('/recipes');
      } catch (error) {
        console.error('Error submitting recipe:', error);
      }
    }
  }
};
</script>
