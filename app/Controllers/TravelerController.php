<?php

class TravelerController {
    
    public function dashboard() {
        $user = Auth::user();
        
        if (!$user || !$user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('traveler/dashboard', [
            'user' => $user,
            'currentPage' => 'dashboard',
        ], 'traveler');

        $view->render();
    }

    public function trips() {
        $user = Auth::user();
        
        if (!$user || !$user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('traveler/trips', [
            'user' => $user,
            'currentPage' => 'trips',
        ], 'traveler');

        $view->render();
    }

    public function chats() {
        $user = Auth::user();
        
        if (!$user || !$user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('traveler/chats', [
            'user' => $user,
            'currentPage' => 'chats',
        ], 'traveler');

        $view->render();
    }

    public function settings() {
        $user = Auth::user();
        
        if (!$user || !$user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('traveler/settings', [
            'user' => $user,
            'currentPage' => 'settings',
        ], 'traveler');

        $view->render();
    }
}
