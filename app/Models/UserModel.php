<?php

Class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find user by ID
     * @return User|null
     */
    public function findById(int $id): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            return User::fromArray($userData);
        }
        
        return null;
    }

    /**
     * Login method to authenticate user
     * @return User|null User entity if authentication successful, null otherwise
     */
    public function login(string $email, string $password): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData && password_verify($password, $userData['password_hash'])) {
            $user = User::fromArray($userData);
            
            // Check if user can login (active and not locked)
            if (!$user->canLogin()) {
                return null;
            }
            
            return $user;
        }
        
        return null;
    }
}