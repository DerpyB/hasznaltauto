<template>
  <div>
    <section class="hero-wrap hero-wrap-2">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <h1 class="mb-0 bread text-white display-5">E-mail megerősítés</h1>
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
                <div class="heading-section mb-5 text-center">
                  <span class="subheading">Aktiválás</span>
                  <h2 class="mb-4 text-white">Add meg a megerősítő kódot</h2>
                  <p class="text-secondary">Küldtünk egy 6 számjegyű kódot az email címedre. A kód 30 percig érvényes.</p>
                </div>

                <form @submit.prevent="handleVerify">
                  <div class="row g-3 justify-content-center">
                    <div class="col-md-8">
                      <label class="form-label text-secondary">Megerősítő kód *</label>
                      <input 
                        type="text" 
                        class="form-control form-control-lg text-center fs-2 letter-spacing-2" 
                        v-model="code" 
                        maxlength="6" 
                        placeholder="000000" 
                        required
                        autocomplete="off"
                      />
                    </div>

                    <div class="col-md-12 pt-4">
                      <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin me-2"></i>Feldolgozás...</span>
                        <span v-else>Megerősítés</span>
                      </button>
                    </div>

                    <div class="col-md-12 text-center pt-4">
                      <p class="text-secondary mb-2">Nem kaptad meg a kódot?</p>
                      <button 
                        type="button" 
                        class="btn btn-link text-primary text-decoration-none" 
                        @click="resendCode" 
                        :disabled="resendLoading || resendTimer > 0"
                      >
                        <span v-if="resendTimer > 0">Újraküldés {{ resendTimer }} mp múlva</span>
                        <span v-else-if="resendLoading">Küldés...</span>
                        <span v-else>Kód újraküldése</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div v-else class="text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-4"></i>
                <h3 class="text-white mb-3">Sikeres megerősítés!</h3>
                <p class="text-secondary">Az email címed megerősítve. Most már bejelentkezhetsz.</p>
                <router-link to="/login" class="btn btn-primary btn-lg mt-3 px-5">Bejelentkezés</router-link>
              </div>

              <div v-if="error" class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
              </div>

              <div v-if="info" class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i>
                {{ info }}
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
  name: "VerifyEmailView",
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      code: '',
      loading: false,
      resendLoading: false,
      resendTimer: 0,
      success: false,
      error: null,
      info: null
    }
  },
  mounted() {
    // Ellenőrizzük, van-e user_id a store-ban
    if (!this.authStore.verificationUserId) {
      // Ha nincs, irányítsuk a regisztrációra
      this.$router.push('/register');
    }
  },
  methods: {
    async handleVerify() {
      if (this.code.length !== 6) {
        this.error = 'A kód pontosan 6 számjegyből áll.';
        return;
      }
      
      this.loading = true;
      this.error = null;
      this.info = null;
      
      try {
        await this.authStore.verifyEmail(this.code);
        this.success = true;
      } catch (err) {
        this.error = err.response?.data?.message || 'Hibás vagy lejárt kód.';
      } finally {
        this.loading = false;
      }
    },
    
    async resendCode() {
      this.resendLoading = true;
      this.error = null;
      this.info = null;
      
      try {
        await this.authStore.resendVerification();
        this.info = 'Új kód elküldve! Kérjük, ellenőrizd az email fiókod.';
        this.startResendTimer();
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba történt a kód újraküldése során.';
      } finally {
        this.resendLoading = false;
      }
    },
    
    startResendTimer() {
      this.resendTimer = 60;
      const interval = setInterval(() => {
        this.resendTimer--;
        if (this.resendTimer <= 0) {
          clearInterval(interval);
        }
      }, 1000);
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

.letter-spacing-2 {
  letter-spacing: 0.5rem;
}

.alert-danger { 
  background-color: #7f1d1d; 
  border-color: #dc2626; 
  color: #fca5a5; 
}

.alert-info {
  background-color: #1e3a8a;
  border-color: #3b82f6;
  color: #93c5fd;
}

.text-success {
  color: #10b981 !important;
}

.btn-link {
  padding: 0;
  border: none;
  background: none;
}

.btn-link:hover {
  color: #0052cc !important;
}

.btn-link:disabled {
  color: #666 !important;
  cursor: not-allowed;
}
</style>