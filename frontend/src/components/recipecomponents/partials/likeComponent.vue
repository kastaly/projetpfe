<template>
  <div>
    <input
      type="checkbox"
      class="checkbox"
      :id="'checkbox-' + recipeId"
      :checked="liked"
      @change="toggleLike"
      :disabled="loading"
    />
    <label :for="'checkbox-' + recipeId">
      <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg">
        <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)">
          <path
            id="heart"
            :fill="liked ? '#E2264D' : '#AAB8C2'"
            d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59"
          />
          <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/>
          <g id="grp7" opacity="0" transform="translate(7 6)">
            <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/>
            <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/>
          </g>
          <g id="grp6" opacity="0" transform="translate(0 28)">
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/>
          </g>
          <g id="grp3" opacity="0" transform="translate(52 28)">
            <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/>
            <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/>
          </g>
          <g id="grp2" opacity="0" transform="translate(44 6)">
            <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/>
            <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/>
          </g>
          <g id="grp5" opacity="0" transform="translate(14 50)">
            <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/>
          </g>
          <g id="grp4" opacity="0" transform="translate(35 50)">
            <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/>
            <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/>
          </g>
          <g id="grp1" opacity="0" transform="translate(24)">
            <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/>
            <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/>
          </g>
        </g>
      </svg>
    </label>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    recipeId: {
      type: Number,
      required: true,
    },
    liked: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    async toggleLike() {
  if (!localStorage.getItem('authToken')) {
    alert('You need to log in to like a recipe.');
    return;
  }

  this.loading = true;
  
  try {
    let url;
    let method;
    
    if (this.liked) {
      method = 'DELETE';
      url = `http://localhost:8000/api/recipes/${this.recipeId}/unlike`;
    } else {
      method = 'POST';
      url = `http://localhost:8000/api/recipes/${this.recipeId}/like`;
    }

    const response = await axios({
      method: method,
      url: url,
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    });

    console.log('Toggle like response:', response.data); // Log the API response for debugging

    // Update liked status locally and emit event to parent component
    this.$emit('like-status-changed', this.recipeId, !this.liked);
  } catch (error) {
    console.error('Error toggling like:', error);
    alert('Failed to toggle like. Please try again later.');
  } finally {
    this.loading = false;
  }
}

  },
};
</script>

<style src="./../../../likeComponent.css"></style>
