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
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        // Validate input
        if (!DataVerification::isValidEmail($email) || !DataVerification::isNotEmpty($password)) {
            $_SESSION['connect_error'] = 'Veuillez entrer un email valide et un mot de passe.';
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        $userModel = new UserModel();
        $user = $userModel->login($email, $password);
        if ($user) {
            // Store only user ID in session
            Auth::login($user);

            // Redirect based on user role
            if ($user->isAdmin()) {
                header('Location: ' . BASE_URL . '/admin/requests');
                exit;
            } elseif ($user->isTraveler()) {
                header('Location: ' . BASE_URL . '/traveler/dashboard');
                exit;
            } else {
                $_SESSION['connect_error'] = 'Rôle utilisateur non reconnu.';
                header('Location: ' . BASE_URL . '/connexion');
                exit;
            }

        } else {
            $_SESSION['connect_error'] = 'Email ou mot de passe incorrect.';
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

    }
    
    /**
     * Logout
     */
    public function logout(): void {
        Auth::logout();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
