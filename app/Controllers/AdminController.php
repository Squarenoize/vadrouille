<?php

class AdminController {
    
    public function dashboard() {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        $view = new View('admin/dashboard', [
            'user' => $user,
            'currentPage' => 'dashboard'
        ], 'admin');

        $view->render();
    }

    public function requests() {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }
        
        //$requestModel = new RequestModel();
        //$requests = $requestModel->getAllRequests();

        $view = new View('admin/requests', [
            'user' => $user,
            //'requests' => $requests,
            'currentPage' => 'requests'
        ], 'admin');

        $view->render();
    }
}