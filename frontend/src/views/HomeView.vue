<template>
  <div>
    <!-- Hero Section -->
    <section class="hero-wrap" style="background-image: url('https://www.topgear.com/sites/default/files/2024/12/Original-11787-skoda-octavia-heritage-08.jpg'); background-size: cover; background-position: center 120%; height: 850px;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center pt-5 mt-md-5">
            <h1 class="mb-4 text-white display-4 fw-bold">Gyors & Egyszerű Módja Autó Találásnak</h1>
            <p class="text-secondary mb-5 fs-5">Találja meg álmai autóját több ezer ellenőrzött hirdetés közül</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Search Form Section -->
    <section class="ftco-search">
      <div class="container">
        <div class="row">
          <div class="col-md-12 search-wrap">
            <div class="search-property p-4 p-md-5 shadow rounded">
              <h3 class="mb-4 text-white">Keresés</h3>
              <form @submit.prevent="search">
                <div class="row g-3">
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Márka</label>
                    <input type="text" class="form-control form-control-lg border-0" placeholder="pl. BMW" v-model="filters.brand"/>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Modell</label>
                    <input type="text" class="form-control form-control-lg border-0" placeholder="pl. 320d" v-model="filters.model"/>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label text-secondary fw-medium">Max ár</label>
                    <input type="number" class="form-control form-control-lg border-0" placeholder="Ft" v-model="filters.maxPrice"/>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label text-secondary fw-medium">Évjárat</label>
                    <input type="number" class="form-control form-control-lg border-0" placeholder="Min." v-model="filters.minYear"/>
                  </div>
                  <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded">
                      <i class="fas fa-search me-2"></i>Keresés
                    </button>
                  </div>
                </div>
                
                <div class="row g-3 mt-2">
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Üzemanyag</label>
                    <select class="form-select form-select-lg border-0" v-model="filters.fuelType">
                      <option value="">Bármely</option>
                      <option>Benzin</option>
                      <option>Dísel</option>
                      <option>Elektromos</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Max km</label>
                    <input type="number" class="form-control form-control-lg border-0" placeholder="km" v-model="filters.maxMileage"/>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Szín</label>
                    <input type="text" class="form-control form-control-lg border-0" placeholder="pl. Fekete" v-model="filters.color"/>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Motorméret</label>
                    <input type="number" class="form-control form-control-lg border-0" placeholder="cm³" v-model="filters.engineSize"/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services/Steps Section -->
    <section class="ftco-section ftco-services-2 py-5">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center heading-section">
            <span class="subheading">Hogyan működik?</span>
            <h2 class="mb-3 text-white">Vásárlás 3 Egyszerű Lépésben</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 d-flex align-self-stretch" v-for="(step, index) in steps" :key="index">
            <div class="media block-6 services services-2">
              <div class="media-body py-md-4 text-center">
                <div class="icon d-flex align-items-center justify-content-center mb-4">
                  <span>{{ index + 1 }}</span>
                </div>
                <h3 class="text-white">{{ step.title }}</h3>
                <p class="text-secondary">{{ step.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Cars Section -->
    <section class="ftco-section py-5">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center heading-section">
            <span class="subheading">Kiemelt ajánlatok</span>
            <h2 class="mb-3 text-white">Frissen feladott autók</h2>
          </div>
        </div>
        
        <div class="row" v-if="loading">
          <div class="col-md-12 text-center py-5">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2 text-secondary">Autók betöltése...</p>
          </div>
        </div>

        <div class="row" v-else-if="featuredCars.length > 0">
          <div class="col-md-4 mb-4" v-for="car in featuredCars" :key="car.id">
            <div class="car-wrap rounded shadow-sm overflow-hidden">
              <div class="img position-relative" :style="{ background: getCarGradient(car.id) }">
                <div class="price-wrap position-absolute bottom-0 end-0 bg-primary text-white px-3 py-2 rounded-top-start">
                  <p class="mb-0 fw-bold">{{ formatPrice(car.price) }} Ft</p>
                </div>
              </div>
              <div class="text p-4">
                <h2 class="mb-2">
                  <router-link to="/cars" class="text-white text-decoration-none">{{ car.brand }} {{ car.model }}</router-link>
                </h2>
                <div class="d-flex mb-3">
                  <span class="cat me-3 text-secondary"><i class="fas fa-calendar me-1 text-primary"></i> {{ car.year }}</span>
                  <span class="cat me-3 text-secondary"><i class="fas fa-tachometer-alt me-1 text-primary"></i> {{ formatNumber(car.mileage) }} km</span>
                  <span class="cat text-secondary"><i class="fas fa-gas-pump me-1 text-primary"></i> {{ car.fuel_type }}</span>
                </div>
                <p class="mb-0">
                  <router-link to="/cars" class="btn btn-outline-primary py-2 px-4 me-2">Részletek</router-link>
                  <button @click="contactSeller(car)" class="btn btn-primary py-2 px-4">Kapcsolat</button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="row" v-else>
          <div class="col-md-12 text-center py-5">
            <p class="text-secondary">Jelenleg nincsenek kiemelt hirdetések. <router-link to="/sell" class="text-primary">Adjon fel Ön egyet!</router-link></p>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-md-12 text-center">
            <router-link to="/cars" class="btn btn-outline-primary btn-lg px-5 rounded-pill">Összes autó megtekintése</router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="ftco-section py-5 bg-primary">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-10 text-center text-white">
            <h2 class="mb-4">Készen áll, hogy megtalálja álmai autóját?</h2>
            <div class="d-flex justify-content-center gap-3">
              <router-link to="/cars" class="btn btn-dark btn-lg px-5 rounded-pill fw-bold">Autók böngészése</router-link>
              <router-link to="/sell" class="btn btn-outline-light btn-lg px-5 rounded-pill fw-bold">Hirdetés feladása</router-link>
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
  name: "HomeView",
  data() {
    return {
      filters: {
        brand: "", model: "", maxPrice: "", minYear: "",
        maxMileage: "", engineSize: "", fuelType: "", color: ""
      },
      featuredCars: [],
      carCount: 0,
      loading: false,
      steps: [
        { title: "Válasszon Helyszínt", desc: "Válogasson több ezer hirdetés közül országszerte." },
        { title: "Válassza ki a Legjobb Ajánlatot", desc: "Hasonlítson össze árakat és paramétereket." },
        { title: "Lépjen Kapcsolatba", desc: "Direkt kapcsolatfelvétel a hirdetővel." }
      ]
    }
  },
  async mounted() {
    await this.loadFeaturedCars();
  },
  methods: {
    async loadFeaturedCars() {
      this.loading = true;
      try {
        const cars = await api.getCars();
        this.featuredCars = cars.slice(-3).reverse();
        this.carCount = cars.length;
        
        if (this.featuredCars.length === 0) {
          this.featuredCars = [
            { id: 1, brand: "BMW", model: "320d", price: 4500000, year: 2019, mileage: 85000, fuel_type: "Dísel" },
            { id: 2, brand: "Audi", model: "A4", price: 3800000, year: 2018, mileage: 120000, fuel_type: "Benzin" },
            { id: 3, brand: "Mercedes", model: "C220", price: 5200000, year: 2020, mileage: 45000, fuel_type: "Dísel" }
          ];
        }
      } catch (error) {
        console.error('Hiba az autók betöltésekor:', error);
        this.featuredCars = [
          { id: 1, brand: "BMW", model: "320d", price: 4500000, year: 2019, mileage: 85000, fuel_type: "Dísel" },
          { id: 2, brand: "Audi", model: "A4", price: 3800000, year: 2018, mileage: 120000, fuel_type: "Benzin" },
          { id: 3, brand: "Mercedes", model: "C220", price: 5200000, year: 2020, mileage: 45000, fuel_type: "Dísel" }
        ];
      } finally {
        this.loading = false;
      }
    },
    search() {
      this.$router.push({ path: "/cars", query: { ...this.filters } });
    },
    formatPrice(price) {
      return new Intl.NumberFormat('hu-HU').format(price);
    },
    formatNumber(num) {
      return new Intl.NumberFormat('hu-HU').format(num);
    },
    getCarGradient(id) {
      const gradients = [
        'linear-gradient(135deg, #0066ff 0%, #00ccff 100%)',
        'linear-gradient(135deg, #0099ff 0%, #6600ff 100%)',
        'linear-gradient(135deg, #0033cc 0%, #0066ff 100%)'
      ];
      return gradients[id % gradients.length];
    },
    contactSeller(car) {
      alert(`Kapcsolatfelvétel: ${car.brand} ${car.model}\nTelefon: +36 30 123 4567`);
    }
  }
}
</script>

<style scoped>
.hero-wrap { 
  width: 100%; 
  height: 850px; 
  position: relative; 
  background-size: cover; 
  background-position: center 30%; 
  display: flex; 
  align-items: center; 
}

.hero-wrap .overlay { 
  position: absolute; 
  top: 0; 
  left: 0; 
  right: 0; 
  bottom: 0; 
  opacity: .6; 
  background: #000; 
}

.ftco-search { 
  position: relative; 
  margin-top: -500px; 
  z-index: 10; 
  padding-bottom: 50px; 
}

.search-property { 
  border-radius: 10px; 
  background: #1a1a1a;
  border: 1px solid #333;
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

.ftco-services-2 {
  background: #0a0a0a;
}

.services { 
  transition: all 0.3s ease; 
  background: #111;
  border-radius: 10px;
  padding: 30px;
}

.services:hover { 
  transform: translateY(-5px); 
  box-shadow: 0 10px 30px rgba(0,102,255,0.2) !important; 
  border: 1px solid #0066ff;
}

.icon { 
  width: 100px; 
  height: 100px; 
  background: rgba(0, 102, 255, 0.2); 
  border-radius: 50%; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  margin: 0 auto; 
}

.icon span { 
  font-size: 40px; 
  color: #0066ff; 
  font-weight: bold; 
}

.ftco-section {
  background: #0a0a0a;
}

.car-wrap { 
  transition: all 0.3s ease; 
  background: #111;
  border: 1px solid #222;
}

.car-wrap:hover { 
  transform: translateY(-5px); 
  box-shadow: 0 15px 40px rgba(0,102,255,0.3) !important; 
  border-color: #0066ff;
}

.car-wrap .text h2 a:hover { 
  color: #0066ff !important; 
}

.price-wrap {
  font-size: 16px;
  background: #0066ff !important;
}

.form-control, .form-select {
  background: #222;
  color: #fff;
  border: 1px solid #333;
}

.form-control::placeholder {
  color: #666;
}

.form-control:focus, .form-select:focus {
  background: #222;
  color: #fff;
  border-color: #0066ff;
  box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.25);
}

.bg-primary { 
  background: #0066ff !important; 
}

.btn-primary { 
  background: #0066ff !important; 
  border-color: #0066ff !important; 
}

.btn-primary:hover { 
  background: #0052cc !important; 
  border-color: #0052cc !important; 
}

.btn-outline-primary { 
  color: #0066ff !important; 
  border-color: #0066ff !important; 
}

.btn-outline-primary:hover { 
  background: #0066ff !important; 
  color: #fff !important; 
}

.btn-dark {
  background: #000 !important;
  border-color: #333 !important;
}

.text-primary {
  color: #0066ff !important;
}

.text-secondary {
  color: #888 !important;
}

.text-info {
  color: #00ccff !important;
}

@media (max-width: 768px) { 
  .hero-wrap { height: 500px; } 
  .ftco-search { margin-top: -100px; }
}
</style>