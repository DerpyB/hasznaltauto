<template>
  <div class="app-wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top">
      <div class="container">
        <!-- Logo -->
        <router-link class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2" to="/">
          <i class="fas fa-car-side"></i>
          <span>DriveMarket<span class="text-info">Project</span></span>
        </router-link>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <!-- Bal oldali menü -->
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link fw-medium" to="/" exact-active-class="active">Kezdőlap</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link fw-medium" to="/cars" active-class="active">Autók</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link fw-medium" to="/sell" active-class="active">Hirdetés feladása</router-link>
            </li>
          </ul>

          <!-- Jobb oldali menü -->
          <ul class="navbar-nav ms-auto align-items-center">
            <!-- NEM BEJELENTKEZETT felhasználóknak -->
            <template v-if="!isLoggedIn">
              <li class="nav-item">
                <router-link to="/login" class="btn btn-outline-light px-4 rounded-pill me-2">
                  <i class="fas fa-sign-in-alt me-2"></i>Bejelentkezés
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/register" class="btn btn-primary px-4 rounded-pill">
                  <i class="fas fa-user-plus me-2"></i>Regisztráció
                </router-link>
              </li>
            </template>
            
            <!-- BEJELENTKEZETT felhasználóknak -->
            <template v-else>
              <li class="nav-item dropdown">
                <a 
                  class="nav-link dropdown-toggle d-flex align-items-center gap-2" 
                  href="#" 
                  id="userDropdown" 
                  role="button" 
                  data-bs-toggle="dropdown" 
                  aria-expanded="false"
                >
                  <div class="user-avatar">
                    <i class="fas fa-user"></i>
                  </div>
                  <span class="d-none d-lg-inline">{{ userName }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    <router-link class="dropdown-item" to="/change-password">
                      <i class="fas fa-key me-2"></i>Jelszó módosítása
                    </router-link>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item text-danger" href="#" @click.prevent="logout">
                      <i class="fas fa-sign-out-alt me-2"></i>Kijelentkezés
                    </a>
                  </li>
                </ul>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>

    <main>
      <router-view />
    </main>

    <footer class="ftco-footer ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">
                <span>DriveMarket<span class="text-info">Project</span></span>
              </h2>
              <p>Találja meg álmai autóját gyorsan és egyszerűen. Több ezer ellenőrzött hirdetés egy helyen.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><i class="fab fa-facebook"></i></a></li>
                <li class="ftco-animate"><a href="#"><i class="fab fa-instagram"></i></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Információk</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Rólunk</a></li>
                <li><a href="#" class="py-2 d-block">Szolgáltatások</a></li>
                <li><a href="#" class="py-2 d-block">Kapcsolat</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Segítség</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">GYIK</a></li>
                <li><a href="#" class="py-2 d-block">Hirdetésfeladás</a></li>
                <li><a href="#" class="py-2 d-block">Adatvédelem</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Kapcsolat</h2>
              <div class="block-23 mb-3">
                <ul>
                  <li><i class="fas fa-map-marker-alt me-2"></i><span class="text">Eger, Magyarország</span></li>
                  <li><i class="fas fa-phone me-2"></i><span class="text">+36 70 532 4985</span></li>
                  <li><i class="fas fa-envelope me-2"></i><span class="text">drivemarketproject@gmail.com</span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <p>&copy; 2026 DriveMarketProject. Minden jog fenntartva.</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { useAuthStore } from './stores/auth';
import { computed } from 'vue';

export default {
  name: 'App',
  setup() {
    const authStore = useAuthStore();
    
    const isLoggedIn = computed(() => authStore.isLoggedIn);
    const userName = computed(() => authStore.user?.name || 'Felhasználó');
    
    const logout = async () => {
      await authStore.logout();
      window.location.href = '/';
    };
    
    return {
      isLoggedIn,
      userName,
      logout
    };
  }
}
</script>

<style>
:root {
  --bg-primary: #0a0a0a;
  --bg-secondary: #111111;
  --bg-card: #1a1a1a;
  --bg-input: #1a1a1a;
  --primary: #0066ff;
  --primary-hover: #0052cc;
  --info: #00ccff;
  --text-primary: #ffffff;
  --text-secondary: #a0a0a0;
  --border: #333333;
}

body {
  background: var(--bg-primary);
  color: var(--text-primary);
  font-family: 'Poppins', sans-serif;
}

.app-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: var(--bg-primary);
}

/* Navbar - sötét téma, border eltávolítva */
.navbar {
  padding: 1rem 0;
  background: #111111 !important;
  border: none !important;
  box-shadow: none !important;
  margin-bottom: 0 !important;
}

.navbar-dark-custom {
  background: #111111 !important;
}

.navbar-brand {
  font-size: 1.5rem;
}

.nav-link {
  color: #a0a0a0 !important;
  font-size: 0.95rem;
  padding: 0.5rem 1rem !important;
  transition: color 0.3s;
}

.nav-link:hover,
.nav-link.active {
  color: #0066ff !important;
}

/* Toggler ikon színe sötét témához */
.navbar-toggler {
  border-color: #333;
}

.navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Felhasználói menü */
.user-avatar {
  width: 32px;
  height: 32px;
  background: #0066ff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 14px;
}

.dropdown-menu-dark {
  background: #1a1a1a;
  border: 1px solid #333;
}

.dropdown-menu-dark .dropdown-item {
  color: #a0a0a0;
}

.dropdown-menu-dark .dropdown-item:hover {
  background: #0066ff;
  color: white;
}

.dropdown-menu-dark .dropdown-divider {
  border-color: #333;
}

/* ===================================================================
   GLOBÁLIS INPUT STÍLUSOK - Minden oldalon egységes
   =================================================================== */

/* Inputok, selectek, textarea-k alapstílusa */
.form-control,
.form-select,
textarea.form-control {
  background-color: #1a1a1a !important;
  color: #ffffff !important;
  border: 1px solid #333333 !important;
  border-radius: 8px !important;
  transition: all 0.3s ease;
}

/* Placeholder szín */
.form-control::placeholder,
textarea.form-control::placeholder {
  color: #666666 !important;
  opacity: 1;
}

/* Focus állapot */
.form-control:focus,
.form-select:focus,
textarea.form-control:focus {
  background-color: #1a1a1a !important;
  color: #ffffff !important;
  border-color: #0066ff !important;
  box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.25) !important;
  outline: none !important;
}

/* Select dropdown nyíl színe - FONTOS: először töröljük az összes background-image-t */
.form-select {
  background-image: none !important;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  appearance: none !important;
  padding-right: 2.5rem !important;
  position: relative;
}

/* Egyedi nyíl ikon ::after pseudo-elementtel */
.form-select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
  background-repeat: no-repeat !important;
  background-position: right 0.75rem center !important;
  background-size: 16px 12px !important;
}

/* Select option-ök (böngésző natív dropdown) */
.form-select option {
  background-color: #1a1a1a;
  color: #ffffff;
}

/* Disabled állapot */
.form-control:disabled,
.form-select:disabled {
  background-color: #222222 !important;
  color: #666666 !important;
  border-color: #333333 !important;
  opacity: 0.7;
}

/* Readonly állapot */
.form-control[readonly] {
  background-color: #222222 !important;
  color: #ffffff !important;
}

/* Input csoportok (input-group) */
.input-group-text {
  background-color: #222222 !important;
  color: #ffffff !important;
  border: 1px solid #333333 !important;
}

/* Textarea speciális */
textarea.form-control {
  resize: vertical;
  min-height: 120px;
}

/* Különböző input típusok */
input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
input[type="date"] {
  background-color: #1a1a1a !important;
  color: #ffffff !important;
}

/* =================================================================== */

/* Buttons */
.btn-primary {
  background: var(--primary) !important;
  border-color: var(--primary) !important;
}

.btn-primary:hover {
  background: var(--primary-hover) !important;
  border-color: var(--primary-hover) !important;
}

.btn-outline-light {
  color: #ffffff !important;
  border-color: #ffffff !important;
}

.btn-outline-light:hover {
  background: #ffffff !important;
  color: #000000 !important;
}

.text-primary {
  color: var(--primary) !important;
}

.text-warning {
  color: #ffc107 !important;
}

.text-info {
  color: var(--info) !important;
}

.text-danger {
  color: #ef4444 !important;
}

main {
  padding-top: 76px;
  flex: 1;
  margin-top: -1px;
}

@media (max-width: 991px) {
  main {
    padding-top: 60px;
  }
  
  .navbar-collapse {
    background: #111;
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
  }
  
  .dropdown-menu {
    background: #1a1a1a !important;
  }
}

/* Hero szekció margin eltávolítása */
.hero-wrap {
  margin-top: 0 !important;
}

/* Footer */
.ftco-footer {
  background: #111111;
  border-top: 1px solid #222222;
  padding: 5em 0;
  color: #a0a0a0;
}

.ftco-footer a {
  color: #a0a0a0;
  text-decoration: none;
  transition: color 0.3s;
}

.ftco-footer a:hover {
  color: #0066ff;
}

.ftco-footer .ftco-heading-2 {
  color: #fff;
  font-size: 1.2rem;
  margin-bottom: 20px;
  font-weight: 600;
}

.ftco-footer-social li {
  display: inline-block;
  margin-right: 15px;
}

.ftco-footer-social li a {
  height: 40px;
  width: 40px;
  display: block;
  background: #1a1a1a;
  border-radius: 50%;
  position: relative;
  text-align: center;
  line-height: 40px;
  transition: all 0.3s;
  border: 1px solid #333;
}

.ftco-footer-social li a:hover {
  background: #0066ff;
  border-color: #0066ff;
  color: #fff;
}

.block-23 ul {
  padding: 0;
  list-style: none;
}

.block-23 ul li {
  margin-bottom: 10px;
  display: flex;
  align-items: flex-start;
}

.block-23 ul li i {
  color: #0066ff;
  margin-top: 5px;
}
</style>