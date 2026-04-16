<?php

class TravelerController {
    
    public function dashboard() {
        $user = Auth::user();
        
        if (!$user || !$user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('traveler/dashboard', [
            'user' => $user
        ], 'traveler');

        $view->render();
    }
}
