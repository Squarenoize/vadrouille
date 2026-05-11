<?php
/**
 * Auth Helper
 * Manages user authentication and session
 */
class Auth {
    
    /**
     * Get the currently logged-in user
     * @return User|null
     */
    public static function user(): ?User {
        if (!isset($_SESSION['userId'])) {
            return null;
        }
        
        $userModel = new UserModel();
        return $userModel->findById($_SESSION['userId']);
    }
    
    /**
     * Check if user is logged in
     */
    public static function check(): bool {
        return isset($_SESSION['userId']);
    }
    
    /**
     * Check if user is guest (not logged in)
     */
    public static function guest(): bool {
        return !self::check();
    }
    
    /**
     * Log in a user
     */
    public static function login(User $user): void {
        $_SESSION['userId'] = $user->getId();
    }
    
    /**
     * Log out the current user
     */
    public static function logout(): void {
        unset($_SESSION['userId']);
        session_destroy();
    }
    
    /**
     * Get user ID
     */
    public static function id(): ?int {
        return $_SESSION['userId'] ?? null;
    }
    
    /**
     * Check if current user is admin
     */
    public static function isAdmin(): bool {
        $user = self::user();
        return $user && $user->isAdmin();
    }
    
    /**
     * Check if current user is traveler
     */
    public static function isTraveler(): bool {
        $user = self::user();
        return $user && $user->isTraveler();
    }
    
    /**
     * Require authentication (redirect if not logged in)
     */
    public static function requireAuth(string $redirectUrl = '/connexion'): void {
        if (self::guest()) {
            header('Location: ' . BASE_URL . $redirectUrl);
            exit;
        }
    }
    
    /**
     * Require admin role (redirect if not admin)
     */
    public static function requireAdmin(string $redirectUrl = '/'): void {
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . $redirectUrl);
            exit;
        }
    }
}
