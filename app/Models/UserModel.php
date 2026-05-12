<?php
/**
 * UserModel handles database operations for users
 * It provides methods to create, retrieve, and update users
 * Table users in DB
 */
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

    /**
     * Create a new user
     * @param User $user
     * @return int|false The new user ID on success, false on failure
     */
    public function create(User $user): int|false {
        $sql = "INSERT INTO users (email, password_hash, first_name, last_name, phone, role, must_change_pwd) 
                VALUES (:email, :password_hash, :first_name, :last_name, :phone, :role, :must_change_pwd)";
        $stmt = $this->db->prepare($sql);
        
        $success = $stmt->execute([
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
            'must_change_pwd' => $user->mustChangePassword() ? 1 : 0
        ]);
        
        return $success ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Update user password and remove must_change_pwd flag
     * @param int $userId
     * @param string $newPassword
     * @return bool Success status
     */
    public function updatePassword(int $userId, string $newPassword): bool {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare('
            UPDATE users 
            SET password_hash = :password_hash, 
                must_change_pwd = 0,
                updated_at = NOW()
            WHERE id = :id
        ');
        
        return $stmt->execute([
            'password_hash' => $passwordHash,
            'id' => $userId
        ]);
    }

    /**
     * Find user by email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            return User::fromArray($userData);
        }
        
        return null;
    }

    public function getCommonUserSettings(int $userId) {
        $stmt = $this->db->prepare('SELECT email, first_name, last_name, phone, email_notif  FROM users WHERE id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ?: null;
    }

    public function updateUserSettings(int $userId, string $email, string $firstName, string $lastName, ?string $phone, bool $emailNotif): bool {
        $stmt = $this->db->prepare('
            UPDATE users 
            SET email = :email,
                first_name = :first_name,
                last_name = :last_name,
                phone = :phone,
                email_notif = :email_notif,
                updated_at = NOW()
            WHERE id = :id
        ');
        
        return $stmt->execute([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email_notif' => $emailNotif ? 1 : 0,
            'id' => $userId
        ]);
    }

}