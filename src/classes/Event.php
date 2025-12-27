<?php
/**
 * Events-Klasse - Verwaltung von Veranstaltungen
 */
class Event {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Alle veröffentlichten Veranstaltungen abrufen
     */
    public function getAllPublished($limit = 10) {
        $query = "
            SELECT e.*, u.full_name as organizer_name
            FROM events e
            JOIN users u ON e.organizer_id = u.id
            WHERE e.is_published = 1 AND e.event_date >= CURDATE()
            ORDER BY e.event_date ASC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Veranstaltung nach ID abrufen
     */
    public function getById($id) {
        $query = "
            SELECT e.*, u.full_name as organizer_name
            FROM events e
            JOIN users u ON e.organizer_id = u.id
            WHERE e.id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Alle Veranstaltungen abrufen (Admin)
     */
    public function getAll($limit = 20, $offset = 0) {
        $query = "
            SELECT e.*, u.full_name as organizer_name
            FROM events e
            JOIN users u ON e.organizer_id = u.id
            ORDER BY e.event_date DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Neue Veranstaltung erstellen
     */
    public function create($title, $description, $eventDate, $eventTime, $location, $organizerId, $imageUrl = null) {
        $query = "
            INSERT INTO events (title, description, event_date, event_time, location, organizer_id, image_url)
            VALUES (:title, :description, :event_date, :event_time, :location, :organizer_id, :image_url)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':event_date', $eventDate);
        $stmt->bindParam(':event_time', $eventTime);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':organizer_id', $organizerId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $imageUrl);
        
        return $stmt->execute();
    }
    
    /**
     * Veranstaltung aktualisieren
     */
    public function update($id, $title, $description, $eventDate, $eventTime, $location, $imageUrl = null) {
        $query = "
            UPDATE events 
            SET title = :title, description = :description, event_date = :event_date, 
                event_time = :event_time, location = :location, image_url = :image_url
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':event_date', $eventDate);
        $stmt->bindParam(':event_time', $eventTime);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':image_url', $imageUrl);
        
        return $stmt->execute();
    }
    
    /**
     * Veranstaltung veröffentlichen
     */
    public function publish($id) {
        $query = "
            UPDATE events 
            SET is_published = 1
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Veranstaltung löschen
     */
    public function delete($id) {
        $query = "DELETE FROM events WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
