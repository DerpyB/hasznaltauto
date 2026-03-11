<template>
  <div class="cars-page">
    <!-- Page Header -->
    <section class="hero-wrap hero-wrap-2" style="background-image: url('https://www.netcarshow.com/Mercedes-Benz-C63_AMG_Coupe_Black_Series-2012-wallpaper.jpg?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center 70%; height: 850px;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center pb-5">
            <p class="breadcrumbs">
              <router-link to="/" class="text-secondary">Kezdőlap</router-link> 
              <span class="text-secondary"><i class="fas fa-chevron-right mx-2"></i></span>
              <span class="text-white">Autók</span>
            </p>
            <h1 class="mb-0 bread text-white display-5">Keresési Találatok</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section py-5">
      <div class="container">
        <!-- Filter Toggle Button -->
        <div class="row mb-4">
          <div class="col-md-12">
            <button class="btn btn-primary mb-3" @click="showFilters = !showFilters">
              <i class="fas fa-filter me-2"></i>
              {{ showFilters ? "Szűrők elrejtése" : "Szűrők megjelenítése" }}
            </button>
          </div>
        </div>

        <!-- Filters -->
        <div v-if="showFilters" class="row mb-5">
          <div class="col-md-12">
            <div class="search-property p-4 shadow rounded">
              <h5 class="mb-4 text-white">Szűrők</h5>
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label text-secondary">Márka</label>
                  <input class="form-control bg-dark border-0 text-white" v-model="filters.brand" placeholder="Bármely" />
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Modell</label>
                  <input class="form-control bg-dark border-0 text-white" v-model="filters.model" placeholder="Bármely" />
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Max ár (Ft)</label>
                  <input type="number" class="form-control bg-dark border-0 text-white" v-model="filters.maxPrice" />
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Min. évjárat</label>
                  <input type="number" class="form-control bg-dark border-0 text-white" v-model="filters.minYear" />
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Üzemanyag</label>
                  <select class="form-select bg-dark border-0 text-white" v-model="filters.fuelType">
                    <option value="">Bármely</option>
                    <option>Benzin</option>
                    <option>Dísel</option>
                    <option>Elektromos</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Max km</label>
                  <input type="number" class="form-control bg-dark border-0 text-white" v-model="filters.maxMileage" />
                </div>
                <div class="col-md-3">
                  <label class="form-label text-secondary">Szín</label>
                  <input class="form-control bg-dark border-0 text-white" v-model="filters.color" />
                </div>
                <div class="col-md-3 d-flex align-items-end">
                  <button class="btn btn-primary w-100" @click="applyFilters">
                    <i class="fas fa-search me-2"></i>Szűrés
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading -->
        <div class="row" v-if="loading">
          <div class="col-md-12 text-center py-5">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2 text-secondary">Autók betöltése...</p>
          </div>
        </div>

        <!-- Results -->
        <template v-else>
          <div class="row mb-4">
            <div class="col-md-6">
              <h5 class="mb-0 text-white"><span class="text-primary fw-bold">{{ filteredCars.length }}</span> találat</h5>
            </div>
            <div class="col-md-6 text-end">
              <select class="form-select d-inline-block w-auto bg-dark border-0 text-white shadow-sm" v-model="sortBy" @change="sortCars">
                <option value="newest">Legújabb elöl</option>
                <option value="price-asc">Ár: Növekvő</option>
                <option value="price-desc">Ár: Csökkenő</option>
                <option value="year-desc">Évjárat: Újabb elöl</option>
              </select>
            </div>
          </div>

          <!-- No Results -->
          <div class="row" v-if="filteredCars.length === 0">
            <div class="col-md-12 text-center py-5">
              <div class="no-results p-5 rounded shadow-sm">
                <i class="fas fa-car-crash fa-3x text-secondary mb-3"></i>
                <h4 class="text-white">Nincs találat</h4>
                <p class="text-secondary mb-4">Sajnos nincs autó a megadott feltételekkel.</p>
                <button class="btn btn-outline-primary" @click="resetFilters">
                  <i class="fas fa-undo me-2"></i>Szűrők törlése
                </button>
              </div>
            </div>
          </div>

          <!-- Car Grid -->
          <div class="row">
            <div class="col-md-4 mb-4" v-for="car in paginatedCars" :key="car.id">
              <div class="car-wrap rounded shadow-sm overflow-hidden h-100">
                <div class="img position-relative" :style="{ background: getCarGradient(car.id) }">
                  <div class="details-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 hover-opacity">
                    <button @click="viewDetails(car)" class="btn btn-light rounded-pill px-4">Részletek</button>
                  </div>
                  <div class="price-wrap position-absolute bottom-0 end-0 bg-primary text-white px-3 py-2">
                    <p class="mb-0 fw-bold">{{ formatPrice(car.price) }} Ft</p>
                  </div>
                </div>
                <div class="text p-4">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="mb-0 fw-bold text-white">{{ car.brand }} {{ car.model }}</h5>
                    <span class="badge bg-dark text-white border">{{ car.year }}</span>
                  </div>
                  <p class="text-secondary small mb-3">{{ car.title || car.brand + ' ' + car.model }}</p>
                  
                  <div class="row text-center g-2 mb-3">
                    <div class="col-4 border-end border-secondary">
                      <i class="fas fa-tachometer-alt text-primary d-block mb-1"></i>
                      <small class="text-secondary">{{ formatNumber(car.mileage) }} km</small>
                    </div>
                    <div class="col-4 border-end border-secondary">
                      <i class="fas fa-gas-pump text-primary d-block mb-1"></i>
                      <small class="text-secondary">{{ car.fuel_type }}</small>
                    </div>
                    <div class="col-4">
                      <i class="fas fa-cog text-primary d-block mb-1"></i>
                      <small class="text-secondary">{{ car.engine_size }} cm³</small>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary">
                    <span class="text-secondary small"><i class="fas fa-palette me-1"></i> {{ car.color || 'N/A' }}</span>
                    <button @click="contactSeller(car)" class="btn btn-sm btn-outline-primary">Kapcsolat</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="row mt-4" v-if="totalPages > 1">
            <div class="col-md-12 d-flex justify-content-center">
              <nav>
                <ul class="pagination">
                  <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link bg-dark border-secondary text-white" @click="currentPage--">Előző</button>
                  </li>
                  <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                    <button class="page-link bg-dark border-secondary text-white" @click="currentPage = page">{{ page }}</button>
                  </li>
                  <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link bg-dark border-secondary text-white" @click="currentPage++">Következő</button>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  name: "CarsView",
  data() {
    return {
      showFilters: false,
      sortBy: 'newest',
      currentPage: 1,
      itemsPerPage: 9,
      loading: false,
      cars: [],
      filteredCars: [],
      filters: {
        brand: "", model: "", maxPrice: "", minYear: "",
        maxMileage: "", fuelType: "", color: ""
      }
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.filteredCars.length / this.itemsPerPage);
    },
    paginatedCars() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredCars.slice(start, start + this.itemsPerPage);
    }
  },
  async mounted() {
    await this.loadCars();
    
    const q = this.$route.query;
    this.filters.brand = q.brand || "";
    this.filters.model = q.model || "";
    this.filters.maxPrice = q.maxPrice || "";
    this.filters.minYear = q.minYear || "";
    this.filters.maxMileage = q.maxMileage || "";
    this.filters.fuelType = q.fuelType || "";
    this.filters.color = q.color || "";
    
    this.applyFilters();
  },
  methods: {
    async loadCars() {
      this.loading = true;
      try {
        this.cars = await api.getCars();
      } catch (error) {
        console.error('Hiba az autók betöltésekor:', error);
        this.cars = [];
      } finally {
        this.loading = false;
      }
    },
    applyFilters() {
      this.filteredCars = this.cars.filter(car => {
        return (
          (!this.filters.brand || car.brand?.toLowerCase().includes(this.filters.brand.toLowerCase())) &&
          (!this.filters.model || car.model?.toLowerCase().includes(this.filters.model.toLowerCase())) &&
          (!this.filters.maxPrice || Number(car.price) <= Number(this.filters.maxPrice)) &&
          (!this.filters.minYear || Number(car.year) >= Number(this.filters.minYear)) &&
          (!this.filters.maxMileage || Number(car.mileage) <= Number(this.filters.maxMileage)) &&
          (!this.filters.fuelType || car.fuel_type === this.filters.fuelType) &&
          (!this.filters.color || car.color?.toLowerCase().includes(this.filters.color.toLowerCase()))
        );
      });
      this.sortCars();
      this.currentPage = 1;
    },
    sortCars() {
      switch(this.sortBy) {
        case 'price-asc': this.filteredCars.sort((a, b) => a.price - b.price); break;
        case 'price-desc': this.filteredCars.sort((a, b) => b.price - a.price); break;
        case 'year-desc': this.filteredCars.sort((a, b) => b.year - a.year); break;
        case 'newest': default: this.filteredCars.sort((a, b) => b.id - a.id);
      }
    },
    resetFilters() {
      this.filters = { brand: "", model: "", maxPrice: "", minYear: "", maxMileage: "", fuelType: "", color: "" };
      this.applyFilters();
    },
    formatPrice(price) {
      return new Intl.NumberFormat('hu-HU').format(price);
    },
    formatNumber(num) {
      return num ? new Intl.NumberFormat('hu-HU').format(num) : '-';
    },
    getCarGradient(id) {
      const gradients = [
        'linear-gradient(135deg, #0066ff 0%, #00ccff 100%)',
        'linear-gradient(135deg, #0099ff 0%, #6600ff 100%)',
        'linear-gradient(135deg, #0033cc 0%, #0066ff 100%)',
        'linear-gradient(135deg, #00ccff 0%, #0066ff 100%)',
        'linear-gradient(135deg, #6600ff 0%, #0066ff 100%)',
        'linear-gradient(135deg, #0052cc 0%, #00ccff 100%)'
      ];
      return gradients[(id || 0) % gradients.length];
    },
    viewDetails(car) {
      alert(`${car.brand} ${car.model}\n\nÉvjárat: ${car.year}\nÁr: ${this.formatPrice(car.price)} Ft\nKm: ${this.formatNumber(car.mileage)} km\nÜzemanyag: ${car.fuel_type}`);
    },
    contactSeller(car) {
      alert(`Kapcsolatfelvétel: ${car.brand} ${car.model}\nTelefon: +36 30 123 4567\nEmail: ${car.brand?.toLowerCase()}@example.com`);
    }
  }
}
</script>

<style scoped>
.cars-page { 
  min-height: 100vh; 
  background: #0a0a0a;
}

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
  background: rgba(0,0,0,0.7); 
}

.breadcrumbs { 
  font-size: 16px; 
  margin-bottom: 20px; 
}

.breadcrumbs a { 
  color: #888; 
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

.search-property {
  background: #111;
  border: 1px solid #222;
  border-radius: 10px;
}

.no-results {
  background: #111;
  border: 1px solid #222;
}

.car-wrap { 
  transition: all 0.3s ease; 
  background: #111;
  border: 1px solid #222;
}

.car-wrap:hover { 
  transform: translateY(-10px); 
  box-shadow: 0 20px 40px rgba(0,102,255,0.3) !important; 
  border-color: #0066ff;
}

.car-wrap .img { 
  height: 220px; 
  overflow: hidden; 
  position: relative; 
}

.details-overlay { 
  background: rgba(0,0,0,0.6); 
  transition: all 0.3s; 
}

.car-wrap:hover .details-overlay { 
  opacity: 1 !important; 
}

.price-wrap { 
  border-top-left-radius: 10px; 
  z-index: 10; 
  background: #0066ff !important;
}

.pagination .page-item.active .page-link { 
  background-color: #0066ff; 
  border-color: #0066ff; 
  color: #fff;
}

.pagination .page-link { 
  color: #fff; 
  border: 1px solid #333;
  margin: 0 3px; 
  border-radius: 5px; 
}

.pagination .page-link:hover {
  background: #0066ff;
  border-color: #0066ff;
}

.bg-dark {
  background: #1a1a1a !important;
}

.border-secondary {
  border-color: #333 !important;
}

.form-control, .form-select {
  background: #1a1a1a;
  color: #fff;
  border: 1px solid #333;
}

.form-control:focus, .form-select:focus {
  background: #1a1a1a;
  color: #fff;
  border-color: #0066ff;
  box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.25);
}

.btn-primary { 
  background: #0066ff !important; 
  border-color: #0066ff !important; 
}

.btn-outline-primary { 
  color: #0066ff !important; 
  border-color: #0066ff !important; 
}

.btn-outline-primary:hover { 
  background: #0066ff !important; 
  color: #fff !important; 
}

.text-primary {
  color: #0066ff !important;
}

.text-secondary {
  color: #888 !important;
}

.text-white {
  color: #fff !important;
}

@media (max-width: 768px) { 
  .hero-wrap-2 { height: 300px; } 
  .car-wrap .img { height: 180px; } 
}
</style>