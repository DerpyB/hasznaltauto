<template>
  <div>
    <section class="hero-wrap hero-wrap-2">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <h1 class="mb-0 bread text-white display-5">Elfelejtett jelszó</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="form-card p-4 p-md-5 shadow rounded">
              
              <div v-if="!emailSent">
                <div class="heading-section mb-5">
                  <span class="subheading">Jelszó-visszaállítás</span>
                  <h2 class="mb-4 text-white">Add meg az email címed</h2>
                  <p class="text-secondary">Küldünk egy linket a jelszavad visszaállításához.</p>
                </div>

                <form @submit.prevent="handleSubmit">
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label text-secondary">E-mail cím *</label>
                      <input type="email" class="form-control form-control-lg" v-model="email" required/>
                    </div>

                    <div class="col-md-12 pt-3">
                      <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Küldés...</span>
                        <span v-else>Link küldése</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div v-else class="text-center py-5">
                <i class="fas fa-envelope fa-3x text-primary mb-4"></i>
                <h3 class="text-white mb-3">Ellenőrizd az email fiókod!</h3>
                <p class="text-secondary">Küldtünk egy jelszó-visszaállítási linket a következő címre: <strong class="text-white">{{ email }}</strong></p>
                <router-link to="/login" class="btn btn-outline-primary mt-3">Vissza a bejelentkezéshez</router-link>
              </div>

              <div v-if="error" class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth';

export default {
  name: "ForgotPasswordView",
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      email: '',
      loading: false,
      emailSent: false,
      error: null
    }
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.error = null;
      
      try {
        await this.authStore.forgotPassword(this.email);
        this.emailSent = true;
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba történt. Kérjük, próbáld újra.';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
.hero-wrap-2 { 
  height: 300px; 
  position: relative; 
  background: linear-gradient(135deg, #0066ff 0%, #00ccff 100%);
  background-size: cover; 
  background-position: center; 
}

.hero-wrap-2 .overlay { 
  position: absolute; 
  top: 0; 
  left: 0; 
  right: 0; 
  bottom: 0; 
  background: rgba(0,0,0,0.3); 
}

.form-card {
  background: #111;
  border: 1px solid #222;
  border-radius: 10px;
}

.heading-section .subheading { 
  font-size: 14px; 
  display: block; 
  margin-bottom: 5px; 
  color: #0066ff; 
  text-transform: uppercase; 
  letter-spacing: 2px; 
  font-weight: 600; 
}

.heading-section h2 { 
  font-size: 32px; 
  font-weight: 700; 
  color: #fff; 
}

.alert-danger { 
  background-color: #7f1d1d; 
  border-color: #dc2626; 
  color: #fca5a5; 
}
</style>