<template>
    <div class=" mx-auto sm:p-24 lg:p-48 bg-gray-100">
      <h2 class="text-4xl font-semibold text-center text-gray-800 mb-8">Emotional Food Stories</h2>
      <p class="text-lg text-center text-gray-600 mb-12">Explore these heartwarming tales inspired by the colors of food.</p>
      <div class="relative rounded-lg block md:flex items-center bg-gray-100 shadow-xl" style="min-height: 19rem;">
        <!-- Image -->
        <div class="relative w-full md:w-2/5 h-full overflow-hidden rounded-t-lg md:rounded-t-none md:rounded-l-lg bg-red-200" style="min-height: 19rem;">
          <img class="inset-0 w-full h-full object-cover object-center" src="/images/index/emotionnel.png" alt="">
        </div>
        <!-- Slide -->
        <div class="w-full md:w-3/5 h-full bg-gray-100 rounded-lg">
          <div class="max-w-4xl mx-auto relative" :key="activeSlide">
            <!-- Slides -->
            <div v-for="(story, index) in stories" :key="index" v-show="activeSlide === index + 1" class="p-12 md:pr-24 md:pl-16 md:py-12">
              <h3 class="text-2xl font-semibold mb-4 text-orange-900">{{ story.title }}</h3>
              <p class="text-lg text-gray-700 mb-4">{{ story.description }}</p>
              <p class="text-base text-gray-600">{{ story.additional }}</p>
            </div>
  
            <!-- Prev/Next Arrows -->
            <div class="absolute inset-0 flex">
              <div class="flex items-center justify-start w-1/2">
                <button class="bg-orange-950 text-white hover:text-orange-500 font-bold hover:shadow-lg rounded-full w-12 h-12 -ml-6" @click="prevSlide">&#8592;</button>
              </div>
              <div class="flex items-center justify-end w-1/2">
                <button class="bg-orange-950 text-white hover:text-orange-500 font-bold hover:shadow-lg rounded-full w-12 h-12 -mr-6" @click="nextSlide">&#8594;</button>
              </div>
            </div>
  
            <!-- Buttons -->
            <div class="absolute w-full flex items-center justify-center px-4">
              <button v-for="slide in slides" :key="slide" class="flex-1 w-4 h-2 mt-4 mx-2 mb-0 rounded-full overflow-hidden transition-colors duration-200 ease-out"
                      :class="{'bg-orange-950': activeSlide === slide, 'bg-orange-900': activeSlide !== slide}"
                      @click="activeSlide = slide">
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeSlide: 1,
        stories: [
          {
            title: 'Saffron Sunset',
            description: 'Bask in the golden glow of a saffron-infused curry, a warm reminder of home.',
            additional: 'Feel the spices dance on your tongue as you savor each bite.'
          },
          {
            title: 'Berry Bliss',
            description: 'Indulge in the sweet embrace of ripe berries, bursting with flavor and memories of summer.',
            additional: 'Let the vibrant hues awaken your senses and transport you to sun-kissed orchards.'
          },
          {
            title: 'Chocolate Serenity',
            description: 'Find solace in the rich depths of velvety chocolate, a decadent treat for the soul.',
            additional: 'Allow the silky smoothness to envelop you in a moment of pure bliss.'
          }
        ]
      };
    },
    computed: {
      slides() {
        return this.stories.length;
      }
    },
    methods: {
      nextSlide() {
        this.activeSlide = this.activeSlide === this.slides ? 1 : this.activeSlide + 1;
      },
      prevSlide() {
        this.activeSlide = this.activeSlide === 1 ? this.slides : this.activeSlide - 1;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Scoped styles can be added here if needed */
  </style>
  