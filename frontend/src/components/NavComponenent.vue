<template>
  <div id="header" 
  class="bg-gray-800 text-white py-4 px-8 flex items-center justify-between  mt-6 w-3/4 mx-auto">
    <div id="title">
      <router-link to="/" ><img src="/images/index/darkmodelogo.png" class="h-10"></router-link>
    </div>
    <div class="nav-bar">
      <ul class="flex items-center space-x-8">
        <li><router-link to="/" class="hover:text-gray-300">Home</router-link></li>
        <li><router-link to="/explore" class="hover:text-gray-300">Explore</router-link></li>
        <li><router-link to="/recipes" class="hover:text-gray-300">Recipes</router-link></li>
      </ul>
    </div>
    <template v-if="isLoggedIn">
      <button @click="logout" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-8 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Logout</button>
    </template>
    <template v-else>
      <router-link to="/login" class="mx-3 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Login</router-link>
      <router-link to="/register" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Signup</router-link>
    </template>
  </div>

  <!-- Header -->
  <header class="header">
    <nav class="navbar bg-gray-900 text-white py-4 px-8">
      <ul class="nav-menu flex items-center space-x-8">
        <li><router-link to="/" class="hover:text-gray-300">Home</router-link></li>
        <li><router-link to="/explore" class="hover:text-gray-300">Explore</router-link></li>
        <li><router-link to="/recipes" class="hover:text-gray-300">Recipes</router-link></li>
      </ul>
    </nav>
  </header>
</template>

<script>
import axiosClient from '../api/axios';

export default {
  data() {
    return {
      isLoggedIn: false // Initially assume user is not logged in
    };
  },
  created() {
    // Check user's authentication status
    this.isLoggedIn = this.checkAuthentication();
  },
  methods: {
    checkAuthentication() {
      // Implement your logic to check if the user is authenticated
      // For example, you can check if a token exists in localStorage or sessionStorage
      const token = localStorage.getItem('authToken');
      return !!token; // Convert to boolean
    },
    async logout() {
      try {
        await axiosClient.post('/logout');
        localStorage.removeItem('authToken');
        localStorage.removeItem('user');
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error.response ? error.response.data : error.message);
      }
    },
  }
};
</script>

<style scoped>
.header{
  display:none;
}

</style>
