

function getToken() {
    return localStorage.getItem("JWT_TOKEN");
}
 
function setToken(token) {
  localStorage.setItem("jwt_token", token);
}

function clearToken() {
  localStorage.removeItem("jwt_token");
}

export { API_BASE_URL, getToken, setToken, clearToken };