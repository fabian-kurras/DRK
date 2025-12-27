<?php
/**
 * Page-Klasse - Verwaltung von statischen Seiten
 */
class Page {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Seite nach Slug abrufen
     */
    public function getBySlug($slug) {
        $query = "
            SELECT p.*, u.full_name as author_name
            FROM pages p
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.slug = :slug AND p.is_published = 1
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Seite nach ID abrufen
     */
    public function getById($id) {
        $query = "
            SELECT p.*, u.full_name as author_name
            FROM pages p
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Alle Seiten abrufen
     */
    public function getAll() {
        $query = "
            SELECT p.*, u.full_name as author_name
            FROM pages p
            LEFT JOIN users u ON p.author_id = u.id
            ORDER BY p.title ASC
        ";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    /**
     * Neue Seite erstellen
     */
    public function create($slug, $title, $content, $authorId) {
        $query = "
            INSERT INTO pages (slug, title, content, author_id)
            VALUES (:slug, :title, :content, :author_id)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Seite aktualisieren
     */
    public function update($id, $title, $content) {
        $query = "
            UPDATE pages 
            SET title = :title, content = :content
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        
        return $stmt->execute();
    }
    
    /**
     * Seite löschen
     */
    public function delete($id) {
        $query = "DELETE FROM pages WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
