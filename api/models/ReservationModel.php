<?php
require_once 'db.php';

/*=========================================================================================
ReservationModel.php => Résumé des fonctions
===========================================================================================
> addReservation() : Ajoute une réservation à une session
> getPlacesRestantes() : Renvoie le nombre de places restantes pour une session
===========================================================================================*/

class Reservation extends Database {
    public $id;
    public $nb_place; 
    public $message;
    public $financeur_entreprise_id;
    public $financeur_entreprise_nom; 
    // Clé étrangère
    public $id_conseillers_financeurs;
    public $id_session;
    public $id_reservations_statut;

    public function addReservation(){
        $query = "INSERT INTO `reservations` (`nb_place`,`message`, `financeur_entreprise_id`, `financeur_entreprise_nom`, `id_conseillers_financeurs`, `id_session`, `id_reservations_statut`) 
                VALUES (:nb_place , :message, :financeur_entreprise_id, :financeur_entreprise_nom, :id_conseillers_financeurs, :id_session, :id_reservations_statut)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":nb_place", $this->nb_place, PDO::PARAM_INT);
            $stmt->bindParam(":message", $this->message, PDO::PARAM_STR);
            $stmt->bindParam(":financeur_entreprise_id", $this->financeur_entreprise_id, PDO::PARAM_INT);
            $stmt->bindParam(":financeur_entreprise_nom", $this->financeur_entreprise_nom, PDO::PARAM_STR);
            $stmt->bindParam(":id_conseillers_financeurs", $this->id_conseillers_financeurs, PDO::PARAM_INT);
            $stmt->bindParam(":id_session", $this->id_session, PDO::PARAM_INT);
            $stmt->bindParam(":id_reservations_statut", $this->id_reservations_statut, PDO::PARAM_INT);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Throw a new exception or the caught one to be handled outside
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function getPlacesRestantes() {
        $query = "SELECT session.nbPlace - COALESCE(SUM(reservations.nb_place), 0) AS remaining_places
        FROM session
        LEFT JOIN reservations ON session.id = reservations.id_session AND reservations.id_reservations_statut = 3
        WHERE session.id = :id_session
        GROUP BY session.nbPlace";

        try{
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }  catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function getSessionInfos() {
        $query = "SELECT session.id, session.dateDebut, session.dateFin, session.nomSession, session.nbPlace AS nb_place_max, session.nbPlace - COALESCE(SUM(reservations.nb_place), 0) AS remaining_places, session.id_centres_de_formation,
        centres_de_formation.nomCentre, 
        formations.id AS formations_id, formations.nom AS formations_nom, formations.prix AS formations_prix, formations.lienFranceCompetence AS formations_lienFranceCompetence
        FROM session
        JOIN formations ON formations.id = session.id_formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        LEFT JOIN reservations ON session.id = reservations.id_session AND reservations.id_reservations_statut = 3
        WHERE session.id = 8
        GROUP BY session.nbPlace";

        try{
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }  catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }


    public function getReservationListeForSession(){
        $query = "SELECT reservations.*,
            reservations_statut.nom_statut
            FROM reservations
            JOIN reservations_statut ON reservations.id_reservations_statut = reservations_statut.id
            WHERE reservations.id_session = :id_session";
        
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function getReservationListeForFinanceurEntreprise(){
        $query = "SELECT reservations.*,
        reservations_statut.nom_statut,
        centres_de_formation.id AS centres_de_formation_id, centres_de_formation.nomCentre,
        session.nomSession, session.dateDebut, session.dateFin,
        formations.nom AS formations_nom, formations.lienFranceCompetence AS formations_lienFranceCompetence, formations.prix AS formations_prix
        FROM reservations
        JOIN reservations_statut ON reservations.id_reservations_statut = reservations_statut.id
        JOIN session ON session.id = reservations.id_session
        JOIN formations ON formations.id = session.id_formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        WHERE reservations.financeur_entreprise_id = :financeur_entreprise_id";
        
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":financeur_entreprise_id", $this->financeur_entreprise_id, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function getReservationListeForFinanceurEntreprise_byCentre($centreId){
        $query = "SELECT reservations.*,
        reservations_statut.nom_statut,
        centres_de_formation.id AS centres_de_formation_id, centres_de_formation.nomCentre,
        session.nomSession, session.dateDebut, session.dateFin,
        formations.nom AS formations_nom, formations.lienFranceCompetence AS formations_lienFranceCompetence, formations.prix AS formations_prix
        FROM reservations
        JOIN reservations_statut ON reservations.id_reservations_statut = reservations_statut.id
        JOIN session ON session.id = reservations.id_session
        JOIN formations ON formations.id = session.id_formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        WHERE reservations.financeur_entreprise_id = :financeur_entreprise_id
        AND centres_de_formation.id = :centreId";
        
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":financeur_entreprise_id", $this->financeur_entreprise_id, PDO::PARAM_INT);
            $queryexec->bindValue(":centreId", $centreId, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function financeur_boolHasSession() {
        require_once 'db.php';
    
        $query = "SELECT session.id
                    FROM session 
                    WHERE session.financeur_entreprise_id = :financeur_entreprise_id
                    AND session.id = :id_session";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":financeur_entreprise_id", $this->financeur_entreprise_id, PDO::PARAM_INT);
            $stmt->bindParam(":id_session", $id_session, PDO::PARAM_INT);
            $stmt->execute();
    
            $count = $stmt->fetchColumn();
    
            return ($count >= 1);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }


    public function getReservationDetails(){
        $query = "SELECT reservations.*,
            reservations_statut.nom_statut,
            centres_de_formation.id AS centres_de_formation_id, centres_de_formation.nomCentre,
            session.nomSession, session.dateDebut, session.dateFin,
            formations.nom AS formations_nom, formations.lienFranceCompetence AS formations_lienFranceCompetence, formations.prix AS formations_prix
            FROM reservations
            JOIN reservations_statut ON reservations.id_reservations_statut = reservations_statut.id
            JOIN session ON session.id = reservations.id_session
            JOIN formations ON formations.id = session.id_formations
            JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
            WHERE reservations.id = :id";
        
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetch(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }    
    }

    public function updateNbPlace() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE reservations
                    SET nb_place = :nb_place 
                    WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nb_place', $this->nb_place, PDO::PARAM_INT);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateMessage() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE reservations
                    SET message = :message 
                    WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':message', $this->message, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateStatut() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE reservations
                    SET id_reservations_statut = :id_reservations_statut 
                    WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_reservations_statut', $this->id_reservations_statut, PDO::PARAM_INT);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(){
        require_once 'db.php';
        $query = "DELETE FROM reservations WHERE id = :id";

        try{
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
            return ($queryexec->execute());
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}