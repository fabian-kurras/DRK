<?php
/**
 * Contact-Klasse - Kontaktformular-Verwaltung
 */
class Contact {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Neue Kontaktanfrage speichern
     */
    public function create($name, $email, $phone, $subject, $message) {
        $query = "
            INSERT INTO contacts (name, email, phone, subject, message)
            VALUES (:name, :email, :phone, :subject, :message)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        
        return $stmt->execute();
    }
    
    /**
     * Alle Kontaktanfragen abrufen
     */
    public function getAll($limit = 50, $offset = 0) {
        $query = "
            SELECT * FROM contacts
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Kontaktanfrage nach ID abrufen
     */
    public function getById($id) {
        $query = "SELECT * FROM contacts WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Kontaktanfrage als gelesen markieren
     */
    public function markAsRead($id) {
        $query = "UPDATE contacts SET is_read = 1 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Kontaktanfrage als beantwortet markieren
     */
    public function markAsAnswered($id) {
        $query = "UPDATE contacts SET is_answered = 1 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Kontaktanfrage löschen
     */
    public function delete($id) {
        $query = "DELETE FROM contacts WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Anzahl ungelesener Anfragen
     */
    public function countUnread() {
        $query = "SELECT COUNT(*) as count FROM contacts WHERE is_read = 0";
        
        $stmt = $this->db->query($query);
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }
}
