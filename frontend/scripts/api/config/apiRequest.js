import { getToken, clearToken } from "./auth.js";
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
    body: options.body ?? undefined, // only include if explicitly set
  };

  const response = await fetch(API_BASE_URL + endpoint, fetchOptions);

  if (response.status === 401) {
    clearToken();
    alert("Session expired, please log in again.");
    window.location.href = "/login.html";
    throw new Error("Unauthorized");
  }

  // Handle other error statuses
  if (!response.ok) {
    const errorText = await response.text();
    throw new Error(`HTTP error ${response.status}: ${errorText}`);
  }

  // Try to parse JSON (in case server sends plain text sometimes)
  try {
    return await response.json();
  } catch {
    return {};
  }
}

export { apiRequest };