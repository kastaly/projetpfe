<template>
    <div>
      <p > admin </p>
      <button @click="logout">Logout</button>
    </div>
  </template>
  
  <script>
  import axiosClient from '@/api/axios';
  
  export default {
    data() {
      return {
        user: null,
      };
    },
    async created() {
      try {
        const response = await axiosClient.get('/user');
        this.user = response.data;
      } catch (error) {
        console.error('Failed to fetch user:', error.response ? error.response.data : error.message);
      }
    },
    methods: {
      async logout() {
        try {
          await axiosClient.post('/logout');
          localStorage.removeItem('authToken');
          this.$router.push('/login');
        } catch (error) {
          console.error('Logout failed:', error.response ? error.response.data : error.message);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  </style>
  