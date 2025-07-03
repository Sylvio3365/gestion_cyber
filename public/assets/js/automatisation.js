// Fonction pour vérifier les sessions expirées
function checkExpiredSessions() {
  const currentTime = new Date();
  console.log(
    `[${currentTime.toLocaleTimeString()}] Vérification des sessions...`
  );

  let shouldReload = false;

  // Parcourir tous les postes occupés avec date de fin
  document
    .querySelectorAll(".pc-card.active .expiration-time")
    .forEach((element) => {
      const posteCard = element.closest(".col-xl-3");
      const posteData = JSON.parse(posteCard.getAttribute("data-poste"));

      // Si la session est déjà marquée comme expirée, on passe
      if (posteData.session_expiree) {
        console.log(
          `Poste ${posteData.numero_poste}: Session déjà marquée comme expirée`
        );
        return;
      }

      const expirationTimeStr = element.getAttribute("data-expiration");
      const expirationTime = new Date(expirationTimeStr);

      console.log(
        `Poste ${
          posteData.numero_poste
        }: Fin prévue à ${expirationTime.toLocaleTimeString()}`
      );

      if (currentTime >= expirationTime) {
        console.log(
          `Session expirée pour le poste ${posteData.numero_poste} - Arrêt en cours...`
        );
        shouldReload = true;

        // Marquer la session comme expirée dans les données
        posteData.session_expiree = true;
        posteCard.setAttribute("data-poste", JSON.stringify(posteData));

        // Appeler l'API pour arrêter la session
        fetch("/poste/arreterSessionPoste", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `action=arreter_poste&numero_poste=${posteData.numero_poste}&poste_id=${posteData.id_poste}`,
        })
          .then((response) => {
            if (response.ok) {
              console.log(
                `Session arrêtée avec succès pour le poste ${posteData.numero_poste}`
              );
            } else {
              console.error(
                `Erreur lors de l'arrêt de la session pour le poste ${posteData.numero_poste}`
              );
              // En cas d'erreur, réinitialiser le flag pour réessayer plus tard
              posteData.session_expiree = false;
              posteCard.setAttribute("data-poste", JSON.stringify(posteData));
            }
          })
          .catch((error) => {
            console.error("Erreur:", error);
            // En cas d'erreur, réinitialiser le flag pour réessayer plus tard
            posteData.session_expiree = false;
            posteCard.setAttribute("data-poste", JSON.stringify(posteData));
          });
      }
    });

  // Recharger la page si au moins une session a expiré
  if (shouldReload) {
    setTimeout(() => {
      window.location.reload();
    }, 1000); // Délai de 1 seconde avant le rechargement
  }
}

// Vérifier toutes les 10 secondes (au lieu de 1 seconde pour réduire la charge)
setInterval(checkExpiredSessions, 1000);

// Vérifier immédiatement au chargement de la page
document.addEventListener("DOMContentLoaded", checkExpiredSessions);
