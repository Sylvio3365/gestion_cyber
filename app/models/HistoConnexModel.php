<?php

namespace app\models;

class HistoConnexModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère l'historique des connexions avec filtrage
     * 
     * @param string $period (jour, mois, annee)
     * @param string $date Date de référence
     * @return array
     */
    public function getHistorique($period = null, $date = null)
    {
        $query = "SELECT 
                    hc.id_historique_connection,
                    hc.date_debut,
                    hc.date_fin,
                    hc.id_client,
                    hc.id_poste,
                    CONCAT(c.nom, ' ', c.prenom) AS client_nom_complet,
                    TIMESTAMPDIFF(MINUTE, hc.date_debut, hc.date_fin) AS duree_minutes,
                    p.numero_poste AS poste_nom,
                    hc.statut
                  FROM historique_connexion hc
                JOIN client c ON hc.id_client = c.id_client
                LEFT JOIN poste p ON hc.id_poste = p.id_poste 
                WHERE hc.date_fin IS NOT NULL"; // Ajout de la condition pour exclure les connexions sans date de fin

        $params = [];

        if ($period && $date) {
            switch ($period) {
                case 'jour':
                    $query .= " AND DATE(hc.date_fin) = :date";
                    $params[':date'] = $date;
                    break;
                case 'mois':
                    $query .= " AND MONTH(hc.date_fin) = MONTH(:date) AND YEAR(hc.date_fin) = YEAR(:date)";
                    $params[':date'] = $date;
                    break;
                case 'annee':
                    $query .= " AND YEAR(hc.date_fin) = YEAR(:date)";
                    $params[':date'] = $date;
                    break;
            }
        }

        $query .= " ORDER BY hc.date_fin DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques agrégées
     */
    public function getStats($period = null, $date = null)
    {
        $query = "SELECT 
                    COUNT(*) as total_connexions,
                    COUNT(DISTINCT id_client) as clients_uniques,
                    COUNT(DISTINCT id_poste) as postes_utilises,
                    SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, date_debut, date_fin))) as duree_moyenne
              FROM historique_connexion
              WHERE date_fin IS NOT NULL"; // Ajout de la condition pour exclure les connexions sans date de fin

        $params = [];

        if ($period && $date) {
            switch ($period) {
                case 'jour':
                    $query .= " AND DATE(date_fin) = :date";
                    $params[':date'] = $date;
                    break;
                case 'mois':
                    $query .= " AND MONTH(date_fin) = MONTH(:date) AND YEAR(date_fin) = YEAR(:date)";
                    $params[':date'] = $date;
                    break;
                case 'annee':
                    $query .= " AND YEAR(date_fin) = YEAR(:date)";
                    $params[':date'] = $date;
                    break;
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une entrée dans l'historique
     */
    public function addConnexion($id_historique_connection, $id_client, $id_poste = null)
    {
        $query = "INSERT INTO historique_connexion 
                  (id_historique_connection, date_debut, id_client, id_poste, statut) 
                  VALUES (:id, NOW(), :id_client, :id_poste, 0)"; // Par défaut, statut = 0 (Non payé)

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id_historique_connection,
            ':id_client' => $id_client,
            ':id_poste' => $id_poste
        ]);
    }

    /**
     * Met à jour la date de fin de connexion
     */
    public function updateFinConnexion($id_historique_connection)
    {
        $query = "UPDATE historique_connexion 
                  SET date_fin = NOW(), statut = 1 
                  WHERE id_historique_connection = :id"; // Met à jour le statut à "Payé" lorsque la connexion est terminée

        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id_historique_connection]);
    }
}
