import { createWebHistory, createRouter } from 'vue-router'
import { useAuthStore } from '../stores'

import { HomeView, LoginView } from '../views'

const routes = [
  { path: '/',      name: 'home',  component: HomeView , meta : { title: 'Dashboard',  requiresAuth : true  }},
  { path: '/login', name: 'login', component: LoginView, meta : { title: 'Login Page', guest : true         }},
]

const router = createRouter({
  history: createWebHistory(),
  routes,

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
        return savedPosition;
    } else {
        return { top: 0, behavior: 'smooth' };
    }
  }
})


const DEFAULT_TITLE = '404'
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || DEFAULT_TITLE;
  const auth = useAuthStore();

  if(to.matched.some((record) => record.meta.requiresAuth)){    
    if(!auth.getAuthStatus){
        next({name:'login'});
    }else{
        next();
    }
  }else if(to.matched.some((record) => record.meta.guest)){    
      if(auth.getAuthStatus){
          router.push({name : 'home' });
          next({name:'home'});
      }else{
          next();
      }
  }else{
      next();
  }
})

export default router