<?php
require_once 'db.php';
require_once 'PointagesinfoModel.php';

/*=========================================================================================
AbsenceModel.php => Résumé des fonctions
===========================================================================================
> boolPointed() : check si l'étudiant a un pointage actif
> addPointage() : ajoute un pointage/dépointage
> disablePreviousPointage() : met is_pointed à 0
> lastPointedInfo() : retourne les infos du dernier pointage
> historiquePointage() : retourne l'historique d'un étudiant groupé par jour
===========================================================================================*/

class Pointage extends Database {
    public $id;
    public $date; // dateTime
    public $entree_sortie; // bool
    public $is_pointed; // bool
    // Clé étrangère
    public $id_etudiants;

    public function boolPointed(){
        $query = "SELECT id 
                FROM pointages 
                WHERE id_etudiants = :id_etudiants 
                AND is_pointed = 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return ($count >= 1);
        } catch (PDOException $e) {
            throw new Exception("Error checking pointage: " . $e->getMessage());
        }
    }

    public function addPointage(){
        $this->db->beginTransaction();
        try {
            // Désactiver les pointages précédents
            $this->disablePreviousPointage();

            // Ajouter un nouveau pointage
            $query = "INSERT INTO `pointages` (`entree_sortie`,`is_pointed`, `id_etudiants`) 
                      VALUES (:entree_sortie ,:is_pointed, :id_etudiants)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":entree_sortie", $this->entree_sortie, PDO::PARAM_INT);
            $stmt->bindParam(":is_pointed", $this->is_pointed, PDO::PARAM_INT);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $lastInsertedId = $this->db->lastInsertId();

         

            $this->db->commit();
            return $lastInsertedId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error adding pointage: " . $e->getMessage());
        }
    }

    public function disablePreviousPointage(){
        $query = "UPDATE pointages
                  SET is_pointed = 0 
                  WHERE is_pointed = 1
                  AND id_etudiants = :id_etudiants";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error disabling previous pointage: " . $e->getMessage());
        }
    }

    public function lastPointedInfo(){
        $query = "SELECT id_etudiants, entree_sortie, MAX(date) AS last_pointed_date
                  FROM pointages
                  WHERE id_etudiants = :id_etudiants";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                return $result;
            } else {
                return null;
            }
           
        } catch (PDOException $e) {
            throw new Exception("Error retrieving last pointed info: " . $e->getMessage());
        }
    }

    public function historiquePointage(){
        $query = "SELECT *, DATE(date) as jour
                  FROM pointages
                  WHERE id_etudiants = :id_etudiants
                  ORDER BY date, jour";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                return $result;
            } else {
                return null;
            }
           
        } catch (PDOException $e) {
            throw new Exception("Error retrieving pointage history: " . $e->getMessage());
        }
    }

    public function currentTime(){
        $query = "SELECT 
            id_etudiants,
            SUM(IF(sortie_time IS NULL, 
                TIMESTAMPDIFF(SECOND, entree_time, NOW()), 
                TIMESTAMPDIFF(SECOND, entree_time, sortie_time))
            ) / 3600 AS total_hours_today
        FROM (
            -- Subquery to pair entree and sortie records for each student
            SELECT 
                p_in.id_etudiants, 
                p_in.date AS entree_time,
                (SELECT MIN(p_out.date) 
                 FROM pointages p_out 
                 WHERE p_out.id_etudiants = p_in.id_etudiants 
                   AND p_out.entree_sortie = 0 
                   AND p_out.date > p_in.date
                   AND DATE(p_out.date) = DATE(p_in.date)) AS sortie_time
            FROM pointages p_in
            WHERE p_in.entree_sortie = 1
              AND p_in.id_etudiants = :id_etudiants
              AND DATE(p_in.date) = CURDATE()
        ) AS paired_times
        GROUP BY id_etudiants";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result['total_hours_today'])) {
                $totalHours = (float) $result['total_hours_today'];
                $hours = floor($totalHours); 
                $minutes = round(($totalHours - $hours) * 60); 
    
                $formattedTime = sprintf('%02d:%02d', $hours, $minutes);
    
                return $formattedTime;
            } else {
                return '00:00'; 
            }
        
        } catch (PDOException $e) {
            throw new Exception("Error retrieving pointage history: " . $e->getMessage());
        }    
    }

    public function totalHours(){
        $query = "SELECT 
                id_etudiants,
                SUM(TIMESTAMPDIFF(SECOND, entree_time, sortie_time) / 3600) AS total_hours
            FROM (
                SELECT 
                    p_in.id_etudiants, 
                    p_in.date AS entree_time,
                    (
                        SELECT MIN(p_out.date)
                        FROM pointages p_out
                        WHERE p_out.id_etudiants = p_in.id_etudiants 
                        AND p_out.entree_sortie = 0 
                        AND p_out.date > p_in.date
                        AND DATE(p_out.date) = DATE(p_in.date)
                    ) AS sortie_time
                FROM pointages p_in
                WHERE p_in.entree_sortie = 1
                AND id_etudiants = :id_etudiants
            ) AS paired_times
            WHERE sortie_time IS NOT NULL
            GROUP BY id_etudiants;
            ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total_hours'])) {
                $totalHours = (float) $result['total_hours'];
                $hours = floor($totalHours); 
                $minutes = round(($totalHours - $hours) * 60); 
    
                $formattedTime = sprintf('%02d:%02d', $hours, $minutes);
    
                return $formattedTime;
            } else {
                return '00:00'; 
            }
        
        } catch (PDOException $e) {
            throw new Exception("Erreur récupération du total d'heures : " . $e->getMessage());
        }    
    }

    public function totalHoursPerMonth(){
        $query = "SELECT 
            id_etudiants,
            DATE_FORMAT(entree_time, '%Y-%m') AS month,
            SUM(TIMESTAMPDIFF(SECOND, entree_time, sortie_time) / 3600) AS month_hours
        FROM (
            SELECT 
                p_in.id_etudiants, 
                p_in.date AS entree_time,
                (
                    SELECT MIN(p_out.date)
                    FROM pointages p_out
                    WHERE p_out.id_etudiants = p_in.id_etudiants 
                    AND p_out.entree_sortie = 0 
                    AND p_out.date > p_in.date
                    AND DATE(p_out.date) = DATE(p_in.date)
                ) AS sortie_time
            FROM pointages p_in
            WHERE p_in.entree_sortie = 1
            AND id_etudiants = :id_etudiants
        ) AS paired_times
        WHERE sortie_time IS NOT NULL
        GROUP BY id_etudiants, month
        ORDER BY month;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $formattedResults = [];
            if ($result) {
                foreach ($result as $row) {
                    $totalHours = (float) $row['month_hours'];
                    $hours = floor($totalHours); 
                    $minutes = round(($totalHours - $hours) * 60); 
        
                    $formattedTime = sprintf('%02d:%02d', $hours, $minutes);
        
                    $formattedResults[$row['month']] = $formattedTime;
                }

                return $formattedResults;
            } else {
                return ['00-00' => '00:00']; 
            }        
        } catch (PDOException $e) {
            throw new Exception("Erreur récupération du total d'heures mensuel : " . $e->getMessage());
        }    
    }

    public function totalHoursPerDay() {
        $query = "SELECT 
            id_etudiants,
            DATE(entree_time) AS day,
            DATE_FORMAT(entree_time, '%Y-%m') AS month,
            SUM(TIMESTAMPDIFF(SECOND, entree_time, sortie_time) / 3600) AS day_hours
        FROM (
            SELECT 
                p_in.id_etudiants, 
                p_in.date AS entree_time,
                (
                    SELECT MIN(p_out.date)
                    FROM pointages p_out
                    WHERE p_out.id_etudiants = p_in.id_etudiants 
                    AND p_out.entree_sortie = 0 
                    AND p_out.date > p_in.date
                    AND DATE(p_out.date) = DATE(p_in.date)
                ) AS sortie_time
            FROM pointages p_in
            WHERE p_in.entree_sortie = 1
            AND id_etudiants = :id_etudiants
        ) AS paired_times
        WHERE sortie_time IS NOT NULL 
        GROUP BY id_etudiants, day
        ORDER BY month, day;
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $formattedResults = [];
            if ($result) {
                foreach ($result as $row) {
                    $totalHours = (float) $row['day_hours'];
                    $hours = floor($totalHours); 
                    $minutes = round(($totalHours - $hours) * 60); 
            
                    $formattedTime = sprintf('%02d:%02d', $hours, $minutes);
            
                    $formattedResults[$row['day']] = $formattedTime;
                }
    
                return $formattedResults;
            } else {
                return []; // Return an empty array if there's no data
            }        
        } catch (PDOException $e) {
            throw new Exception("Erreur récupération du total d'heures journalier : " . $e->getMessage());
        }    
    }
    

    /* 
    // Heures totales de pointages pour 1 etudiant
    SELECT 
        id_etudiants,
        SUM(TIMESTAMPDIFF(SECOND, entree_time, IFNULL(sortie_time, NOW()))) / 3600 AS total_hours
    FROM (
        SELECT 
            p_in.id_etudiants, 
            p_in.date AS entree_time,
            (SELECT MIN(p_out.date) 
            FROM pointages p_out 
            WHERE p_out.id_etudiants = p_in.id_etudiants 
            AND p_out.entree_sortie = 0 
            AND p_out.date > p_in.date
            AND DATE(p_out.date) = DATE(p_in.date)) AS sortie_time
        FROM pointages p_in
        WHERE p_in.entree_sortie = 1
        AND id_etudiants = :id_etudiants
    ) AS paired_times;

    // Heures par mois 
    SELECT 
    id_etudiants,
    DATE_FORMAT(entree_time, '%Y-%m') AS month,
    SUM(TIMESTAMPDIFF(SECOND, entree_time, IFNULL(sortie_time, NOW()))) / 3600 AS month_hours
FROM (
    SELECT 
        p_in.id_etudiants, 
        p_in.date AS entree_time,
        (SELECT MIN(p_out.date) 
         FROM pointages p_out 
         WHERE p_out.id_etudiants = p_in.id_etudiants 
         AND p_out.entree_sortie = 0 
         AND p_out.date > p_in.date
         AND DATE(p_out.date) = DATE(p_in.date)) AS sortie_time
    FROM pointages p_in
    WHERE p_in.entree_sortie = 1
    AND id_etudiants = :id_etudiants
) AS paired_times
GROUP BY id_etudiants, month;

// Heures par jour par mois 
SELECT 
    id_etudiants,
    DATE(entree_time) AS day,
    DATE_FORMAT(entree_time, '%Y-%m') AS month,
    SUM(TIMESTAMPDIFF(SECOND, entree_time, IFNULL(sortie_time, NOW()))) / 3600 AS day_hours
FROM (
    SELECT 
        p_in.id_etudiants, 
        p_in.date AS entree_time,
        (SELECT MIN(p_out.date) 
         FROM pointages p_out 
         WHERE p_out.id_etudiants = p_in.id_etudiants 
         AND p_out.entree_sortie = 0 
         AND p_out.date > p_in.date
         AND DATE(p_out.date) = DATE(p_in.date)) AS sortie_time
    FROM pointages p_in
    WHERE p_in.entree_sortie = 1
    AND id_etudiants = :id_etudiants
) AS paired_times
GROUP BY id_etudiants, day
ORDER BY month, day;

    // Récupère les heures par jour pour 1 mois et 1 année donnée pour 1 étudiant 
    SELECT 
        id_etudiants,
        DATE(entree_time) AS day, 
        SUM(IF(sortie_time IS NULL, TIMESTAMPDIFF(SECOND, entree_time, NOW()), TIMESTAMPDIFF(SECOND, entree_time, sortie_time))) / 3600 AS total_hours
    FROM (
        -- Subquery to pair entree and sortie records for each student
        SELECT 
            p_in.id_etudiants, 
            p_in.date AS entree_time,
            (SELECT MIN(p_out.date) 
            FROM pointages p_out 
            WHERE p_out.id_etudiants = p_in.id_etudiants 
            AND p_out.entree_sortie = 0 
            AND p_out.date > p_in.date
            AND DATE(p_out.date) = DATE(p_in.date)) AS sortie_time
        FROM pointages p_in
        WHERE p_in.entree_sortie = 1
        AND id_etudiants = :id_etudiants
        AND MONTH(p_in.date) = :month  
        AND YEAR(p_in.date) = :year
    ) AS paired_times
    GROUP BY id_etudiants, day
    ORDER BY id_etudiants, day;

    */
}


?>