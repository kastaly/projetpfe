<template>
  <body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl mx-auto">
      <!-- Left side with image and description -->
      <div class="md:w-1/2 bg-cover bg-center p-8" style="background-image: url('images/register.png');">
        <div class="bg-black bg-opacity-50 p-12 m-5 rounded-lg">
          <h1 class="text-white text-4xl font-bold mb-4">Enhance your food experience with Foodiehub</h1>
          <p class="text-white text-lg">Discover an extensive array of gourmet recipes, cooking tips, and meal planning tools to streamline your culinary journey.</p>
        </div>
      </div>
      <!-- Right side with form -->
      <div class="md:w-1/2 p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-bold mb-6 font-sans">Create an account</h2>
        <form @submit.prevent="register()">
          <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" v-model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="What should we call you?">
          </div>
          <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" v-model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="you@example.com">
          </div>
          <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
            <input type="password" v-model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="********">
          </div>
          <div class="mb-6">
            <label for="confirm-password" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
            <input type="password" v-model="password_confirmation" name="confirm-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="********">
          </div>
          <!-- Profile Picture Input -->
          <div class="mb-4">
            <label for="profile-picture" class="block text-gray-700 font-bold mb-2">Profile Picture</label>
            <input type="file" @change="onProfilePictureChange" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          </div>
          <div class="flex items-center justify-between">
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              Create Account
            </button>
          </div>
        </form>
        <p class="mt-4 text-sm">Already a member?             
          <router-link to="/login" class="btn btn-primary">
            <a class="text-blue-500 hover:text-blue-700">Log in</a>
          </router-link>
        </p>
      </div>
    </div>
  </body>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      profile_picture: '', 
      response: null,
    };
  },
  methods: {
    async register() {
      try {
        // Get CSRF cookie
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
          withCredentials: true,
        });

        // Prepare form data
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('email', this.email);
        formData.append('password', this.password);
        formData.append('password_confirmation', this.password_confirmation);
        formData.append('profile_picture', this.profile_picture); // Append profile picture

        // Make registration request with form data
        this.response = await axios.post('http://localhost:8000/api/register', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        // Debugging
        console.log('Registration response:', this.response.data);

        // Store token and user info
        localStorage.setItem('authToken', this.response.data.token);
        
        // Redirect to login page
        this.$router.push('/login');
      } catch (error) {
        console.error('Registration failed:', error.response ? error.response.data : error.message);

        if (error.response && error.response.status === 201) {
          const user = JSON.parse(localStorage.getItem('user'));
          const redirectPath = '/login';
          this.$router.push(redirectPath);
        }
      }
    },
    // Method to handle profile picture change
    onProfilePictureChange(event) {
      this.profile_picture = event.target.files[0];
    },
  },
};
</script>
