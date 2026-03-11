<template>
  <div>
    <section class="hero-wrap hero-wrap-2">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <h1 class="mb-0 bread text-white display-5">Bejelentkezés</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="form-card p-4 p-md-5 shadow rounded">
              <div class="heading-section mb-5">
                <span class="subheading">Üdvözöljük</span>
                <h2 class="mb-4 text-white">Jelentkezz be</h2>
              </div>

              <form @submit.prevent="handleLogin">
                <div class="row g-3">
                  <div class="col-md-12">
                    <label class="form-label text-secondary">E-mail cím *</label>
                    <input type="email" class="form-control form-control-lg" v-model="form.email" required/>
                  </div>

                  <div class="col-md-12">
                    <label class="form-label text-secondary">Jelszó *</label>
                    <input type="password" class="form-control form-control-lg" v-model="form.password" required/>
                  </div>

                  <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" v-model="form.remember" id="remember">
                      <label class="form-check-label text-secondary" for="remember">
                        Emlékezz rám
                      </label>
                    </div>
                    <router-link to="/forgot-password" class="text-primary text-decoration-none">
                      Elfelejtett jelszó?
                    </router-link>
                  </div>

                  <div class="col-md-12 pt-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                      <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Bejelentkezés...</span>
                      <span v-else>Bejelentkezés</span>
                    </button>
                  </div>
                </div>
              </form>

              <div v-if="error" class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
              </div>

              <div class="text-center mt-4">
                <p class="text-secondary mb-0">Még nincs fiókod? 
                  <router-link to="/register" class="text-primary">Regisztrálj most</router-link>
                </p>
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
  name: "LoginView",
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      },
      loading: false,
      error: null
    }
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await this.authStore.login(this.form);
        
        // Ha nincs megerősítve az email
        if (response.data.needs_verification) {
          this.authStore.verificationUserId = response.data.user_id;
          this.$router.push('/verify-email');
          return;
        }
        
        this.$router.push('/');
      } catch (err) {
        this.error = err.response?.data?.message || 'Hibás email vagy jelszó.';
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

.form-check-input {
  background-color: #1a1a1a;
  border-color: #333;
}

.form-check-input:checked {
  background-color: #0066ff;
  border-color: #0066ff;
}
</style>