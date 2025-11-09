

function getToken() {
    return localStorage.getItem("jwt_token");
}
 
function setToken(token) {
  localStorage.setItem("jwt_token", token);
}

function clearToken() {
  localStorage.removeItem("jwt_token");
}

export {getToken, setToken, clearToken };