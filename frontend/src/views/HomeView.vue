<template>
  <div class="">
    <div>
      <NavComponenent />
    </div>
    <headerSection/>
    <ourServices/>

    <!-- recipes -->
    <section class="bg-gray-100 py-20">
      <div class="container mx-auto px-6 md:px-12 lg:px-24">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Explore Our Latest Recipes</h2>
          <p class="text-lg text-gray-600 mt-2">Indulge in the flavors of our newest culinary creations</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12" v-if="recipes">
          <div v-for="recipe in recipes" :key="recipe.id"
               class="bg-white rounded-lg shadow-md overflow-hidden transition duration-500 ease-in-out transform hover:scale-105">
            <img :src="recipe.image_url" alt="{{ recipe.title }}" class="w-full h-64 object-cover">
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ recipe.title }}</h3>
              <p class="text-sm text-gray-600 mb-4">{{ recipe.steps.substring(0, 150) }}
                <span v-if="recipe.steps.length > 200">... <span class="text-blue-400">See more</span></span>
              </p>
              <a :href="`/show-recipe/${recipe.id}#recipe-section`"
                 class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full inline-block transition duration-300 ease-in-out">View Recipe</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <heroSection/>

    <!-- Comments Section with Owl Carousel -->
    <div v-if="comments.length" class="container py-24 mx-auto px-40 bg-gray-900 relative">
      <!-- Static Circles -->
      <div class="flex justify-between absolute top-0 left-0 right-0">
        <div class="w-24 h-24 bg-gray-900 rounded-full mt-8 ml-12"></div>
        <div class="w-20 h-20 bg-gray-900 rounded-md mt-8 mr-8"></div>
      </div>
      <div class="flex justify-between absolute top-0 left-0 right-0">
        <div class="w-24 h-24 bg-gray-900 rounded-3xl mt-40 ml-2"></div>
        <div class="w-20 h-20 bg-gray-900 rounded-full mt-40 mr-18"></div>
      </div>

      <section class="text-center relative z-10">
        <h2 class="pb-5 text-3xl font-serif dark:text-gray-100">What Our Users Say About Our Recipes</h2>
        <p class="text-sm text-gray-600 mb-20 dark:text-gray-100">Read testimonials from our satisfied users</p>

        <div class="owl-carousel owl-theme">
          <div v-for="comment in comments" :key="comment.id" class="item">
            <div class="bg-white rounded-lg shadow-md p-6 mx-2 mb-6  h-full">
              <img :src="comment.user.profile_picture_url" class="w-20 h-20 rounded-full shadow-lg border-2 border-yellow-400 object-cover mx-auto mb-4" />
              <h5 class="text-lg font-bold mb-2">{{ comment.user.name }}</h5>
              <p class="text-gray-700">{{ comment.content.substring(0, 30) }}<span v-if="comment.content.length > 30">... </span></p>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- about us -->
    <aboutUs/>

    <!-- why us section -->
    <whyUs/>

    <!-- emotional section -->
    <emotionalSection/>

    <!-- footer -->
    <footer class="bg-white dark:bg-gray-900">
      <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
          <router-link to="/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
            <img src="/images/index/darkmodelogo.png" alt="Application Logo" class="h-10" />
          </router-link>
          <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
            <li>
              <router-link to="/about" class="hover:underline me-4 md:me-6">About</router-link>
            </li>
            <li>
              <router-link to="/contact" class="hover:underline me-4 md:me-6">Contact</router-link>
            </li>
            <li>
              <router-link to="/recipes" class="hover:underline">Recipes</router-link>
            </li>
          </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="#">FoodieHub</a>. All Rights Reserved.</span>
      </div>
    </footer>
  </div>
</template>

<script>
import axiosClient from '../api/axios';
import NavComponenent from '../components/NavComponenent.vue';
import headerSection from './sections/headerSection.vue';
import heroSection from './sections/heroSection.vue';
import ourServices from './sections/ourServices.vue';
import aboutUs from './sections/aboutUs.vue';
import emotionalSection from './sections/emotionalSection.vue';
import whyUs from './sections/whyUs.vue';

export default {
  name: 'RecipesPage',
  components: {
    NavComponenent,
    headerSection,
    heroSection,
    ourServices,
    aboutUs,
    emotionalSection,
    whyUs,
  },
  data() {
    return {
      recipes: [],
      comments: [],
    };
  },
  mounted() {
    this.fetchRecipes();
    this.fetchComments();
  },
  methods: {
    async fetchRecipes() {
  try {
    const response = await axiosClient.get('/recipes');
    // Assuming response.data.recipes is an array of all recipes
    this.recipes = response.data.recipes.slice(0, 9); // Limit to the first 9 recipes
  } catch (error) {
    console.error('Error fetching recipes:', error);
  }
},

    async fetchComments() {
      try {
        const response = await axiosClient.get('/show-Comments');
        this.comments = response.data.comments;
        this.$nextTick(() => {
          this.initializeOwlCarousel();
        });
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    },
    initializeOwlCarousel() {
      this.$nextTick(() => {
        $('.owl-carousel').owlCarousel({
          loop: true,
          margin: 10,
          nav: true,
          dots: true,
          autoplay: true,
          autoplayTimeout: 4000,
          responsive: {
            0: {
              items: 1
            },
            400: {
              items: 2
            },
            7000: {
              items: 3
            },
            1000: {
              items: 4
            }
          }
        });
      });
    }
  },
};
</script>


<style scoped>
.callibari {
  font-family: 'Calligraffitti', cursive;
}
</style>