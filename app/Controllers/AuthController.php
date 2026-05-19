<?php

/**
 * Controller for authentication (login/logout and password change)
 */
class AuthController
{

    /**
     * Show the login page
     */
    public function showLogin(): void
    {
        // Display the view with the auth layout (without header/footer)
        $view = new View('auth/login', [
            'pageTitle' => 'Connexion - Vadrouille & Bourlingue'
        ], 'auth');

        $view->render();
    }

    /**
     * Process the login form (POST)
     */
    public function login(): void
    {

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

            // Check if user must change password
            if ($user->mustChangePassword()) {
                $_SESSION['change_pwd_required'] = 'Vous devez modifier votre mot de passe avant de continuer.';
                header('Location: ' . BASE_URL . '/change-password');
                exit;
            }

            // Redirect based on user role
            if ($user->isAdmin()) {
                header('Location: ' . BASE_URL . '/admin/requests');
                exit;
            } elseif ($user->isTraveler()) {
                header('Location: ' . BASE_URL . '/traveler/trips');
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
    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /**
     * Show the change password form (for users who must change their password)
     */
    public function showChangePassword(): void
    {
        // Ensure user is logged in
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        $view = new View('auth/change_password', [
            'pageTitle' => 'Changement de mot de passe obligatoire - Vadrouille & Bourlingue',
            'user' => $user
        ], 'auth');

        $view->render();
    }

    /**
     * Process the change password form (POST)
     */
    public function changePassword(): void
    {
        // Ensure user is logged in
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate inputs
        if (
            !DataVerification::isNotEmpty($currentPassword) ||
            !DataVerification::isNotEmpty($newPassword) ||
            !DataVerification::isNotEmpty($confirmPassword)
        ) {
            $_SESSION['change_pwd_error'] = 'Tous les champs sont obligatoires.';
            header('Location: ' . BASE_URL . '/change-password');
            exit;
        }

        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            $_SESSION['change_pwd_error'] = 'Les nouveaux mots de passe ne correspondent pas.';
            header('Location: ' . BASE_URL . '/change-password');
            exit;
        }

        // Validate password strength (minimum 8 characters with at least one number, one letter and one special character)
        if (strlen($newPassword) < 8 || !preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/\d/', $newPassword) || !preg_match('/[\W_]/', $newPassword)) {
            $_SESSION['change_pwd_error'] = 'Le mot de passe doit contenir au moins 8 caractères, dont au moins une lettre, un chiffre et un caractère spécial.';
            header('Location: ' . BASE_URL . '/change-password');
            exit;
        }

        // Verify current password
        $userModel = new UserModel();
        if (!password_verify($currentPassword, $user->getPasswordHash())) {
            $_SESSION['change_pwd_error'] = 'Le mot de passe actuel est incorrect.';
            header('Location: ' . BASE_URL . '/change-password');
            exit;
        }

        // Update password
        $success = $userModel->updatePassword($user->getId(), $newPassword);

        if ($success) {
            $_SESSION['change_pwd_success'] = 'Votre mot de passe a été modifié avec succès.';

            // Redirect based on user role
            if ($user->isAdmin()) {
                header('Location: ' . BASE_URL . '/admin/requests');
                exit;
            } elseif ($user->isTraveler()) {
                header('Location: ' . BASE_URL . '/traveler/trips');
                exit;
            }
        } else {
            $_SESSION['change_pwd_error'] = 'Une erreur est survenue lors de la modification du mot de passe.';
            header('Location: ' . BASE_URL . '/change-password');
            exit;
        }
    }
}
