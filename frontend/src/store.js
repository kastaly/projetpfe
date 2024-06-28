import { createStore } from 'vuex';
import axiosClient from './api/axios'; // Assuming this is correctly configured

export default createStore({
  state: {
    isLoggedIn: !!localStorage.getItem('authToken'),
    user: JSON.parse(localStorage.getItem('user')) || null,
  },
  
  mutations: {
    login(state) {
      state.isLoggedIn = true;
    },
    logout(state) {
      state.isLoggedIn = false;
      state.user = null; // Clear user data upon logout
      window.location.href = '/login';
    },
      setUser(state, user) {
        state.user = user;
        // Assuming profile_picture_url is included in user data
        state.user.profile_picture_url = user.profile_picture_url;
      },
    
    
    
  },
  
 actions: {
  async login({ commit }, credentials) {
    try {
      const response = await axiosClient.post('/login', credentials);
      const user = response.data.user;
      const authToken = response.data.token;

      localStorage.setItem('authToken', authToken);
      localStorage.setItem('user', JSON.stringify(user));

      commit('login');
      commit('setUser', user);

      return user;
    } catch (error) {
      console.error('Error during login:', error);
      throw error;
    }
  },
  logout({ commit }) {
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
    commit('logout');
    // Redirect to login page using Vue Router if available
    router.push('/login');
    // or full page reload
    window.location.href = '/login';
  },
},
})
