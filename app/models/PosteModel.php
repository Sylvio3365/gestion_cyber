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
        return ($result['count'] == 0);
    }

    public function setEtatPoste($id_poste, $id_etat, $date_debut = null, $date_fin = null)
    {
        if (empty($id_poste) || empty($id_etat)) {
            throw new \InvalidArgumentException("ID poste et ID état sont obligatoires");
        }
        $date_debut = $date_debut ?? date('Y-m-d H:i:s');

        $query = "INSERT INTO poste_etat (id_poste, id_etat, date_debut, date_fin) 
              VALUES (:id_poste, :id_etat, :date_debut, :date_fin)";

        $params = [
            ':id_poste' => $id_poste,
            ':id_etat' => $id_etat,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ];

        $stmt = $this->db->prepare($query);
        $success = $stmt->execute($params);

        if (!$success) {
            throw new \RuntimeException("Erreur lors de la mise à jour de l'état du poste: " . implode(", ", $stmt->errorInfo()));
        }
        return true;
    }

    public function demarrerSessionPoste($id_poste, $id_client, $duree_minutes = null)
    {
        try {
            $this->db->beginTransaction();
            date_default_timezone_set('Indian/Antananarivo');

            // 1. Terminer l'état précédent
            $updateEtatFin = "
            UPDATE poste_etat 
            SET date_fin = NOW()
            WHERE id_poste = :id_poste 
              AND date_fin IS NULL
            ORDER BY date_debut DESC
            LIMIT 1
        ";
            $stmt = $this->db->prepare($updateEtatFin);
            $stmt->execute([':id_poste' => $id_poste]);

            // 2. Ajouter le nouvel état
            $this->setEtatPoste(
                $id_poste,
                2, // "Occupé"
                date('Y-m-d H:i:s'),
                $duree_minutes ? date('Y-m-d H:i:s', strtotime("+$duree_minutes minutes")) : null
            );

            // 3. Historique connexion
            $query = "INSERT INTO historique_connexion 
            (id_poste, id_client, date_debut, date_fin) 
            VALUES (:id_poste, :id_client, NOW(), ";
            $query .= $duree_minutes ? "DATE_ADD(NOW(), INTERVAL :duree MINUTE))" : "NULL)";

            $stmt = $this->db->prepare($query);
            $params = [
                ':id_poste' => $id_poste,
                ':id_client' => $id_client
            ];
            if ($duree_minutes) {
                $params[':duree'] = $duree_minutes;
            }

            // ✅ Appel unique
            $stmt->execute($params);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new \RuntimeException("Erreur lors du démarrage de la session: " . $e->getMessage());
        }
    }

    public function mettreEnMaintenance($id_poste)
    {
        try {
            $this->db->beginTransaction();
            date_default_timezone_set('Indian/Antananarivo');

            // 1. Terminer l'état précédent
            $updateEtatFin = "
            UPDATE poste_etat 
            SET date_fin = NOW()
            WHERE id_poste = :id_poste 
              AND date_fin IS NULL
            ORDER BY date_debut DESC
            LIMIT 1
            ";
            $stmt = $this->db->prepare($updateEtatFin);
            $stmt->execute([':id_poste' => $id_poste]);

            // 2. Ajouter le nouvel état "En maintenance"
            $this->setEtatPoste(
                $id_poste,
                3, // ID pour l'état "En maintenance"
                date('Y-m-d H:i:s'),
                null
            );

            // 3. Si un client était connecté, terminer sa session
            $query = "UPDATE historique_connexion 
                  SET date_fin = NOW() 
                  WHERE id_poste = :id_poste AND date_fin IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id_poste' => $id_poste]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new \RuntimeException("Erreur lors de la mise en maintenance: " . $e->getMessage());
        }
    }

    public function rendreDisponible($id_poste)
    {
        try {
            $this->db->beginTransaction();
            date_default_timezone_set('Indian/Antananarivo');

            // 1. Terminer l'état précédent (maintenance)
            $updateEtatFin = "
            UPDATE poste_etat 
            SET date_fin = NOW()
            WHERE id_poste = :id_poste 
              AND date_fin IS NULL
            ORDER BY date_debut DESC
            LIMIT 1
        ";
            $stmt = $this->db->prepare($updateEtatFin);
            $stmt->execute([':id_poste' => $id_poste]);

            // 2. Ajouter le nouvel état "Disponible"
            $this->setEtatPoste(
                $id_poste,
                1, // ID pour l'état "Disponible"
                date('Y-m-d H:i:s'),
                null
            );

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new \RuntimeException("Erreur lors du retour à disponible: " . $e->getMessage());
        }
    }

    public function arreterSessionPoste($id_poste)
    {
        try {
            $this->db->beginTransaction();
            date_default_timezone_set('Indian/Antananarivo');

            // 1. Vérifier si l'état actuel a déjà une date de fin
            $checkEtat = "
            SELECT date_fin 
            FROM poste_etat 
            WHERE id_poste = :id_poste 
            ORDER BY date_debut DESC 
            LIMIT 1
        ";
            $stmt = $this->db->prepare($checkEtat);
            $stmt->execute([':id_poste' => $id_poste]);
            $etatActuel = $stmt->fetch(\PDO::FETCH_ASSOC);

            // 2. Mettre à jour la date_fin seulement si elle est NULL
            if ($etatActuel && $etatActuel['date_fin'] === null) {
                $updateEtatFin = "
                UPDATE poste_etat 
                SET date_fin = NOW()
                WHERE id_poste = :id_poste 
                AND date_fin IS NULL
                ORDER BY date_debut DESC
                LIMIT 1
            ";
                $stmt = $this->db->prepare($updateEtatFin);
                $stmt->execute([':id_poste' => $id_poste]);
            }

            // 3. Définir le nouvel état "Disponible"
            $this->setEtatPoste(
                $id_poste,
                1, // ID pour l'état "Disponible"
                date('Y-m-d H:i:s'),
                null
            );

            // 4. Mettre à jour l'historique de connexion si date_fin est NULL
            $query = "
            UPDATE historique_connexion 
            SET date_fin = NOW() 
            WHERE id_poste = :id_poste 
            AND date_fin IS NULL
        ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id_poste' => $id_poste]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new \RuntimeException("Erreur lors de l'arrêt de la session: " . $e->getMessage());
        }
    }

    public function demarrerSession()
    {
        try {
            // Démarrer une transaction
            $this->db->beginTransaction();

            date_default_timezone_set('Indian/Antananarivo');
            $currentDateTime = date('Y-m-d H:i:s');

            // Récupérer le dernier état de chaque poste
            $derniersEtats = $this->getDernierEtatPourTousLesPostes();

            // Si aucun dernier état n'existe, récupérer tous les postes
            if (empty($derniersEtats)) {
                $tousLesPostes = $this->getAll(); // Supposons que cette méthode retourne tous les postes

                foreach ($tousLesPostes as $poste) {
                    $this->setEtatPoste(
                        $poste['id_poste'],
                        1, // ID pour l'état "Disponible"
                        $currentDateTime,
                        null
                    );
                }
            } else {
                // Pour chaque poste, créer un nouvel enregistrement avec le même état
                foreach ($derniersEtats as $etat) {
                    // Si le poste était occupé (état 2), le rendre disponible (état 1)
                    $nouvelEtat = ($etat['id_etat'] == 2) ? 1 : $etat['id_etat'];

                    $this->setEtatPoste(
                        $etat['id_poste'],
                        $nouvelEtat,
                        $currentDateTime,
                        null
                    );
                }
            }

            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new \RuntimeException("Erreur lors du démarrage de la session: " . $e->getMessage());
        }
    }

    public function getDernierEtatPourTousLesPostes()
    {
        $query = "SELECT pe.*, e.nom as etat_nom
              FROM poste_etat pe
              INNER JOIN (
                  SELECT id_poste, MAX(date_debut) as derniere_date
                  FROM poste_etat
                  GROUP BY id_poste
              ) derniers ON pe.id_poste = derniers.id_poste AND pe.date_debut = derniers.derniere_date
              JOIN etat e ON pe.id_etat = e.id_etat
              ORDER BY pe.id_poste";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllPosteAvecEtatActuel()
    {
        // 1. Récupérer tous les postes avec leur état actuel
        $query = "
    SELECT 
        p.id_poste,
        p.numero_poste,
        e.nom AS etat_nom,
        pe.date_debut,
        pe.date_fin
    FROM poste p
    JOIN (
        SELECT id_poste, MAX(date_debut) AS max_date
        FROM poste_etat
        GROUP BY id_poste
    ) latest ON p.id_poste = latest.id_poste
    JOIN poste_etat pe ON pe.id_poste = latest.id_poste AND pe.date_debut = latest.max_date
    JOIN etat e ON pe.id_etat = e.id_etat
    ORDER BY p.numero_poste
    ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $postes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Pour chaque poste, si l'état est "Occupé", charger les infos du client
        foreach ($postes as &$poste) {
            if ($poste['etat_nom'] === 'Occupé') {
                $queryClient = "
            SELECT 
                hc.id_client,
                CONCAT(c.prenom, ' ', c.nom) AS nom_client,
                hc.date_debut,
                hc.date_fin
            FROM historique_connexion hc
            JOIN client c ON hc.id_client = c.id_client
            WHERE hc.id_poste = :id_poste
            ORDER BY hc.date_debut DESC
            LIMIT 1
            ";

                $stmtClient = $this->db->prepare($queryClient);
                $stmtClient->execute([':id_poste' => $poste['id_poste']]);
                $clientData = $stmtClient->fetch(\PDO::FETCH_ASSOC);

                if ($clientData) {
                    $poste['id_client_occupant'] = $clientData['id_client'];
                    $poste['nom_client_occupant'] = $clientData['nom_client'];
                    $poste['date_debut'] = $clientData['date_debut'];
                    $poste['date_fin'] = $clientData['date_fin'];

                    // Si date_fin est définie, calculer la durée totale
                    if ($clientData['date_fin']) {
                        $seconds = strtotime($clientData['date_fin']) - strtotime($clientData['date_debut']);
                        $hours = floor($seconds / 3600);
                        $minutes = floor(($seconds % 3600) / 60);
                        $poste['duree'] = sprintf("%02dh %02dmin", $hours, $minutes);
                    } else {
                        $poste['duree'] = 'En cours';
                    }
                }
            } else {
                $poste['date_fin'] = null;
                $poste['duree'] = null;
            }
        }

        return $postes;
    }
}
