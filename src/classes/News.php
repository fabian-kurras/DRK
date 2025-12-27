<?php
/**
 * News-Klasse - Verwaltung von Nachrichten
 */
class News {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Alle veröffentlichten Nachrichten abrufen
     */
    public function getAllPublished($limit = 10) {
        $query = "
            SELECT n.*, u.full_name as author_name
            FROM news n
            JOIN users u ON n.author_id = u.id
            WHERE n.is_published = 1
            ORDER BY n.published_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Nachricht nach ID abrufen
     */
    public function getById($id) {
        $query = "
            SELECT n.*, u.full_name as author_name
            FROM news n
            JOIN users u ON n.author_id = u.id
            WHERE n.id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Alle Nachrichten abrufen (Admin)
     */
    public function getAll($limit = 20, $offset = 0) {
        $query = "
            SELECT n.*, u.full_name as author_name
            FROM news n
            JOIN users u ON n.author_id = u.id
            ORDER BY n.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Neue Nachricht erstellen
     */
    public function create($title, $content, $excerpt, $authorId, $imageUrl = null) {
        $query = "
            INSERT INTO news (title, content, excerpt, author_id, image_url)
            VALUES (:title, :content, :excerpt, :author_id, :image_url)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':excerpt', $excerpt);
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $imageUrl);
        
        return $stmt->execute();
    }
    
    /**
     * Nachricht aktualisieren
     */
    public function update($id, $title, $content, $excerpt, $imageUrl = null) {
        $query = "
            UPDATE news 
            SET title = :title, content = :content, excerpt = :excerpt, image_url = :image_url
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':excerpt', $excerpt);
        $stmt->bindParam(':image_url', $imageUrl);
        
        return $stmt->execute();
    }
    
    /**
     * Nachricht veröffentlichen
     */
    public function publish($id) {
        $query = "
            UPDATE news 
            SET is_published = 1, published_at = NOW()
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Nachricht löschen
     */
    public function delete($id) {
        $query = "DELETE FROM news WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Anzahl der Nachrichten abrufen
     */
    public function count() {
        $query = "SELECT COUNT(*) as count FROM news";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }
}
