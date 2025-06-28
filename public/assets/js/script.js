// script.js
function mettreAJourHeure() {
  const maintenant = new Date();

  // Formatage de l'heure (HH:MM:SS)
  const heures = maintenant.getHours().toString().padStart(2, "0");
  const minutes = maintenant.getMinutes().toString().padStart(2, "0");
  const secondes = maintenant.getSeconds().toString().padStart(2, "0");
  const heureFormattee = `${heures}:${minutes}:${secondes}`;

  // Mise à jour de l'élément HTML
  document.getElementById("heure-actuelle").textContent = heureFormattee;
}

// Mettre à jour l'heure immédiatement
mettreAJourHeure();

// Mettre à jour l'heure toutes les secondes
setInterval(mettreAJourHeure, 1000);
