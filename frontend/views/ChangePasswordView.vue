<!-- Ez a védett oldal -->

<template>
  <div>
    <section class="hero-wrap hero-wrap-2">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <h1 class="mb-0 bread text-white display-5">Jelszóváltoztatás</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="form-card p-4 p-md-5 shadow rounded">
              
              <div v-if="!success">
                <div class="heading-section mb-5">
                  <span class="subheading">Biztonság</span>
                  <h2 class="mb-4 text-white">Változtasd meg a jelszavad</h2>
                </div>

                <form @submit.prevent="handleSubmit">
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label text-secondary">Jelenlegi jelszó *</label>
                      <input type="password" class="form-control form-control-lg" v-model="form.current_password" required/>
                    </div>

                    <div class="col-md-12">
                      <label class="form-label text-secondary">Új jelszó *</label>
                      <input type="password" class="form-control form-control-lg" v-model="form.password" required minlength="8"/>
                    </div>

                    <div class="col-md-12">
                      <label class="form-label text-secondary">Új jelszó megerősítése *</label>
                      <input type="password" class="form-control form-control-lg" v-model="form.password_confirmation" required/>
                    </div>

                    <div class="col-md-12 pt-3">
                      <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Feldolgozás...</span>
                        <span v-else>Jelszó módosítása</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div v-else class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-4"></i>
                <h3 class="text-white mb-3">Sikeres módosítás!</h3>
                <p class="text-secondary">Kérjük, jelentkezz be újra az új jelszavaddal.</p>
                <router-link to="/login" class="btn btn-primary mt-3">Bejelentkezés</router-link>
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
  name: "ChangePasswordView",
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      form: {
        current_password: '',
        password: '',
        password_confirmation: ''
      },
      loading: false,
      success: false,
      error: null
    }
  },
  methods: {
    async handleSubmit() {
      if (this.form.password !== this.form.password_confirmation) {
        this.error = 'Az új jelszavak nem egyeznek.';
        return;
      }
      
      this.loading = true;
      this.error = null;
      
      try {
        await this.authStore.changePassword({
          current_password: this.form.current_password,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation
        });
        this.success = true;
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba történt a jelszó módosítása során.';
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

.text-success {
  color: #10b981 !important;
}
</style>