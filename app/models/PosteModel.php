<?php

namespace app\models;

use Flight;

class PosteModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM poste";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function estNouveauJour()
    {
        $query = "SELECT COUNT(*) as count 
              FROM poste_etat 
              WHERE DATE(date_debut) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        // Retourne true si aucun changement d'état n'a été enregistré aujourd'hui (nouveau jour)
        return ($result['count'] == 0);
    }

    public function getAllPosteAvecEtatActuel()
    {
        $query = "SELECT 
            p.id_poste,
            p.numero_poste,
            e.nom as etat_nom, 
            pe.date_debut, 
            pe.date_fin,
            CASE 
                WHEN e.nom = 'Occupé' THEN c.id_client
                ELSE NULL
            END as id_client_occupant,
            CASE 
                WHEN e.nom = 'Occupé' THEN CONCAT(c.prenom, ' ', c.nom)
                ELSE NULL
            END as nom_client_occupant,
            CASE
                WHEN e.nom = 'Occupé' THEN TIMESTAMPDIFF(SECOND, hc.date_debut, NOW())
                ELSE NULL
            END as duree_secondes
          FROM poste p
          JOIN (
              SELECT id_poste, MAX(date_debut) as max_date
              FROM poste_etat
              GROUP BY id_poste
          ) as latest ON p.id_poste = latest.id_poste
          JOIN poste_etat pe ON pe.id_poste = latest.id_poste AND pe.date_debut = latest.max_date
          JOIN etat e ON pe.id_etat = e.id_etat
          LEFT JOIN historique_connexion hc ON p.id_poste = hc.id_poste AND hc.date_fin IS NULL
          LEFT JOIN client c ON hc.id_client = c.id_client
          ORDER BY p.numero_poste";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $postes = $stmt->fetchAll();

        // Formater les données pour l'affichage
        foreach ($postes as &$poste) {
            if ($poste['etat_nom'] === 'Occupé' && $poste['duree_secondes'] !== null) {
                // Calculer les heures, minutes et secondes
                $hours = floor($poste['duree_secondes'] / 3600);
                $minutes = floor(($poste['duree_secondes'] % 3600) / 60);
                $seconds = $poste['duree_secondes'] % 60;

                // Formatage de la durée
                if ($hours > 0) {
                    $poste['duree'] = sprintf("%dh %02dmin %02ds", $hours, $minutes, $seconds);
                } else {
                    $poste['duree'] = sprintf("%dmin %02ds", $minutes, $seconds);
                }

                // Calculer le prix (0.10€ par minute)
                $total_minutes = $poste['duree_secondes'] / 60;
                $poste['prix'] = number_format($total_minutes * 0.10, 2) . " €";

                // Ajouter aussi le timestamp de début pour un éventuel calcul côté client
                $poste['date_debut_timestamp'] = strtotime($poste['date_debut']);
            } else {
                $poste['duree'] = null;
                $poste['prix'] = null;
            }
        }

        return $postes;
    }
}
