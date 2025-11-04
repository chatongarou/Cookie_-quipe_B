// --- Fonctions de gestion des cookies ---

// Créer un cookie
function setCookie(name, value, days) {
  const d = new Date();
  d.setTime(d.getTime() + (days*24*60*60*1000));
  const expires = "expires=" + d.toUTCString();
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Lire un cookie
function getCookie(name) {
  const cname = name + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  for (let c of ca) {
    c = c.trim();
    if (c.indexOf(cname) === 0) {
      return c.substring(cname.length, c.length);
    }
  }
  return "";
}

// Supprimer un cookie
function deleteCookie(name) {
  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

// --- Gestion des boutons ---
document.getElementById("createCookie").addEventListener("click", () => {
  setCookie("utilisateur", "Jean", 7);
  alert("Cookie créé !");
});

document.getElementById("readCookie").addEventListener("click", () => {
  const user = getCookie("utilisateur");
  alert(user ? `Bonjour ${user}` : "Aucun cookie trouvé");
});

document.getElementById("deleteCookie").addEventListener("click", () => {
  deleteCookie("utilisateur");
  alert("Cookie supprimé !");
});