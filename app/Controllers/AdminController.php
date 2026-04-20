<?php

class AdminController {
    
    private $user;
    private $sharedData = [];

    /**
     * Constructeur - Initialise les données communes à toutes les pages admin
     */
    public function __construct() {
        $this->user = Auth::user();
        
        // Vérification admin globale
        if (!$this->user || !$this->user->isAdmin()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        // Données communes au sidebar (disponibles dans toutes les vues)
        $contactRequestModel = new ContactRequestModel();
        $this->sharedData = [
            'user' => $this->user,
            'newRequestsCount' => $contactRequestModel->countByStatus('new')
        ];
    }

    /**
     * Helper pour rendre une vue admin avec les données partagées
     * @param string $template Chemin du template (ex: 'admin/dashboard')
     * @param array $data Données spécifiques à la vue
     */
    private function renderAdminView(string $template, array $data = []): void {
        // Merge des données partagées + données spécifiques
        $viewData = array_merge($this->sharedData, $data);
        
        $view = new View($template, $viewData, 'admin');
        $view->render();
    }
    
    public function dashboard() {
        $this->renderAdminView('admin/dashboard', [
            'currentPage' => 'dashboard'
        ]);
    }

    public function requests() {
        $contactRequestModel = new ContactRequestModel();
        $requests = $contactRequestModel->getAllRequests();

        $this->renderAdminView('admin/requests', [
            'requests' => $requests,
            'currentPage' => 'requests'
        ]);
    }

    public function viewRequest($id) {
        $contactRequestModel = new ContactRequestModel();
        $request = $contactRequestModel->getRequestById($id);

        if (!$request) {
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }

        $this->renderAdminView('admin/request_detail', [
            'request' => $request,
            'currentPage' => 'requests'
        ]);
    }

    public function updateRequestStatus($id) {
        $newStatus = $_POST['status'] ?? null;
        if (!in_array($newStatus, ['new', 'studying', 'quoted', 'accepted', 'refused', 'archived'])) {
            header('Location: ' . BASE_URL . '/admin/requests/' . $id);
            exit;
        }

        $contactRequestModel = new ContactRequestModel();
        $contactRequestModel->updateStatus($id, $newStatus);

        header('Location: ' . BASE_URL . '/admin/requests/' . $id);
        exit;
    }

    public function trips() {
        $this->renderAdminView('admin/trips', [
            'currentPage' => 'trips'
        ]);
    }

    public function chats() {
        $this->renderAdminView('admin/chats', [
            'currentPage' => 'chats'
        ]);
    }
}