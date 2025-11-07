import { setToken } from "../config/auth.js";
import { apiRequest } from "../config/apiRequest.js";

async function signin(email, password) {
  const data = await apiRequest("", {        
    method: "POST",
    body: JSON.stringify({
      kirjaudu: true,
      sahkoposti: email,
      salasana: password,
    }),
  });

  if (data?.token) {
    setToken(data.token);
    alert("Login successful!");
    window.location.href = "/index.html";
  } else {
    alert(data.message || "Invalid credentials");
  }
}
export { signin };
