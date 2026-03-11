<template>
  <div>
    <section class="hero-wrap hero-wrap-2" style="background-image: url('https://wallpapers.com/images/hd/black-b-m-w-e46-coupe-urban-backdrop-v5k8l4yy9p2eu1cd.jpg?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center 70%; height: 850px;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <p class="breadcrumbs">
              <router-link to="/">Kezdőlap</router-link>
              <span><i class="fas fa-chevron-right mx-2"></i></span>
              <span>Hirdetés feladása</span>
            </p>
            <h1 class="mb-0 bread text-white display-5">Adja fel autóját</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="form-card p-4 p-md-5 shadow rounded">
              <div class="heading-section mb-5">
                <span class="subheading">Hirdetés feladása</span>
                <h2 class="mb-4 text-white">Töltse ki az adatokat</h2>
                <p class="text-secondary">Minél részletesebben adja meg az autó adatait, annál hamarabb talál vevőt.</p>
              </div>

              <form @submit.prevent="submit">
                <div class="row g-4">
                  <div class="col-md-12">
                    <label class="form-label fw-medium text-secondary">Hirdetés címe *</label>
                    <input type="text" class="form-control form-control-lg" v-model="car.title" placeholder="pl. Kitűnő állapotú BMW 320d" required/>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Márka *</label>
                    <input type="text" class="form-control form-control-lg" v-model="car.brand" placeholder="pl. BMW" required/>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Modell *</label>
                    <input type="text" class="form-control form-control-lg" v-model="car.model" placeholder="pl. 320d" required/>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Évjárat *</label>
                    <input type="number" class="form-control form-control-lg" v-model="car.year" placeholder="pl. 2020" required/>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Ár (Ft) *</label>
                    <input type="number" class="form-control form-control-lg" v-model="car.price" placeholder="pl. 5000000" required/>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Futásteljesítmény (km) *</label>
                    <input type="number" class="form-control form-control-lg" v-model="car.mileage" placeholder="pl. 50000" required/>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Motorméret (cm³)</label>
                    <input type="number" class="form-control form-control-lg" v-model="car.engine_size" placeholder="pl. 1995"/>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Üzemanyag típusa *</label>
                    <select class="form-select form-select-lg" v-model="car.fuel_type" required>
                      <option value="">Válasszon...</option>
                      <option>Benzin</option>
                      <option>Dísel</option>
                      <option>Elektromos</option>
                      <option>Hibrid</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Váltó típusa *</label>
                    <select class="form-select form-select-lg" v-model="car.transmission" required>
                      <option value="">Válasszon...</option>
                      <option>Manuális</option>
                      <option>Automata</option>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Karosszéria</label>
                    <select class="form-select form-select-lg" v-model="car.body_type">
                      <option value="">Válasszon...</option>
                      <option>Szedán</option>
                      <option>Kombi</option>
                      <option>Kupé</option>
                      <option>SUV</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Szín</label>
                    <input type="text" class="form-control form-control-lg" v-model="car.color" placeholder="pl. Fekete metál"/>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Teljesítmény (LE)</label>
                    <input type="number" class="form-control form-control-lg" v-model="car.horsepower" placeholder="pl. 150"/>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-secondary">Ajtók száma</label>
                    <select class="form-select form-select-lg" v-model="car.doors">
                      <option value="">Válasszon...</option>
                      <option>3</option>
                      <option>5</option>
                    </select>
                  </div>

                  <div class="col-md-12">
                    <label class="form-label fw-medium text-secondary">Leírás</label>
                    <textarea class="form-control" rows="5" v-model="car.description" placeholder="Írja le részletesen az autó állapotát..."></textarea>
                  </div>

                  <div class="col-md-12 pt-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3" :disabled="submitting">
                      <span v-if="submitting"><i class="fas fa-spinner fa-spin me-2"></i>Feldolgozás...</span>
                      <span v-else><i class="fas fa-paper-plane me-2"></i>Hirdetés feladása</span>
                    </button>
                  </div>
                </div>
              </form>

              <div v-if="submitted" class="alert alert-success mt-4 mb-0">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Siker!</strong> A hirdetését rögzítettük. <router-link to="/cars" class="alert-link">Megtekintés az autók között</router-link>
              </div>

              <div v-if="error" class="alert alert-danger mt-4 mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Hiba!</strong> {{ error }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  name: "SellCarView",
  data() {
    return {
      submitting: false,
      submitted: false,
      error: null,
      car: {
        user_id: 1,
        title: "",
        brand: "",
        model: "",
        year: "",
        price: "",
        mileage: "",
        engine_size: "",
        horsepower: "",
        fuel_type: "",
        transmission: "",
        body_type: "",
        color: "",
        doors: "",
        description: ""
      }
    }
  },
  methods: {
    async submit() {
      this.submitting = true;
      this.error = null;
      
      try {
        const createdCar = await api.createCar(this.car);
        
        await api.createListing({
          seller_id: this.car.user_id,
          listable_id: createdCar.id,
          listable_type: 'car',
          price: this.car.price,
          status: 'active'
        });
        
        this.submitted = true;
        this.resetForm();
        window.scrollTo(0, 0);
      } catch (err) {
        console.error('Hiba a mentéskor:', err);
        this.error = 'Nem sikerült menteni a hirdetést. Próbálja újra.';
      } finally {
        this.submitting = false;
      }
    },
    resetForm() {
      this.car = {
        user_id: 1,
        title: "", brand: "", model: "", year: "", price: "",
        mileage: "", engine_size: "", horsepower: "", fuel_type: "",
        transmission: "", body_type: "", color: "", doors: "", description: ""
      };
    }
  }
}
</script>

<style scoped>
.hero-wrap-2 { 
  height: 400px; 
  position: relative; 
  background-size: cover; 
  background-position: center; 
}

.hero-wrap-2 .overlay { 
  position: absolute; 
  top: 0; 
  left: 0; 
  right: 0; 
  bottom: 0; 
  background: rgba(0,0,0,0.5); 
}

.breadcrumbs { 
  font-size: 16px; 
  margin-bottom: 20px; 
}

.breadcrumbs a { 
  color: rgba(255,255,255,0.8); 
  text-decoration: none; 
}

.breadcrumbs a:hover {
  color: #0066ff;
}

.bread { 
  font-weight: 700; 
}

.ftco-section {
  background: #0a0a0a;
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
  font-size: 40px; 
  font-weight: 700; 
  color: #fff; 
}

.form-control, .form-select {
  background: #1a1a1a;
  color: #fff;
  border: 1px solid #333;
  border-radius: 8px;
}

.form-control::placeholder {
  color: #666;
}

.form-control:focus, .form-select:focus { 
  background: #1a1a1a;
  color: #fff;
  box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.15); 
  border-color: #0066ff !important; 
}

.form-select option {
  background: #1a1a1a;
  color: #fff;
}

.alert-success { 
  background-color: #064e3b; 
  border-color: #059669; 
  color: #6ee7b7; 
}

.alert-success .alert-link {
  color: #34d399;
  text-decoration: underline;
}

.alert-danger { 
  background-color: #7f1d1d; 
  border-color: #dc2626; 
  color: #fca5a5; 
}

.btn-primary { 
  background: #0066ff !important; 
  border-color: #0066ff !important; 
  border-radius: 8px;
}

.btn-primary:hover:not(:disabled) { 
  background: #0052cc !important; 
  border-color: #0052cc !important; 
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.text-secondary {
  color: #888 !important;
}

@media (max-width: 768px) { 
  .hero-wrap-2 { height: 300px; } 
  .heading-section h2 { font-size: 28px; } 
}
</style>