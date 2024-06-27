<template>
  <div class="reel-container mx-auto h-screen items-center py-6
   md:w-1/2 overflow-hidden">
    <div class="relative h-full flex ">
      
      <!-- Display current video -->
      <div class="video-wrapper  h-full flex items-center justify-center" v-for="(recipe, index) in recipes" v-show="index === recipeIndex" :key="recipe.id">
        <div class="video-box relative w-full h-full ">
          <video v-if="recipe.video_url" :src="recipe.video_url" @click="togglePlayPause" ref="video" :muted="isMuted" autoplay loop class="w-full h-full object-cover">
            Recipe video
          </video>
        </div>
        
        <!-- Video Information -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-black bg-opacity-50 text-white flex justify-between items-center">
          <div class="flex items-start">
            <img :src="recipe.user.profile_picture_url" alt="Profile Picture" class="w-12 h-12 rounded-full ">
            <div class="ml-4">
              <p class="text-lg font-semibold">@{{ recipe.user.name }}</p>
              <h3 class="text-sm">{{ recipe.title }}</h3>
                 <!-- steps -->
                 <div class="py-1"><a :href="`/show-recipe/${recipe.id}#recipe-section`">
                  <p class="text-gray-200">{{ recipe.steps.substring(0, 50) }}<span v-if="recipe.steps.length > 200">... <span class="text-blue-400">See more</span></span></p>
                </a></div>
            </div>
          </div>
        </div>

        <!-- Like and Comment -->
        <div class="absolute sm:left-0 flex-col items-center space-y-5 like-comment" >
          <div class="like-component-wrapper  flex flex-col items-center rounded-full bg-black bg-opacity-40 py-4 px-1">
            <like-component 
              :recipe-id="recipe.id"
              :liked="recipe.liked"
              @like-status-changed="handleLikeStatusChanged"
            ></like-component>
            <small v-if="recipe.likes.length == 0">0 </small>
            <small v-else>{{ recipe.likes.length }}</small>
          </div>
          <div class="comments-component-wrapper flex flex-col  items-center space-y-4 py-4 rounded-full bg-black bg-opacity-40 ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
              <path d="M19 2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12l4 4V6a4 4 0 0 0-4-4z"/>
            </svg>
            <small v-if="recipe.comments.length == 0">0 </small>
            <small v-else>{{ recipe.comments.length }}</small>
          </div>
        </div>
      </div>

      <!-- Navigation and Control Buttons -->
      <div class="controls-wrapper flex flex-col justify-center items-center space-y-4">
        <!-- Mute Button -->
        <button @click="toggleMute" class="audio-button bg-black bg-opacity-50 p-2 rounded-full text-white">
         <!-- Mute Button -->
      <svg v-if="isMuted" xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-volume-mute-fill h-7 w-7" viewBox="0 0 16 16">
        <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06m7.137 2.096a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0"/>
      </svg>

      <svg v-else xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-volume-up-fill h-7 w-7" viewBox="0 0 16 16">
        <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/>
        <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/>
        <path d="M8.707 11.182A4.5 4.5 0 0 0 10.025 8a4.5 4.5 0 0 0-1.318-3.182L8 5.525A3.5 3.5 0 0 1 9.025 8 3.5 3.5 0 0 1 8 10.475zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06"/>
      </svg>
        </button>
        
        <!-- Pause/Play Button -->
        <button @click="togglePlayPause" class="audio-button bg-black bg-opacity-50 p-2 rounded-full text-white">
          <svg v-if="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
            <path d="M5 3v18h4V3H5zm10 0v18h4V3h-4z"/>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
            <path d="M8 5v14l11-7L8 5z"/>
          </svg>
        </button>

        <!-- Navigation Buttons -->
        <button @click="prevVideo" class="nav-button bg-black bg-opacity-50 p-2 rounded-full text-white">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
            <path d="M12 4a1 1 0 0 1 .707.293l6 6a1 1 0 0 1-1.414 1.414L12 6.414 6.707 11.707a1 1 0 0 1-1.414-1.414l6-6A1 1 0 0 1 12 4z" />
          </svg>
        </button>
        
        <button @click="nextVideo" class="nav-button bg-black bg-opacity-50 p-2 rounded-full text-white">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
            <path d="M12 20a1 1 0 0 1-.707-.293l-6-6a1 1 0 0 1 1.414-1.414L12 17.586l5.293-5.293a1 1 0 0 1 1.414 1.414l-6 6A1 1 0 0 1 12 20z" />
          </svg>
        </button>
        
      </div>

    </div>
  </div>
</template>

<script>
import axiosClient from '../../api/axios';
import likeComponent from '../../components/recipecomponents/partials/likeComponent.vue';

export default {
  components: {
    likeComponent,
  },
  data() {
    return {
      recipes: [],
      userConnected: null,
      recipeIndex: 0,
      isMuted: true,
      isPaused: false,
    };
  },
  methods: {
    async fetchRecipes() {
      try {
        const response = await axiosClient.get('/recipes');
        this.recipes = response.data.recipes;

        if (!this.userConnected) {
          await this.fetchLoggedInUser();
        }

        this.recipes.forEach(recipe => {
          recipe.liked = this.userConnected ? recipe.likes.some(like => like.user.id === this.userConnected.id) : false;
        });
      } catch (error) {
        console.error('Error fetching recipes:', error);
      }
    },
    async fetchLoggedInUser() {
      try {
        const response = await axiosClient.get('/dashboard');
        this.userConnected = response.data.user;
      } catch (error) {
        console.error('Error fetching logged-in user:', error);
      }
    },
    prevVideo() {
      this.stopCurrentVideo();
      this.recipeIndex = (this.recipeIndex - 1 + this.recipes.length) % this.recipes.length;
      this.playCurrentVideo();
    },
    nextVideo() {
      this.stopCurrentVideo();
      this.recipeIndex = (this.recipeIndex + 1) % this.recipes.length;
      this.playCurrentVideo();
    },
    toggleMute() {
      this.isMuted = !this.isMuted;
      this.$refs.video[this.recipeIndex].muted = this.isMuted;
    },
    togglePlayPause() {
      const video = this.$refs.video[this.recipeIndex];
      if (this.isPaused) {
        video.play();
      } else {
        video.pause();
      }
      this.isPaused = !this.isPaused;
    },
    playCurrentVideo() {
      const video = this.$refs.video[this.recipeIndex];
      video.play();
      this.isPaused = false;
    },
    stopCurrentVideo() {
      const video = this.$refs.video[this.recipeIndex];
      video.pause();
      video.currentTime = 0;
    },
    handleLikeStatusChanged(recipeId, isLiked) {
      const recipe = this.recipes.find(r => r.id === recipeId);
      if (recipe) {
        recipe.liked = isLiked;
        if (isLiked) {
          recipe.likes.push({ user: this.userConnected });
        } else {
          recipe.likes = recipe.likes.filter(like => like.user.id !== this.userConnected.id);
        }
      }
    },
  },
  mounted() {
    this.fetchRecipes();
  },
};
</script>

<style scoped>
.reel-container {
  display: flex;
  justify-content: center;
  align-items: center;

}

.video-wrapper {
  position: relative;
  flex: 1;
  width:400px;
}

.video-box {
  position: relative;
}

.controls-wrapper {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

.audio-button, .nav-button {
  cursor: pointer;
  transition: background-color 0.3s;
}

.audio-button:hover, .nav-button:hover {
  background-color: rgba(0, 0, 0, 0.75);
}
.like-comment{
  transform: translateX(-120%);
  color:white;
}
</style>
