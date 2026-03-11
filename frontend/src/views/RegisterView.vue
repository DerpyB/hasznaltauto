<template>
  <div>
    <section class="hero-wrap hero-wrap-2">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <h1 class="mb-0 bread text-white display-5">Regisztráció</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="form-card p-4 p-md-5 shadow rounded">
              
              <!-- Regisztrációs űrlap -->
              <div v-if="!showVerification">
                <div class="heading-section mb-5">
                  <span class="subheading">Fiók létrehozása</span>
                  <h2 class="mb-4 text-white">Regisztrálj most</h2>
                </div>

                <form @submit.prevent="handleRegister">
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label text-secondary">Név *</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.name" required/>
                    </div>

                    <div class="col-md-12">
                      <label class="form-label text-secondary">E-mail cím *</label>
                      <input type="email" class="form-control form-control-lg" v-model="form.email" required/>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label text-secondary">Jelszó *</label>
                      <input type="password" class="form-control form-control-lg" v-model="form.password" required minlength="8"/>
                      <small class="text-muted">Minimum 8 karakter</small>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label text-secondary">Jelszó megerősítése *</label>
                      <input type="password" class="form-control form-control-lg" v-model="form.password_confirmation" required/>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label text-secondary">Telefonszám</label>
                      <input type="tel" class="form-control form-control-lg" v-model="form.phone"/>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label text-secondary">Felhasználó típusa</label>
                      <select class="form-select form-select-lg" v-model="form.type">
                        <option value="private">Magánszemély</option>
                        <option value="dealer">Kereskedő</option>
                      </select>
                    </div>

                    <div class="col-md-12 pt-3">
                      <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Feldolgozás...</span>
                        <span v-else>Regisztráció</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Megerősítő kód -->
              <div v-else>
                <div class="heading-section mb-5">
                  <span class="subheading">E-mail megerősítés</span>
                  <h2 class="mb-4 text-white">Add meg a kódot</h2>
                  <p class="text-secondary">Küldtünk egy 6 számjegyű kódot az email címedre. A kód 30 percig érvényes.</p>
                </div>

                <form @submit.prevent="handleVerify">
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label text-secondary">Megerősítő kód *</label>
                      <input type="text" class="form-control form-control-lg text-center fs-2" 
                             v-model="verificationCode" maxlength="6" placeholder="000000" required/>
                    </div>

                    <div class="col-md-12 pt-3">
                      <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Feldolgozás...</span>
                        <span v-else>Megerősítés</span>
                      </button>
                    </div>

                    <div class="col-md-12 text-center pt-3">
                      <button type="button" class="btn btn-link text-secondary" @click="resendCode" :disabled="resendLoading">
                        {{ resendLoading ? 'Küldés...' : 'Kód újraküldése' }}
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Alert üzenetek -->
              <div v-if="error" class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
              </div>

              <div v-if="success" class="alert alert-success mt-4">
                <i class="fas fa-check-circle me-2"></i>
                {{ success }}
              </div>

              <div class="text-center mt-4">
                <p class="text-secondary mb-0">Már van fiókod? 
                  <router-link to="/login" class="text-primary">Jelentkezz be</router-link>
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
  name: "RegisterView",
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        type: 'private'
      },
      verificationCode: '',
      showVerification: false,
      loading: false,
      resendLoading: false,
      error: null,
      success: null
    }
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      this.error = null;
      
      try {
        await this.authStore.register(this.form);
        this.showVerification = true;
        this.success = 'Sikeres regisztráció! Kérjük, erősítsd meg az email címed.';
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba történt a regisztráció során.';
      } finally {
        this.loading = false;
      }
    },
    
    async handleVerify() {
      this.loading = true;
      this.error = null;
      
      try {
        await this.authStore.verifyEmail(this.verificationCode);
        this.success = 'Sikeres megerősítés! Most már bejelentkezhetsz.';
        setTimeout(() => {
          this.$router.push('/login');
        }, 2000);
      } catch (err) {
        this.error = err.response?.data?.message || 'Hibás vagy lejárt kód.';
      } finally {
        this.loading = false;
      }
    },
    
    async resendCode() {
      this.resendLoading = true;
      try {
        await this.authStore.resendVerification();
        this.success = 'Új kód elküldve!';
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba történt.';
      } finally {
        this.resendLoading = false;
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

.alert-success { 
  background-color: #064e3b; 
  border-color: #059669; 
  color: #6ee7b7; 
}

.alert-danger { 
  background-color: #7f1d1d; 
  border-color: #dc2626; 
  color: #fca5a5; 
}

.btn-link {
  text-decoration: none;
}

.btn-link:hover {
  color: #0066ff !important;
}
</style>