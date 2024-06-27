// router/index.js
import { createWebHistory, createRouter } from 'vue-router';
import UserDashboard from '../components/UserDashboard.vue';
import Recipes from '../views/Recipes/showRecipes.vue';
import showRecipe from '../views/Recipes/showVue.vue';
import Ingredient from '../views/Recipes/HighIngredient.vue';
import Profile from '../views/profile/profileVue.vue';
import modifyRecipe from '../views/Recipes/modifyRecipe.vue';
import Explore from '../views/searchVue.vue';
import recipeVideos from '../views/Recipes/recipeVideos.vue';
const routes = [
  { path: '/', component: () => import('../views/HomeView.vue') },
  { path: '/register', component: () => import('../views/RegisterView.vue') },
  { path: '/login', component: () => import('../views/loginView.vue') },
  { 
    path: '/create-recipe', 
    component: () => import('../views/Recipes/createRecipe.vue'), 
    meta: { requiresAuth: true } 
  },
  { path: '/user-dashboard', name: 'UserDashboard', component: UserDashboard, meta: { requiresAuth: true } },
  {path:'/recipes',name:'Recipes',component: Recipes},
  {path:'/ingredient',name:'Ingredient',component: Ingredient},

  {path:'/user/:userId',name:'Profile',component: Profile},
  {path:'/explore',name:'Explore',component: Explore},
  {path:'/recipe-videos',name:'recipeVideos',component: recipeVideos},


  {
    path: '/show-recipe/:recipeId',name: 'showRecipe',
    component: showRecipe
  },
  {
    path: '/modify-recipe/:recipeId',name: 'modifyRecipe',
    component: modifyRecipe
  },
  { path: '/:pathMatch(.*)', redirect: '/' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('authToken');
  let user = null;

  try {
    user = JSON.parse(localStorage.getItem('user'));
  } catch (error) {
    console.error('Error parsing user from localStorage', error);
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next('/login');
    } else {
      if (to.matched.some(record => record.meta.isAdmin)) {
        if (user && user.role === 'admin') {
          next();
        } else {
          next('/user-dashboard');
        }
      } else {
        next();
      }
    }
  } else if ((to.path === '/login' || to.path === '/register') && isAuthenticated) {
    next(user && user.role === 'admin' ? '/admin-dashboard' : '/user-dashboard');
  } else {
    next();
  }
});

export default router;
