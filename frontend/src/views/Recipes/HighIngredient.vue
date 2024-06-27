<template>
  <div>
      <h2 class="text-2xl font-bold mb-6 font-sans">Create Highlighted Ingredient</h2>
      <form @submit.prevent="addHighlightedIngredient">
          <div class="mb-4">
              <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
              <input type="text" v-model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingredient Name">
          </div>

          <div class="mb-4">
              <label for="type" class="block text-gray-700 font-bold mb-2">Type</label>
              <input type="text" v-model="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ingredient Type">
          </div>

          <div class="mb-4">
              <label for="image" class="block text-gray-700 font-bold mb-2">Image</label>
              <input type="file" @change="onImageChange" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          </div>

          <div class="flex items-center justify-between">
              <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Ingredient</button>
          </div>
      </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
      return {
          name: '',
          type: '', // Add type field
          image: null,
      };
  },
  methods: {
      async addHighlightedIngredient() {
          try {
              // Get CSRF cookie
              await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
                  withCredentials: true,
              });

              // Prepare form data
              const formData = new FormData();
              formData.append('name', this.name);
              formData.append('type', this.type); // Include type field
              formData.append('image', this.image);

              // Retrieve authentication token from local storage
              const token = localStorage.getItem('authToken');

              // Make request to add highlighted ingredient
              const response = await axios.post('http://localhost:8000/api/highlighted-ingredients', formData, {
                  headers: {
                      'Content-Type': 'multipart/form-data',
                      'Authorization': `Bearer ${token}` // Include the authentication token in the request headers
                  },
              });

              // Reset form fields after successful addition
              this.name = '';
              this.image = null;

              console.log('Highlighted ingredient added:', response.data);

              // Optionally, you can add a success message or redirect to another page
          } catch (error) {
              console.error('Error adding highlighted ingredient:', error);
          }
      },
      // Method to handle image change
      onImageChange(event) {
          this.image = event.target.files[0];
      },
  },
};
</script>
