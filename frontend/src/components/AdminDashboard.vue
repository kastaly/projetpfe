<template>
  <div class="flex h-screen bg-gray-200 font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header />

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
//import { defineComponent, ref } from "vue";

import Sidebar from "./dashboard/sidebarVue.vue";
import Header from "./dashboard/headerVue.vue";
export default {
  data() {
    return {
      message: ''
    }
  },
  components: {
    Header,
    Sidebar,
  },
  created() {
    this.fetchDashboardData();
  },
  methods: {
    async fetchDashboardData() {
      try {
        const response = await axios.get('/api/admin-dashboard');
        this.message = response.data.message;
      } catch (error) {
        console.error('Error fetching admin dashboard data:', error);
      }
    }
  }
}
</script>
