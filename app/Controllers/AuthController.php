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
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userFirstName'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['connected'] = true;

            // Redirect based on user role
            switch ($_SESSION['role']) {
                case 'admin':
                    $view = new View('admin/dashboard', [
                        'userFirstName' => $user['first_name']
                    ], 'admin');
                    break;
                
                case 'user':
                    $view = new View('user/dashboard', [
                        'userFirstName' => $user['first_name']
                    ], 'user');
                    break;
                
                default:
                    $_SESSION['connect_error'] = 'Rôle utilisateur non reconnu.';
                    header('Location: ' . BASE_URL . '/connexion');
                    exit;
            }

            $view->render();

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
        // TODO: Destroy the session
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
