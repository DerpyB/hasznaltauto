const API_URL = 'https://hasznaltauto.alwaysdata.net/api';

class ApiService {
  async getCars() {
    const response = await fetch(`${API_URL}/cars`);
    return response.json();
  }

  async getCar(id) {
    const response = await fetch(`${API_URL}/cars/${id}`);
    return response.json();
  }

  async createCar(carData) {
    const response = await fetch(`${API_URL}/cars`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(carData)
    });
    return response.json();
  }

  async updateCar(id, carData) {
    const response = await fetch(`${API_URL}/cars/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(carData)
    });
    return response.json();
  }

  async deleteCar(id) {
    await fetch(`${API_URL}/cars/${id}`, { method: 'DELETE' });
  }

  async getListings() {
    const response = await fetch(`${API_URL}/listings`);
    return response.json();
  }

  async createListing(listingData) {
    const response = await fetch(`${API_URL}/listings`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(listingData)
    });
    return response.json();
  }
}

export default new ApiService();