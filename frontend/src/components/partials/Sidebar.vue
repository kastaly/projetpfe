<template>
  <div :class="['sidebar', { 'bottom-sidebar': isMobile }]">
    <div class="logo-wrapper flex">
      <img src="/images/logo.png" alt="Logo">
    </div>

    <ul class="sidebar-menu">
      <li><router-link to="/"><a class="nav-link"><span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" aria-label="Home" class="_ab6-" color="rgb(0, 0, 0)" fill="rgb(0, 0, 0)" height="24" role="img" viewBox="0 0 24 24" width="24">
          <path d="M9.005 16.545a2.997 2.997 0 0 1 2.997-2.997A2.997 2.997 0 0 1 15 16.545V22h7V11.543L12 2 2 11.543V22h7.005Z" fill="none" stroke="#000000" stroke-linejoin="round" stroke-width="2px"></path>
        </svg></span></a></router-link></li>

      <li><router-link to="/explore"><a class="nav-link"><span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" aria-label="Search" class="_ab6-" color="rgb(0, 0, 0)" fill="rgb(0, 0, 0)" height="24" role="img" viewBox="0 0 24 24" width="24">
          <path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px"></path>
          <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px" x1="16.511" x2="22" y1="16.511" y2="22"></line>
        </svg></span></a></router-link></li>

      <li><router-link to="/recipes"><a class="nav-link"><span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" aria-label="Explore" class="_ab6-" color="rgb(0, 0, 0)" fill="rgb(0, 0, 0)" height="24" role="img" viewBox="0 0 24 24" width="24">
          <polygon fill="none" points="13.941 13.953 7.581 16.424 10.06 10.056 16.42 7.585 13.941 13.953" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px"></polygon>
          <circle cx="12.001" cy="12.005" fill="none" r="10.5" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px"></circle>
        </svg></span></a></router-link></li>

        <li><router-link to="/recipe-videos"><a class="nav-link"><span class="icon">
          <img src="/public/images/videoicon.png" alt="">          
          </span></a></router-link></li>

      <li><router-link to="/create-recipe"><a class="nav-link"><span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" aria-label="New post" class="_ab6-" color="rgb(0, 0, 0)" fill="rgb(0, 0, 0)" height="24" role="img" viewBox="0 0 24 24" width="24">
          <path d="M2 12v3.45c0 2.849.698 4.005 1.606 4.944.94.909 2.098 1.608 4.946 1.608h6.896c2.848 0 4.006-.7 4.946-1.608C21.302 19.455 22 18.3 22 15.45V8.552c0-2.849-.698-4.006-1.606-4.945C19.454 2.7 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.547 2 5.703 2 8.552Z" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px"></path>
          <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px" x1="6.545" x2="17.455" y1="12.001" y2="12.001"></line>
          <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2px" x1="12.003" x2="12.003" y1="6.545" y2="17.455"></line>
        </svg></span></a></router-link></li>

        <!-- dashboard route -->
        <li>
          <router-link to="/user-dashboard" class="nav-link">
            <span class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path d="M3 6h4v12H3zM9 4h4v14H9zM15 10h4v8h-4zM21 2v20h-2V2z"/>
              </svg>
              
            </span>
          </router-link>
        </li>

      <li v-if="userConnected">
        <router-link :to="`/user/${userConnected.id}`" class="nav-link active">
          <img class="user-image rounded-circle" :src="userConnected.profile_picture_url" alt="Profile icon">
        </router-link>
      </li>
    </ul>

    <ul class="sidebar-menu bottom" v-if="isLoggedIn">
      <li><a class="nav-link" @click="showModal = true"><span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" aria-label="Logout" color="rgb(0, 0, 0)" fill="rgb(0, 0, 0)" height="24" role="img" viewBox="0 0 24 24" width="24">
          <path d="M14 4h6a2 2 0 012 2v12a2 2 0 01-2 2h-6" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
          <polyline points="11 16 15 12 11 8" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline>
          <line x1="15" y1="12" x2="3" y2="12" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
        </svg></span></a></li>
    </ul>

    <LogoutModal v-if="showModal" @close="showModal = false" @confirm="logout" />
  </div>
</template>

<script>
import axiosClient from '../../api/axios';
import LogoutModal from '../modals/logoutModal.vue';

export default {
  components: {
    LogoutModal,
  },
  data() {
    return {
      showModal: false,
      isLoggedIn: false,
      userConnected: null,
      isMobile: window.innerWidth <= 768
    };
  },
  created() {
    this.isLoggedIn = this.checkAuthentication();
    if (this.isLoggedIn) {
      this.fetchUserConnected();
    }
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    checkAuthentication() {
      const token = localStorage.getItem('authToken');
      return !!token;
    },
    async fetchUserConnected() {
      try {
        const response = await axiosClient.get('/dashboard');
        this.userConnected = response.data.user;
      } catch (error) {
        console.error('Error fetching user profile:', error.response ? error.response.data : error.message);
      }
    },
    async logout() {
      try {
        await axiosClient.post('/logout');
        localStorage.removeItem('authToken');
        localStorage.removeItem('user');
        this.$router.push('/login');
        this.showModal = false;
      } catch (error) {
        console.error('Logout failed:', error.response ? error.response.data : error.message);
      }
    },
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    }
  },
};
</script>

<style scoped>
:root {
  --ig-separator: 219, 219, 219;
}

body {
  font-family: Roboto, serif;
}

.sidebar {
  width: 60px;  /* Adjusted width to allow more space for the images */
  background-color: #ffffff;
  position: fixed;
  height: 100vh;
  top: 0;
  left: 0;
  z-index: 999;
  border-right: 1px solid rgb(var(--ig-separator));
  display: flex;
  flex-direction: column;
  box-shadow: inset 1px 1px 3px orange;
  padding-top: 10px;
}



.sidebar-menu {
  font-size: 1rem;
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  align-items: center;
}

.sidebar-menu.bottom {
  flex-grow: 0;
  margin-bottom: 10px;
}

.sidebar-menu li {
  margin-bottom: 10px;
}

.sidebar-menu li a.nav-link {
  border-radius: 10px;
  padding: 10px;
  color: #333333;
  text-decoration: none;
  display: flex;
  justify-content: center;
  align-items: center;
}

.sidebar-menu li a.nav-link.active {
  font-weight: bold;
}

.sidebar-menu li a.nav-link .icon {
  width: 24px;
  height: 24px;
  transition: all 0.2s ease-in-out;
}

.sidebar-menu li a.nav-link:hover {
  background-color: #f5f5f5;
}

.sidebar-menu li a.nav-link:hover .icon {
  transform: scale(1.08);
}

.rounded-circle {
  border-radius: 50%;
  border: 2px solid orange;
  width: 36px;  /* Adjusted size for profile image */
  height: 36px;  /* Adjusted size for profile image */
  object-fit: cover;  /* Ensures the image covers the element without stretching */
}

@media (max-width: 768px) {
  .sidebar {

    flex-direction: row;
    height: auto;
    width: 100%;
    bottom: 0;
    top: auto;
    border-right: none;
    border-top: 1px solid rgb(var(--ig-separator));
    padding-top: 0;
  }
  .logo-wrapper {
    display: none;
  }
  .sidebar-menu {
    flex-direction: row;
    justify-content: space-around;
    padding: 0px;
  }

  .sidebar-menu li {
    margin-bottom: 0;
  }

  .sidebar-menu li a.nav-link {
    padding: 10px;
  }

  .sidebar-menu li a.nav-link .icon {
    width: 24px;
    height: 24px;
  }
}

</style>
