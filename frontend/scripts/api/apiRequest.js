import { getToken, clearToken } from "./config/auth.js";
const API_BASE_URL = "http://localhost/group-project/Group-project-/backend/router/main.php";

async function apiRequest(endpoint, options = {}) {
  const token = getToken();

  const headers = {
    "Content-Type": "application/json",
    ...(token ? { Authorization: "Bearer " + token } : {}),
    ...options.headers,
  };

  // Build full options safely
  const fetchOptions = {
    method: options.method || "GET",
    headers,
    body: options.body ?? undefined, 
  };
  console.log("API Request Options:", fetchOptions);
  try {

    const response = await fetch(API_BASE_URL + endpoint, fetchOptions);
    if (response.status === 401) {
      clearToken();
      window.location.href = "./signin.php";
      throw new Error("Unauthorized", response);
    }

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`HTTP error ${response.status}: ${errorText}`);
    }
    return await response.json()
  } catch (error) {
    console.error("API Request Error:", error);
    throw error;
  }
}

export { apiRequest };