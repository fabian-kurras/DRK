<?php
/**
 * BloodDonation-Klasse - Blutspende-Termine Verwaltung
 */

class BloodDonation {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Neuen Blutspendetermin erstellen
     */
    public function create($date, $time, $location, $organizer = 'Admin') {
        try {
            // Überprüfe ob Tabelle existiert
            try {
                $check = $this->db->query("SHOW TABLES LIKE 'blood_donations'");
                if ($check->rowCount() == 0) {
                    error_log('BloodDonation: Tabelle blood_donations existiert nicht!');
                    return false;
                }
            } catch (Exception $e) {
                error_log('BloodDonation: Fehler beim Prüfen der Tabelle: ' . $e->getMessage());
                return false;
            }
            
            // Validiere Eingaben
            if (empty($date) || empty($time) || empty($location)) {
                error_log('BloodDonation: Leere Felder - date:' . $date . ' time:' . $time . ' location:' . $location);
                return false;
            }
            
            $stmt = $this->db->prepare('
                INSERT INTO blood_donations 
                (donation_date, donation_time, location, organizer, created_at) 
                VALUES (:date, :time, :location, :organizer, NOW())
            ');
            
            $result = $stmt->execute([
                ':date' => $date,
                ':time' => $time,
                ':location' => $location,
                ':organizer' => $organizer
            ]);
            
            if (!$result) {
                error_log('BloodDonation::create - Execute fehlgeschlagen: ' . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('BloodDonation::create PDOException - ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log('BloodDonation::create Exception - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Alle Blutspendetermine abrufen
     */
    public function getAll($limit = 50) {
        try {
            // Überprüfe ob Tabelle existiert
            $check = $this->db->query("SHOW TABLES LIKE 'blood_donations'");
            if (!$check || $check->rowCount() == 0) {
                error_log('BloodDonation: Tabelle blood_donations existiert nicht!');
                return [];
            }
            
            $stmt = $this->db->prepare('
                SELECT * FROM blood_donations 
                ORDER BY donation_date ASC, donation_time ASC
                LIMIT :limit
            ');
            
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('BloodDonation::getAll - ' . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log('BloodDonation::getAll - ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Bevorstehende Blutspendetermine (ab heute)
     */
    public function getUpcoming($limit = 10) {
        try {
            // Überprüfe ob Tabelle existiert
            $check = $this->db->query("SHOW TABLES LIKE 'blood_donations'");
            if (!$check || $check->rowCount() == 0) {
                error_log('BloodDonation: Tabelle blood_donations existiert nicht!');
                return [];
            }
            
            $stmt = $this->db->prepare('
                SELECT * FROM blood_donations 
                WHERE donation_date >= CURDATE()
                ORDER BY donation_date ASC, donation_time ASC
                LIMIT :limit
            ');
            
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('BloodDonation::getUpcoming - ' . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log('BloodDonation::getUpcoming - ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Blutspendetermin nach ID abrufen
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM blood_donations 
                WHERE id = :id
            ');
            
            $stmt->execute([':id' => (int)$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Blutspendetermin aktualisieren
     */
    public function update($id, $date, $time, $location) {
        try {
            $stmt = $this->db->prepare('
                UPDATE blood_donations 
                SET donation_date = :date, 
                    donation_time = :time, 
                    location = :location
                WHERE id = :id
            ');
            
            return $stmt->execute([
                ':id' => (int)$id,
                ':date' => $date,
                ':time' => $time,
                ':location' => $location
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Blutspendetermin löschen
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare('DELETE FROM blood_donations WHERE id = :id');
            return $stmt->execute([':id' => (int)$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
