<template>
  <div class="flex bg-dark min-h-screen mx-auto">
    <div class="container-fluid text-gray-800 py-12 px-4 mx-auto" id="main-content">
      <main class="p-6 sm:p-10 space-y-6">
        <div class="text-center">
          <h1 class="text-4xl font-semibold mb-2 callibFont">Dashboard</h1>
          <h2 class="ml-0.5">FoodieHub</h2>
        </div>
        
        <div class="flex flex-wrap justify-center mt-6">
          <div class="me-2 " @click="selectTab('manage')">
            <a href="#" :class="tabClass('manage')" @click.prevent
          
                  class="inline-flex px-5 py-3 bg-gray-800 text-orange-100 hover:text-orange-700 focus:text-orange-700 hover:bg-orange-100 focus:bg-orange-100 border border-orange-600 rounded-md mb-3">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="flex-shrink-0 h-5 w-5 -ml-1 mt-0.5 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Manage my recipes
          </a></div>

          <router-link to="/create-recipe">
          <button 
                  class="inline-flex px-5 py-3 text-white bg-orange-600 hover:bg-orange-700 focus:bg-orange-700 rounded-md ml-6 mb-3">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="flex-shrink-0 h-6 w-6 text-white -ml-1 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create new recipe
          </button></router-link>
        </div>

        <div class="responsive-div border border-gray-200 dark:border-gray-700 mx-auto rounded-md px-5">
        
                <ul class=" flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <li class="me-2 " @click="selectTab('manage')">
              <a href="#" :class="tabClass('manage')" @click.prevent>
                <svg class="w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                </svg>
                manage
              </a>
            </li>
            <li class="me-2" @click="selectTab('dashboard')">
              <a href="#" :class="tabClass('dashboard')" @click.prevent>
                <svg class="w-4 h-4 me-2 text-orange-600 dark:text-orange-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>
                Dashboard
              </a>
            </li>
            <li class="me-2" @click="selectTab('settings')">
              <a href="#" :class="tabClass('settings')" @click.prevent>
                <svg class="w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z"/>
                </svg>
                Settings
              </a>
            </li>
          
            <li>
              <a class="inline-block p-4 text-gray-400 rounded-t-lg cursor-not-allowed dark:text-gray-500">Disabled</a>
            </li>
          </ul>
        </div>

       
        <div v-if="currentTab === 'dashboard'">
          <recipe-count />
          <MostViewedRecipes  />
        </div>
        <div v-if="currentTab === 'manage'">
          <manage-recipes />
        </div>
        <div v-if="currentTab === 'settings'">
          <UpdateProfile />
        </div>
        <div v-if="currentTab === 'contacts'">
          <p>Contacts content goes here...</p>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import RecipeCount from '../components/dashboard/RecipeCountComponent.vue';
import ManageRecipes from '../components/dashboard/ManageMyRecipes.vue';
import UpdateProfile from '../components/partials/UpdateProfileInfo.vue';
import MostViewedRecipes from '../components/dashboard/MostViewedRecipes.vue';

export default {
  data() {
    return {
      currentTab: 'dashboard',
    };
  },
  methods: {
    selectTab(tab) {
      this.currentTab = tab;
    },
    tabClass(tab) {
      return this.currentTab === tab
        ? 'inline-flex items-center justify-center p-4 text-orange-600 border-b-2 border-orange-600 rounded-t-lg active dark:text-orange-500 dark:border-orange-500 group'
        : 'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group';
    },
  },
  components: {
    RecipeCount,
    ManageRecipes,
    UpdateProfile,
    MostViewedRecipes,
  },
};
</script>

<style scoped>
.callibFont {
  font-family: "Calligraffitti", cursive;
}
.responsive-div {
  width: 90%; 
  max-width: 90vh; 
  margin: 0 auto; 
  box-sizing: border-box; /* Ensures padding and border are included in the total width and height */
}

@media (max-width: 600px) {
  .responsive-div {
      padding: 8px; 
  }
}

</style>
