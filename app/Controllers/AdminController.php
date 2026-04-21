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
        $tripModel = new TripsModel();
        $this->sharedData = [
            'user' => $this->user,
            'newRequestsCount' => $contactRequestModel->countByStatus('new'),
            'draftTripsCount' => $tripModel->countByStatus('draft'),
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
        $tripModel = new TripsModel();
        $trips = $tripModel->getAllTrips();

        $this->renderAdminView('admin/trips', [
            'trips' => $trips,
            'currentPage' => 'trips'
        ]);
    }

    public function newTripFromRequest($requestId) {
        // Logique pour créer un voyage à partir d'une demande de contact
        // (Récupérer la demande, pré-remplir un formulaire de création de voyage, etc.)
        
        $contactRequestModel = new ContactRequestModel();
        $request = $contactRequestModel->getRequestById($requestId);

        if (!$request) {
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }

        $this->renderAdminView('admin/newTrip', [
            'request' => $request,
            'currentPage' => 'trips'
        ]);
    }

    public function createTrip() {
        // 1. Create the entity from POST data
        $newTrip = Trip::fromArray($_POST);
        // 2. Validate the entity (all logic is in the entity)
        $errors = $newTrip->validate();
        // 3. If errors, re-display the form
        if (!empty($errors)) {
            // Récupérer la request pour ré-afficher le formulaire
            $requestId = $_POST['requestId'] ?? null;
            $request = null;
            if ($requestId) {
                $contactRequestModel = new ContactRequestModel();
                $request = $contactRequestModel->getRequestById($requestId);
            }
            
            $this->renderAdminView('admin/newTrip', [
                'errors' => $errors,
                'formData' => $_POST,
                'request' => $request, // ✅ Maintenant $request est disponible
                'currentPage' => 'trips'
            ]);
            return;
        }
        // 4. Validation OK : Save via the Model (non implémenté ici)
        $tripModel = new TripsModel();
        $tripModel->save($newTrip);

        // Redirection après création (pattern PRG - Post/Redirect/Get)
        header('Location: ' . BASE_URL . '/admin/trips');
        exit;
    }

    public function viewTrip($id) {
        // Logique pour afficher le détail d'un voyage
        // (Récupérer le voyage par ID, afficher les informations, etc.)
        
        $tripModel = new TripsModel();
        $trip = $tripModel->getTripById($id);

        if (!$trip) {
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }

        $this->renderAdminView('admin/trip_detail', [
            'trip' => $trip,
            'currentPage' => 'trips'
        ]);
    }

    public function updateTripStatus($id) {
        $newStatus = $_POST['status'] ?? null;
        if (!in_array($newStatus, ['draft', 'quoted', 'accepted', 'ongoing', 'finished', 'cancelled'])) {
            header('Location: ' . BASE_URL . '/admin/trips/' . $id);
            exit;
        }

        $tripModel = new TripsModel();
        $tripModel->updateStatus($id, $newStatus);

        header('Location: ' . BASE_URL . '/admin/trips');
        exit;
    }

    public function chats() {
        $this->renderAdminView('admin/chats', [
            'currentPage' => 'chats'
        ]);
    }
}