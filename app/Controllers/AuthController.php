<?php
class AuthController {
    
    /**
     * Show the login page
     */
    public function showLogin(): void {
        // Display the view with the auth layout (without header/footer)
        $view = new View('auth/login', [
            'pageTitle' => 'Connexion - Vadrouille & Bourlingue'
        ], 'auth');
        
        $view->render();
    }
    
    /**
     * Process the login form (POST)
     */
    public function login(): void {
        // TODO: Login logic
        // Check credentials, create session, redirect
        
        // For now, just display a message
        echo "Connexion en cours...";
    }
    
    /**
     * Logout
     */
    public function logout(): void {
        // TODO: Destroy the session
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
