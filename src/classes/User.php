<?php
/**
 * User-Klasse - Authentifizierung und Benutzerverwaltung
 */
class User {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Benutzer nach Username abrufen
     */
    public function getByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Benutzer nach ID abrufen
     */
    public function getById($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Alle Benutzer abrufen
     */
    public function getAll() {
        $query = "SELECT id, username, email, full_name, role, is_active, created_at FROM users ORDER BY created_at DESC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    /**
     * Benutzer authentifizieren
     */
    public function login($username, $password) {
        $user = $this->getByUsername($username);
        
        if ($user && $user['is_active'] && password_verify($password, $user['password'])) {
            // Last-Login aktualisieren
            $this->updateLastLogin($user['id']);
            
            // Session setzen
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        return true;
    }
    
    /**
     * Neuen Benutzer erstellen
     */
    public function create($username, $email, $password, $fullName, $role = 'editor') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $query = "
            INSERT INTO users (username, email, password, full_name, role)
            VALUES (:username, :email, :password, :full_name, :role)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':role', $role);
        
        return $stmt->execute();
    }
    
    /**
     * Passwort aktualisieren
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $query = "UPDATE users SET password = :password WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }
    
    /**
     * Last-Login aktualisieren
     */
    private function updateLastLogin($userId) {
        $query = "UPDATE users SET last_login = NOW() WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Benutzer deaktivieren/aktivieren
     */
    public function toggleActive($userId) {
        $query = "UPDATE users SET is_active = NOT is_active WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
