<template>
  <div class="not-found">
    <div class="container text-center py-5">
      <h1 class="display-1 text-primary">404</h1>
      <h2 class="text-white mb-4">Az oldal nem található</h2>
      <p class="text-secondary mb-4">A keresett oldal nem létezik vagy eltávolításra került.</p>
      <router-link to="/" class="btn btn-primary btn-lg">Vissza a főoldalra</router-link>
    </div>
  </div>
</template>

<style scoped>
.not-found {
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>