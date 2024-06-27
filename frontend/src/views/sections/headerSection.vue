<template>
    <div class="container ">
      <!-- Carousel Section -->
      <div class="carousel-section">
        <div class="carousel-wrapper relative" style="height: 90vh;">
          <!-- Navigation Buttons -->
          <button class="wiper-button wiper-button__left" @click="prevSlide">
            <img width="40px" src="https://www.iconpacks.net/icons/2/free-arrow-left-icon-3099-thumb.png" alt="left" />
          </button>
          <button class="wiper-button wiper-button__right" @click="nextSlide">
            <img width="40px" src="https://www.iconpacks.net/icons/2/free-arrow-left-icon-3099-thumb.png" alt="right" />
          </button>
          <!-- Slider Wrapper -->
          <div class="wiper-wrapper overflow-hidden" style="height: 100%;">
            <ul class="wiper-track" :style="{ transform: `translateX(-${currentSlideIndex * wipeWidth}%)` }">
              <!-- Carousel Items -->
              <li v-for="(slide, index) in slides" :key="index" class="wiper-item relative overflow-hidden"
                  :style="{ zIndex: slides.length - index }">
                <img class="w-full h-full object-cover swiper__image" :src="slide.image" :alt="'slide-' + index" />
              </li>
            </ul>
          </div>
        </div>
      </div>
  
      <!-- Food Section -->
      <div class="food-section self-center mx-auto">
        <div class="side-info space-y-6">
          <span>Delicious Recipes</span>
          <h1>Explore Our Tastiest Creations</h1>
          <hr>
          <p class="pb-8">Discover a wide range of delicious recipes crafted with love and passion. From savory to sweet, explore our culinary delights.</p>
          <a href="#" class="px-6 py-3 text-xl bg-yellow-600 rounded-xl text-white">Browse Recipes</a>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        slides: [
          { image: '/public/images/index/pizza.png' },
          { image: '/public/images/index/why-us.jpg' },
          { image: '/public/images/index/salad.png' },
        ],
        currentSlideIndex: 0,
        wipeWidth: 100, // Adjust as per your design
        autoplayInterval: null,
        autoplayDelay: 3000 // Adjust autoplay delay in milliseconds
      };
    },
    computed: {
      visibleSlides() {
        // Only show 3 slides at a time
        return this.slides.slice(this.currentSlideIndex, this.currentSlideIndex + 3);
      }
    },
    mounted() {
      this.startAutoplay();
    },
    methods: {
      startAutoplay() {
        this.autoplayInterval = setInterval(() => {
          this.nextSlide();
        }, this.autoplayDelay);
      },
      stopAutoplay() {
        clearInterval(this.autoplayInterval);
      },
      nextSlide() {
        if (this.currentSlideIndex < this.slides.length - 1) {
          this.currentSlideIndex++;
        } else {
          this.currentSlideIndex = 0; // Loop back to the first slide
        }
      },
      prevSlide() {
        if (this.currentSlideIndex > 0) {
          this.currentSlideIndex--;
        } else {
          this.currentSlideIndex = this.slides.length - 1; // Go to last slide when at first slide
        }
      },
      handleButtonClick(index) {
        console.log('Button clicked on slide ' + index);
        // Handle button click logic
      }
    },
    beforeDestroy() {
      this.stopAutoplay();
    }
  };
  </script>
  
  <style scoped>
  .container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }
  
  .carousel-section {
    width: calc(56% - 10px); /* Adjust as per your layout */
    height: 90vh; /* Set fixed height of 90% viewport height */
  }
  
  .food-section {
    width: calc(40% - 10px); /* Adjust as per your layout */
  }
  
  .carousel-wrapper {
    position: relative;
  }
  
  .wiper-wrapper {
    overflow: hidden;
    height: 100%; /* Ensure full height within the carousel wrapper */
  }
  
  .wiper-track {
    display: flex;
    transition: transform 0.4s ease-in-out;
  }
  
  .wiper-item {
    flex: 0 0 100%; /* Ensure each slide takes full width of the carousel */
    max-width: 100%;
    position: relative;
    transition: transform 0.2s linear;
    overflow: hidden;
  }
  
  .wiper-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.8);
    border: none;
    cursor: pointer;
    z-index: 10;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .wiper-button__left {
    left: 10px;
  }
  
  .wiper-button__right {
    right: 10px;
  }
  
  .swiper__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .side-info {
    margin-top: 20px;
  }
  
  .side-info span {
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 16px;
    color: #717171;
  }
  
  .side-info h1 {
    text-transform: capitalize;
    letter-spacing: 0.8px;
    font-family: "Roboto", sans-serif;
    font-weight: 900;
    font-size: 40px; /* Adjust font size for better fit */
    background-color: #aa8e00;
    background-image: linear-gradient(45deg, #c5a90a, #000000);
    background-size: 100%;
    background-repeat: repeat;
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    -moz-background-clip: text;
    -moz-text-fill-color: transparent;
  }
  
  .side-info hr {
    display: block;
    background: #aa7400;
    height: 4px;
    width: 20%;
    margin: 18px 0;
  }
  
  .side-info p {
    color: #717171;
    font-weight: 500;
    font-size: 18px;
  }
  
 
  
  @media (max-width: 800px) {
    .container {
      flex-direction: column;
      align-items: center;
    }
  
    .carousel-section {
      width: 100%;
      margin-bottom: 20px; /* Add spacing between sections */
      height: auto; /* Allow height to adjust based on content */
    }
  
    .food-section {
      width: 100%;
    }
  }
  </style>
  