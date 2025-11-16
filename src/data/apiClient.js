const API_URL = "https://691a3adc2d8d7855756e387c.mockapi.io/api/v1/articles";

export class ApiClient {
  async fetchArticles() {
    const response = await fetch(API_URL);
    if (!response.ok) {
      throw new Error(`API error: ${response.status}`);
    }
    return await response.json();
  }
}
