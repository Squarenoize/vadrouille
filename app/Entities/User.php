<?php
/**
 * User Entity
 * Represents a user (admin or regular user) in the system
 */
class User {
    
    private ?int $id = null;
    private string $email;
    private string $passwordHash;
    private string $firstName;
    private string $lastName;
    private ?string $phone = null;
    private string $role;
    private bool $isActive = true;
    private int $failedAttempts = 0;
    private ?string $lockedUntil = null;
    private bool $mustChangePassword = false;
    private bool $emailNotifications = true;
    private ?string $createdAt = null;
    private ?string $updatedAt = null;
    
    /**
     * Create a User instance from database array
     */
    public static function fromArray(array $data): self {
        $instance = new self();
        
        if (isset($data['id'])) {
            $instance->id = (int)$data['id'];
        }
        
        $instance->email = $data['email'] ?? '';
        $instance->passwordHash = $data['password_hash'] ?? '';
        $instance->firstName = $data['first_name'] ?? '';
        $instance->lastName = $data['last_name'] ?? '';
        $instance->phone = $data['phone'] ?? null;
        $instance->role = $data['role'] ?? 'user';
        $instance->isActive = (bool)($data['is_active'] ?? true);
        $instance->failedAttempts = (int)($data['failed_attempts'] ?? 0);
        $instance->lockedUntil = $data['locked_until'] ?? null;
        $instance->mustChangePassword = (bool)($data['must_change_pwd'] ?? false);
        $instance->emailNotifications = (bool)($data['email_notif'] ?? true);
        $instance->createdAt = $data['created_at'] ?? null;
        $instance->updatedAt = $data['updated_at'] ?? null;
        
        return $instance;
    }
    
    /**
     * Convert User to array (useful for sessions, serialization)
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'role' => $this->role,
            'is_active' => $this->isActive,
            'failed_attempts' => $this->failedAttempts,
            'locked_until' => $this->lockedUntil,
            'must_change_pwd' => $this->mustChangePassword,
            'email_notif' => $this->emailNotifications,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
    
    // === Getters ===
    
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function getPasswordHash(): string {
        return $this->passwordHash;
    }
    
    public function getFirstName(): string {
        return $this->firstName;
    }
    
    public function getLastName(): string {
        return $this->lastName;
    }
    
    public function getPhone(): ?string {
        return $this->phone;
    }
    
    public function getRole(): string {
        return $this->role;
    }
    
    public function isActive(): bool {
        return $this->isActive;
    }
    
    public function getFailedAttempts(): int {
        return $this->failedAttempts;
    }
    
    public function getLockedUntil(): ?string {
        return $this->lockedUntil;
    }
    
    public function mustChangePassword(): bool {
        return $this->mustChangePassword;
    }
    
    public function hasEmailNotifications(): bool {
        return $this->emailNotifications;
    }
    
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }
    
    // === Setters ===
    
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    
    public function setPasswordHash(string $passwordHash): void {
        $this->passwordHash = $passwordHash;
    }
    
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }
    
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }
    
    public function setPhone(?string $phone): void {
        $this->phone = $phone;
    }
    
    public function setRole(string $role): void {
        $this->role = $role;
    }
    
    public function setIsActive(bool $isActive): void {
        $this->isActive = $isActive;
    }
    
    public function setFailedAttempts(int $failedAttempts): void {
        $this->failedAttempts = $failedAttempts;
    }
    
    public function setLockedUntil(?string $lockedUntil): void {
        $this->lockedUntil = $lockedUntil;
    }
    
    public function setMustChangePassword(bool $mustChangePassword): void {
        $this->mustChangePassword = $mustChangePassword;
    }
    
    public function setEmailNotifications(bool $emailNotifications): void {
        $this->emailNotifications = $emailNotifications;
    }
    
    // === Business methods ===
    
    /**
     * Get user's full name
     */
    public function getFullName(): string {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    /**
     * Get initials (for avatar)
     */
    public function getInitials(): string {
        $initials = '';
        if (!empty($this->firstName)) {
            $initials .= strtoupper($this->firstName[0]);
        }
        if (!empty($this->lastName)) {
            $initials .= strtoupper($this->lastName[0]);
        }
        return $initials;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is traveler (regular user)
     */
    public function isTraveler(): bool {
        return $this->role === 'traveler';
    }
    
    /**
     * Check if account is locked
     */
    public function isLocked(): bool {
        if ($this->lockedUntil === null) {
            return false;
        }
        
        $lockedUntilTime = strtotime($this->lockedUntil);
        return $lockedUntilTime > time();
    }
    
    /**
     * Check if user can login
     */
    public function canLogin(): bool {
        return $this->isActive && !$this->isLocked();
    }
}
