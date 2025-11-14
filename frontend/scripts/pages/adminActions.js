import { apiRequest } from "../api/apiRequest.js";

// Simple helper to POST JSON to the backend router
async function postToRouter(body) {
  const payload = {
    method: "POST",
    body: JSON.stringify(body),
  };
  return await apiRequest("", payload);
}

// Delete handler
async function handleDelete(type, id) {
  const confirmed = confirm(`Haluatko varmasti poistaa tämän ${type}?`);
  if (!confirmed) return;

  try {
    if (type === "user") {
      const res = await postToRouter({ deleteKayttaja: true, KayttajaID: id });
      alert(res.message || JSON.stringify(res));
    } else if (type === "luokka") {
      const res = await postToRouter({ deleteLuokka: true, LuokkaID: id });
      alert(res.message || JSON.stringify(res));
    }
    // Optionally remove the row from the UI
    const btn = document.querySelector(`button.btn-delete[data-type="${type}"][data-id="${id}"]`);
    if (btn) {
      const row = btn.closest("tr");
      if (row) row.remove();
    }
  } catch (err) {
    console.error(err);
    alert("Poisto epäonnistui; katso konsoli lisätiedoille.");
  }
}

// Edit handler: basic prompt-based editing for small admin tweaks
async function handleEdit(type, id) {
  try {
    if (type === "user") {
      const Nimi = prompt("Uusi Nimi:");
      if (Nimi === null) return; // cancelled
      const Rooli = prompt("Uusi Rooli:");
      if (Rooli === null) return;
      const Sähköposti = prompt("Uusi Sähköposti:");
      if (Sähköposti === null) return;

      const res = await postToRouter({
        editKayttaja: true,
        KayttajaID: id,
        Nimi,
        Rooli,
        Sähköposti,
      });
      alert(res.message || JSON.stringify(res));
    } else if (type === "luokka") {
      const Nimi = prompt("Luokan nimi:");
      if (Nimi === null) return;
      const Varusteet = prompt("Varusteet (pilkulla erotettuna):");
      if (Varusteet === null) return;
      const Kapasiteetti = prompt("Kapasiteetti:");
      if (Kapasiteetti === null) return;
      const Sijainti = prompt("Sijainti:");
      if (Sijainti === null) return;
      const Tila = prompt("Tila (esim. käytössä/poissa):");
      if (Tila === null) return;

      const res = await postToRouter({
        editLuokka: true,
        LuokkaID: id,
        Nimi,
        Varusteet,
        Kapasiteetti,
        Sijainti,
        Tila,
      });
      alert(res.message || JSON.stringify(res));
    }
  } catch (err) {
    console.error(err);
    alert("Muokkaus epäonnistui; katso konsoli lisätiedoille.");
  }
}

function attachListeners() {
  document.querySelectorAll("button.btn-delete").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const type = btn.dataset.type;
      const id = btn.dataset.id;
      handleDelete(type, id);
    });
  });

  document.querySelectorAll("button.btn-edit").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const type = btn.dataset.type;
      const id = btn.dataset.id;
      handleEdit(type, id);
    });
  });
}

// Auto-attach on DOM ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", attachListeners);
} else {
  attachListeners();
}
