<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-900">
    <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-lg">
      <!-- Logo and Branding -->
      <div class="flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-orange-500" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M364.61 390.213C304.625 450.196 207.37 450.196 147.386 390.213C117.394 360.22 102.398 320.911 102.398 281.6C102.398 242.291 117.394 202.981 147.386 172.989C147.386 230.4 153.6 281.6 230.4 307.2C230.4 256 256 102.4 294.4 76.7999C320 128 334.618 142.997 364.608 172.989C394.601 202.981 409.597 242.291 409.597 281.6C409.597 320.911 394.601 360.22 364.61 390.213Z" fill="#F97316" stroke="#F97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M201.694 387.105C231.686 417.098 280.312 417.098 310.305 387.105C325.301 372.109 332.8 352.456 332.8 332.8C332.8 313.144 325.301 293.491 310.305 278.495C295.309 263.498 288 256 275.2 230.4C256 243.2 243.201 320 243.201 345.6C201.694 345.6 179.2 332.8 179.2 332.8C179.2 352.456 186.698 372.109 201.694 387.105Z" fill="white"/>
        </svg>
        <span class="ml-2 text-3xl font-bold text-gray-100">FoodieHub</span>
      </div>

      <!-- Welcome Text -->
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Welcome Back!</h1>
        <p class="text-gray-400">Please login to continue</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ errorMessage }}
      </div>

      <!-- Login Form -->
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-gray-300 font-bold mb-2">Email</label>
          <input type="email" id="email" v-model="email" class="text-gray-200 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border border-gray-600 placeholder-gray-400" placeholder="you@example.com" required>
        </div>
        <div class="mb-4">
          <label for="password" class="block text-gray-300 font-bold mb-2">Password</label>
          <input type="password" id="password" v-model="password" class="text-gray-200 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border border-gray-600 placeholder-gray-400" placeholder="********" required>
        </div>
        <div class="flex items-center justify-between mt-4">
          <label class="inline-flex items-center">
            <input type="checkbox" class="text-orange-500 border-gray-600 rounded-md focus:border-orange-500 focus:ring focus:ring-opacity-40 focus:ring-orange-500" />
            <span class="mx-2 text-sm text-gray-400">Remember me</span>
          </label>
          <a href="#" class="text-sm text-orange-500 hover:underline">Forgot your password?</a>
        </div>
        <button type="submit" class="w-full mt-6 px-4 py-2 text-sm text-center text-white bg-orange-600 rounded-lg focus:outline-none hover:bg-orange-500">
          Sign in
        </button>
      </form>

      <!-- Sign Up Link -->
      <p class="mt-6 text-sm text-center text-gray-400">
        Don't have an account? <a href="#" class="text-orange-500 hover:underline">
         <router-link to="/register">Sign up</router-link> 
        </a>
      </p>
    </div>
  </div>
  <!-- Template code remains the same -->
</template>

<script>
import axiosClient from '../api/axios'; 

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axiosClient.post('/login', { 
          email: this.email,
          password: this.password
        });

        const user = response.data.user;

        localStorage.setItem('authToken', response.data.token);
        localStorage.setItem('user', JSON.stringify(user));

        if (user && user.role === 'admin') {
          this.errorMessage = "You don't have the right to access here";
          localStorage.removeItem('authToken');
          localStorage.removeItem('user');
        } else {
          this.$router.push('/user-dashboard');
        }

      } catch (error) {
        if (error.response && error.response.status === 401 || error.response && error.response.status === 422) {
          this.errorMessage = "Incorrect email or password";
        } else if (error.response && error.response.status === 404) {
          this.errorMessage = "Email doesn't exist";
        } else {
          this.errorMessage = "An unexpected error occurred";
        }
      }
    }
  }
}
</script>
