<template>
    <section id="about-us" class=" bg-white">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
          <!-- Image Section with Animation -->
          <div class="relative overflow-hidden rounded-lg shadow-xl">
            <img src="/images/index/recipe-book.png" alt="Recipe Book" class="w-full h-auto object-cover rounded-lg transition-transform duration-500 transform hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900 opacity-50 transition-opacity duration-500 hover:opacity-0"></div>
          </div>
  
          <!-- Tabs and Content Section with Animation -->
          <div class="flex flex-col justify-center">
            <h2 class="text-3xl md:text-4xl font-bold text-green-700 callibari mb-6 text-center md:text-left">The New Tradition Is Good Nutrition</h2>
            <div class="tabs flex justify-center md:justify-start space-x-6 mb-6">
              <div
                v-for="(tab, index) in tabs"
                :key="index"
                @click="activeTab = index"
                :class="{ 'active': activeTab === index }"
                class="cursor-pointer font-semibold text-lg py-2 transition duration-300 ease-in-out hover:text-green-600"
              >
                {{ tab.name }}
              </div>
            </div>
            <div class="tab-content px-4">
              <div v-for="(tab, index) in tabs" :key="index" v-show="activeTab === index" class="transition-opacity duration-500" :class="{ 'opacity-100': activeTab === index, 'opacity-0': activeTab !== index }">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ tab.title }}</h3>
                <p class="text-gray-700 leading-relaxed">{{ tab.content }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeTab: 0,
        tabs: [
          {
            name: 'Our History',
            title: 'History',
            content: `Founded in 2001, our journey started with a small group of passionate chefs who believed in the power of good nutrition. Over the years, we have grown into a community-driven platform that shares thousands of recipes from around the world. Our commitment to quality and nutrition has always been at the core of what we do.`
          },
          {
            name: 'Our Vision',
            title: 'Vision',
            content: `Our vision is to inspire and empower individuals to lead healthier lives through nutritious food. We envision a world where healthy eating is accessible to everyone, and we strive to make this possible by providing easy-to-follow recipes, nutritional information, and cooking tips.`
          },
          {
            name: 'Our Mission',
            title: 'Mission',
            content: `Our mission is to be the go-to resource for anyone looking to improve their diet and health. We are dedicated to creating a supportive community where users can share their culinary experiences, discover new recipes, and learn about the benefits of good nutrition.`
          }
        ]
      };
    },
  };
  </script>
  
  <style scoped>
  .tabs .active {
    color: #4E7C58;
    border-bottom: 2px solid #4E7C58;
  }
  .tab-content {
    min-height: 200px;
  }
  .tab-content > div {
    transition: opacity 0.5s ease-in-out;
  }
  .img-overlay {
    transition: opacity 0.5s ease-in-out;
  }
  </style>
  