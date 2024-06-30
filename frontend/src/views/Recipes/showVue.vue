<template>
  <div class="wrapper px-8 mx-auto" v-if="recipe">
    <input class="btn_animation__checkbox" id="animation" type="checkbox">
    
    <label class="btn_animation" for="animation">
      <svg width="60" height="60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800">
        <path style="fill:#60b533" d="m178.6 202 202.1 198-202.1 198-60.3-61.8L257.2 400 118.3 263.8z"/>
        <path style="fill:#349905" d="m273.5 0 408.2 400-408.2 400-121.9-124.9L432.3 400 151.6 124.9z"/>
        <path style="fill:#5ecc25" d="m202.1 401-84-86.2v166.4z"/>
      </svg>
    </label>

    <input class="btn_go_down__checkbox" id="goDown" type="checkbox" >
    <label class="btn_go_down" for="goDown" @click="toggleComments">
      <svg width="60" height="60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" transform="rotate(90)">
        <path style="fill:#60b533" d="m178.6 202 202.1 198-202.1 198-60.3-61.8L257.2 400 118.3 263.8z"/>
        <path style="fill:#349905" d="m273.5 0 408.2 400-408.2 400-121.9-124.9L432.3 400 151.6 124.9z"/>
        <path style="fill:#5ecc25" d="m202.1 401-84-86.2v166.4z"/>
      </svg>      
    </label>

    <main class="main ">
      <div class="main_section">
        <div class="header_wrapper">
          <h1 class="header_title">{{ recipe.title }}</h1>
          <p class="header_subtitle ">Category : {{ recipe.category?.name || 'N/A' }}</p>
        </div>

        <img class="main_image rounded-3xl" v-if="recipe.image_url" :src="recipe.image_url" alt="Recipe Image" width="800" height="800">

        <div class="recipe_footer ">
          <dl class="recipe_controls mt-5">
            <dt class="subtitle">Cooking Time</dt>
            <dd>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#0F0F0F"
                      d="M23 12c0 6.075-4.925 11-11 11S1 18.075 1 12 5.925 1 12 1s11 4.925 11 11ZM3.007 12a8.993 8.993 0 1 0 17.986 0 8.993 8.993 0 0 0-17.986 0Z"/>
                <path fill="#0F0F0F"
                      d="M12 5a1 1 0 0 0-1 1v6.467s0 .26.127.457c.084.166.217.31.39.41l4.62 2.668a1 1 0 0 0 1-1.732L13 11.88V6a1 1 0 0 0-1-1Z"/>
              </svg>
              <span>{{ recipe.total_time }} min</span>
            </dd>

            <dt class="subtitle">Serving</dt>
            <dd>
              <svg height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 487.55 487.55"
                   xml:space="preserve"><path d="M244.625 171.415c-40 0-72.4 32.4-72.4 72.4s32.4 72.4 72.4 72.4 72.4-32.4 72.4-72.4c-.1-40-32.5-72.4-72.4-72.4zm0 48.8c-13 0-23.6 10.6-23.6 23.6 0 6-4.8 10.8-10.8 10.8s-10.8-4.8-10.8-10.8c0-24.9 20.3-45.2 45.2-45.2 6 0 10.8 4.8 10.8 10.8 0 6-4.9 10.8-10.8 10.8z"/>
              <path
                      d="M481.325 227.515c-224.8-258.6-428-53.9-476.4 2.8-6.5 7.6-6.6 18.8-.1 26.4 221.9 259 423.4 64.6 476.5 3.7 8.3-9.4 8.3-23.4 0-32.9zm-236.7 119.1c-56.8 0-102.8-46-102.8-102.8s46-102.8 102.8-102.8 102.8 46 102.8 102.8-46.1 102.8-102.8 102.8z"/></svg>
              <span>{{ recipe.servings }} people</span>
            </dd>

            user infos
            <div class="user"  v-if="recipe.user">
              <dt class="subtitle">Shared by :</dt>
              <dd>
                <div class="user-img h-10 w-10 border rounded-full overflow-hidden mr-4">
                  <img draggable="false" v-if="recipe.user.profile_picture" :src="recipe.user.profile_picture_url" alt="User Image" class="h-full w-full object-cover">
                </div>
                <div class="user-name-and-place flex flex-col">
                  <span class="text-sm font-bold text-gray-700">{{ recipe.user.name }}</span>
                </div>
              </dd>
            </div>
          </dl>

          <div class="recipe_ingredients mt-5">
            <p class="recipe_title subtitle">Ingredients</p>
            <ul class="recipe_list">
              <li v-for="ingredient in recipe.ingredients.split('\n')">- {{ ingredient.trim() }}</li>
            </ul>
          </div>
           

          <div class="ingredients ">
            <template v-if="highlightedIngredients.length > 0">
              <figure class="ingredients_item" v-for="ingredient in highlightedIngredients" :key="ingredient.id">
                <img :src="ingredient.image_url" :alt="ingredient.name"/>
                <figcaption>{{ ingredient.name }}</figcaption>
              </figure>
            </template>
            <template v-else>
              <p class="text-gray-400">No highlighted ingredients available.</p>
            </template>
          </div>


        </div>
      </div>

      <div class="main_video" >
        <h1 class="steps_title  text-gray-100">Steps</h1>
        <ol class="steps_list">
          <li v-for="(step, index) in recipe.steps.split('\n')">{{ 'Step ' + (index + 1) + ': ' + step.trim() }}</li>
        </ol>
      <div class="video_box">
        <div class="video_box__wrap">
          <video v-if="recipe.video_url" :src="recipe.video_url" controls autoplay loop poster="">
            Recipe video
          </video>
          <div v-else class="no-video flex justify-center items-center h-full bg-gray-300 rounded-lg text-gray-700 text-lg font-semibold text-center p-5 shadow-inner">
            No video of this recipe is shared
          </div>
        </div>
      </div>

    </div>
    </main>
  </div>
    <!-- Comment Section -->
    <div class="comment-section flex flex-col items-center py-8" style="background-color:#2f3138">
      <div class="w-full max-w-2xl bg-white rounded-lg shadow-md p-6" v-if="user">
        <div class="flex items-center mb-4">
          <img :src="user.profile_picture_url" alt="User Image" class="rounded-full w-12 h-12 mr-3">
          <h1 class="font-semibold text-lg">{{ user.name }}</h1>
        </div>
        <form @submit.prevent="submitComment" class="mb-4">
          <textarea rows="3" v-model="newComment.content"
                    class="border border-gray-300 p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Write a comment..."></textarea>
          <div class="flex justify-end mt-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-500">
              Submit
            </button>
          </div>
        </form>
      </div>
  
      <ul class="w-full max-w-2xl mt-5 bg-white rounded-lg shadow-md p-6 space-y-4">
        <li v-for="comment in comments" :key="comment.id" class="flex items-start space-x-4">
          <img :src="comment.user.profile_picture_url" alt="Commenter Image" class="rounded-full w-10 h-10">
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-semibold text-sm">{{ comment.user.name }}</p>
                <p class="text-gray-700">{{ comment.content }}</p>
                <p class="text-xs text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</p>
              </div>
              <!-- Delete Button (visible only to the owner of the comment or the recipe owner) -->
              <button v-if="canDeleteComment(comment)" @click="deleteComment(comment.id)"
                      class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  
</template>
<script>
import axiosClient from '../../api/axios';

export default {
  data() {
    return {
      recipe: null,
      highlightedIngredients: [],
      comments: [],
      user: null,
      newComment: {
        content: '',
        parent_comment_id: null
      },
      showComments: false
    };
  },
  mounted() {
    const recipeId = this.$route.params.recipeId;
    this.fetchRecipe(recipeId);
    this.fetchComments(recipeId);
    this.fetchUser();
  },
  methods: {
    async fetchRecipe(recipeId) {
      try {
        const response = await axiosClient.get(`/recipes/${recipeId}`);
        this.recipe = response.data.recipe;
        this.highlightedIngredients = response.data.highlighted_ingredients;
      } catch (error) {
        if (error.response && error.response.status === 404) {
          console.error('Recipe not found');
          this.$router.push('/not-found');
        } else {
          console.error('Error fetching recipe:', error);
        }
      }
    },
    async fetchComments(recipeId) {
      try {
        const response = await axiosClient.get(`/comments/recipe/${recipeId}`);
        this.comments = response.data.comments;
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    },
    async fetchUser() {
      try {
        const response = await axiosClient.get('/dashboard');
        this.user = response.data.user;
      } catch (error) {
        console.error('Error fetching user:', error);
      }
    },
    async submitComment() {
      const recipeId = this.$route.params.recipeId;
      if (!this.user) {
        console.error('User not logged in');
        return;
      }
      try {
        const commentData = {
          user_id: this.user.id,
          recipe_id: recipeId,
          content: this.newComment.content,
          parent_comment_id: this.newComment.parent_comment_id
        };
        const response = await axiosClient.post(`/comments`, commentData);
        this.comments.push(response.data.comment);
        this.newComment.content = '';
        this.newComment.parent_comment_id = null;
      } catch (error) {
        console.error('Error submitting comment:', error);
      }
    },
    async deleteComment(commentId) {
      try {
        await axiosClient.delete(`/comments/${commentId}`);
        this.comments = this.comments.filter(comment => comment.id !== commentId);
      } catch (error) {
        console.error('Error deleting comment:', error);
      }
    },
    canDeleteComment(comment) {
      // Check if the user is logged in and either the recipe owner or the comment author
      return this.user && (this.user.id === this.recipe.user_id || this.user.id === comment.user_id);
    },
    toggleComments() {
      this.showComments = !this.showComments;
      if (this.showComments) {
        this.$nextTick(() => {
          document.querySelector('.comment-section').scrollIntoView({ behavior: 'smooth' });
        });
      }
    }
  }
};
</script>

<style>

</style>
<style src="./../../show.css"></style>
