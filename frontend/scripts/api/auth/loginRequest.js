const API_BASE_URL = "http://localhost/Group-project-/backend/router/main.php";

async function loginRequest(payload) {
  const headers = {
    "Content-Type": "application/json",
    ...payload.headers,
  };

  const fetchOptions = {
    method: payload.method || "POST",
    headers,
    body: payload.body ?? undefined,
  };
  
  const response = await fetch(API_BASE_URL, fetchOptions);

  if (!response.ok && response.status !== 401) {
    const errorText = await response.text();
    throw new Error(`HTTP error ${response.status}: ${errorText}`);
  }

  return await response.json();
}

export { loginRequest };