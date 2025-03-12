<script setup>
import AuthenticateLayout from './layouts/AuthenticateLayout.vue';
import GuestLayout from './layouts/GuestLayout.vue'
import { Notifications } from './components';
import { useAuthStore } from './stores';

const auth = useAuthStore();
</script>

<template>
  <div>
    <AuthenticateLayout v-if="auth.isLoggedIn">
        <Notifications/>
      <RouterView/>
    </AuthenticateLayout>

    <GuestLayout v-else>
      <RouterView/>
    </GuestLayout>
  </div>
</template>

<style scoped>
/* Add your custom styles here */
</style>
