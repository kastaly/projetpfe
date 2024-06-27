<template>
  <div id="app">
    <router-view></router-view>
    <Sidebar v-if="isLoggedIn" />
  </div>
</template>

<script>
import Sidebar from './components/partials/Sidebar.vue';
import { mapState } from 'vuex';

export default {
  components: {
    Sidebar,
  },
  computed: {
    ...mapState(['isLoggedIn']),
  },
};
</script>

<style>
/* Your global styles */
</style>
