const BASE_URL = "https://691a3adc2d8d7855756e387c.mockapi.io/api/v1/articles";

export class ApiClient {
  async fetchArticles() {
    const response = await fetch(BASE_URL);
    if (!response.ok) {
      throw new Error("Failed to fetch articles");
    }
    const data = await response.json();
    return data;
  }
}
