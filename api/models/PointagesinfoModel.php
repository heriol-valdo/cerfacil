<?php
require_once 'db.php';

class PointagesInfo extends Database {
    public $etudiant_email;
    public $etudiant_nom;
    public $etudiant_prenom;
    public $id_centres_de_formation;
    public $centres_nom;
    public $id_entreprises;
    public $entreprises_siret;
    public $entreprises_nom;
    public $id_session;
    public $session_nom;
    public $session_dateDebut;
    public $session_dateFin;
    public $id_formations;
    public $formations_nom;
    public $id_conseillers_financeurs;
    public $financeurs_nom;
    public $financeurs_prenom;
    public $id_entreprises_financeurs;
    public $entreprises_financeurs_siret;
    public $entreprises_financeurs_nom;
    public $id_pointages;
    public $id;

    public function addPointage($id_etudiants) {
        $this->db->beginTransaction();
        try {
            $queryInfo = "SELECT 
                            user.email AS etudiant_email,
                            etudiant.lastname AS etudiant_nom, 
                            etudiant.firstname AS etudiant_prenom, 
                            etudiant.id_centres_de_formation AS id_centres_de_formation, 
                            cdf.nomCentre AS centres_nom,
                            etudiant.id_entreprises AS id_entreprises,
                            ent.siret AS entreprises_siret, 
                            ent.nomEntreprise AS entreprises_nom,
                            etudiant.id_session AS id_session,
                            s.nomSession AS session_nom, 
                            s.dateDebut AS session_dateDebut, 
                            s.dateFin AS session_dateFin, 
                            s.id_formations AS id_formations, 
                            f.nom AS formations_nom,
                            etudiant.id_conseillers_financeurs AS id_conseillers_financeurs,
                            cof.lastname AS financeurs_nom, 
                            cof.firstname AS financeurs_prenom,
                            cof.id_entreprises AS id_entreprises_financeurs, 
                            ent.siret AS entreprises_financeurs_siret,
                            ent.nomEntreprise AS entreprises_financeurs_nom
                        
                          FROM 
                            etudiants etudiant
                          JOIN 
                            users user ON user.id = etudiant.id_users
                          JOIN 
                            centres_de_formation cdf ON cdf.id = etudiant.id_centres_de_formation
                          LEFT JOIN 
                            entreprises ent ON ent.id = etudiant.id_entreprises
                          JOIN 
                            session s ON s.id = etudiant.id_session
                          JOIN 
                            formations f ON f.id = s.id_formations
                          LEFT JOIN 
                            conseillers_financeurs cof ON cof.id = etudiant.id_conseillers_financeurs
                        
                          WHERE 
                            etudiant.id = :id_etudiants";

            $stmtInfo = $this->db->prepare($queryInfo);
            $stmtInfo->bindParam(":id_etudiants", $id_etudiants, PDO::PARAM_INT);
            $stmtInfo->execute();

            $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                $pointagesInfoQuery = "INSERT INTO pointages_infos (
                                            etudiant_email, etudiant_nom, etudiant_prenom, id_centres_de_formation, centres_nom,
                                            id_entreprises, entreprises_siret, entreprises_nom, id_formations, formations_nom,
                                            id_session, session_nom, session_dateDebut, session_dateFin,
                                            id_conseillers_financeurs, financeurs_nom, financeurs_prenom,
                                            id_entreprises_financeurs, entreprises_financeurs_siret, entreprises_financeurs_nom,
                                            id_pointages
                                        ) VALUES (
                                            :etudiant_email, :etudiant_nom, :etudiant_prenom, :id_centres_de_formation, :centres_nom,
                                            :id_entreprises, :entreprises_siret, :entreprises_nom, :id_formations, :formations_nom,
                                            :id_session, :session_nom, :session_dateDebut, :session_dateFin,
                                            :id_conseillers_financeurs, :financeurs_nom, :financeurs_prenom,
                                            :id_entreprises_financeurs, :entreprises_financeurs_siret, :entreprises_financeurs_nom,
                                            :id_pointages
                                        )";

                $stmtPointagesInfo = $this->db->prepare($pointagesInfoQuery);
                $stmtPointagesInfo->bindParam(":etudiant_email", $info['etudiant_email']);
                $stmtPointagesInfo->bindParam(":etudiant_nom", $info['etudiant_nom']);
                $stmtPointagesInfo->bindParam(":etudiant_prenom", $info['etudiant_prenom']);
                $stmtPointagesInfo->bindParam(":id_centres_de_formation", $info['id_centres_de_formation']);
                $stmtPointagesInfo->bindParam(":centres_nom", $info['centres_nom']);
                $stmtPointagesInfo->bindParam(":id_entreprises", $info['id_entreprises']);
                $stmtPointagesInfo->bindParam(":entreprises_siret", $info['entreprises_siret']);
                $stmtPointagesInfo->bindParam(":entreprises_nom", $info['entreprises_nom']);
                $stmtPointagesInfo->bindParam(":id_formations", $info['id_formations']);
                $stmtPointagesInfo->bindParam(":formations_nom", $info['formations_nom']);
                $stmtPointagesInfo->bindParam(":id_session", $info['id_session']);
                $stmtPointagesInfo->bindParam(":session_nom", $info['session_nom']);
                $stmtPointagesInfo->bindParam(":session_dateDebut", $info['session_dateDebut']);
                $stmtPointagesInfo->bindParam(":session_dateFin", $info['session_dateFin']);
                $stmtPointagesInfo->bindParam(":id_conseillers_financeurs", $info['id_conseillers_financeurs']);
                $stmtPointagesInfo->bindParam(":financeurs_nom", $info['financeurs_nom']);
                $stmtPointagesInfo->bindParam(":financeurs_prenom", $info['financeurs_prenom']);
                $stmtPointagesInfo->bindParam(":id_entreprises_financeurs", $info['id_entreprises_financeurs']);
                $stmtPointagesInfo->bindParam(":entreprises_financeurs_siret", $info['entreprises_financeurs_siret']);
                $stmtPointagesInfo->bindParam(":entreprises_financeurs_nom", $info['entreprises_financeurs_nom']);
                $stmtPointagesInfo->bindParam(":id_pointages", $this->id_pointages);
                $result = $stmtPointagesInfo->execute() ;
               
                if ($result) {
                    $this->db->commit();
                    return $result;
                }else {
                    $this->db->rollBack();
                    return false;
                }
             

            }
        
        } catch (PDOException $e) {
            
            throw new Exception("Error adding pointages info: " . $e->getMessage());
        }
    }
    
    public function getPointagesInfoByEtudiantId($id_etudiants) {
        $query = "SELECT pointages_infos.*, pointages.date, pointages.entree_sortie, pointages.id_etudiants 
                  FROM pointages_infos
                  RIGHT JOIN pointages ON pointages.id = pointages_infos.id_pointages
                  WHERE pointages.id_etudiants = :id_etudiants";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_etudiants", $id_etudiants, PDO::PARAM_INT);
            
           
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error get pointages info: " . $e->getMessage());
        }
    }
    

    public function getPointagesInfoByEtudiantEmail($email_etudiants) {
        $query = "SELECT pointages_infos.*, pointages.date, pointages.entree_sortie, pointages.id_etudiants 
                  FROM pointages_infos
                  RIGHT JOIN pointages ON pointages.id = pointages_infos.id_pointages
                  WHERE pointages_infos.etudiant_email = :email_etudiants";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":email_etudiants", $email_etudiants, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error get pointages info: " . $e->getMessage());
        }
    }
    
    public function getPointagesInfoByEtudiantNom($nom_etudiants) {
        $query = "SELECT pointages_infos.*, pointages.date, pointages.entree_sortie, pointages.id_etudiants 
                  FROM pointages_infos
                  RIGHT JOIN pointages ON pointages.id = pointages_infos.id_pointages
                  WHERE pointages_infos.etudiant_nom =:nom_etudiants";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":nom_etudiants", $nom_etudiants, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error get pointages info: " . $e->getMessage());
        }
    }
    
    public function getPointagesInfoByCentreId($centreId) {
        // Requête SQL pour récupérer les informations de pointage liées à un centre spécifique
        $query = "SELECT pointages_infos.*, pointages.date, pointages.entree_sortie, pointages.id_etudiants 
                  FROM pointages_infos
                  RIGHT JOIN pointages ON pointages.id = pointages_infos.id_pointages
                  WHERE pointages_infos.id_centres_de_formation = :centreId";
        
        try {
            // Préparer la requête
            $stmt = $this->db->prepare($query);
            
            // Lier le paramètre :centreId à la valeur $centreId
            $stmt->bindParam(":centreId", $centreId, PDO::PARAM_INT);
            
            // Exécuter la requête
            $stmt->execute();
            
            // Récupérer tous les résultats
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Retourner les résultats
            return $result;
        } catch (PDOException $e) {
            // En cas d'erreur, lever une exception avec un message d'erreur clair
            throw new Exception("Error get pointages info: " . $e->getMessage());
        }
    }
    

}
