<?php

class AdminController {
    
    private $user;
    private $sharedData = [];
    private ContactRequestModel $contactRequestModel;
    private TripsModel $tripModel;
    private MessagesModel $messagesModel;

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

        // Initialisation des Models (réutilisables dans toutes les méthodes)
        $this->contactRequestModel = new ContactRequestModel();
        $this->tripModel = new TripsModel();
        $this->messagesModel = new MessagesModel();

        // Données communes au sidebar (disponibles dans toutes les vues)
        $this->sharedData = [
            'user' => $this->user,
            'newRequestsCount' => $this->contactRequestModel->countByStatus('new'),
            'draftTripsCount' => $this->tripModel->countByStatus('draft'),
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
        $requests = $this->contactRequestModel->getAllRequests();

        $this->renderAdminView('admin/requests', [
            'requests' => $requests,
            'currentPage' => 'requests'
        ]);
    }

    public function viewRequest($id) {
        $request = $this->contactRequestModel->getRequestById($id);

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

        $this->contactRequestModel->updateStatus($id, $newStatus);

        header('Location: ' . BASE_URL . '/admin/requests/' . $id);
        exit;
    }

    public function trips() {
        $trips = $this->tripModel->getAllTrips();

        $this->renderAdminView('admin/trips', [
            'trips' => $trips,
            'currentPage' => 'trips'
        ]);
    }

    public function newTripFromRequest($requestId) {
        // Logique pour créer un voyage à partir d'une demande de contact
        // (Récupérer la demande, pré-remplir un formulaire de création de voyage, etc.)
        
        $request = $this->contactRequestModel->getRequestById($requestId);

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
                $request = $this->contactRequestModel->getRequestById($requestId);
            }
            
            $this->renderAdminView('admin/newTrip', [
                'errors' => $errors,
                'formData' => $_POST,
                'request' => $request, // ✅ Maintenant $request est disponible
                'currentPage' => 'trips'
            ]);
            return;
        }
        // 4. Validation OK : Save via the Model
        $this->tripModel->save($newTrip);

        // Redirection après création (pattern PRG - Post/Redirect/Get)
        header('Location: ' . BASE_URL . '/admin/trips');
        exit;
    }

    public function viewTrip($id) {
        // Logique pour afficher le détail d'un voyage
        // (Récupérer le voyage par ID, afficher les informations, etc.)
        
        $trip = $this->tripModel->getTripById($id);

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

        $this->tripModel->updateStatus($id, $newStatus);

        header('Location: ' . BASE_URL . '/admin/trips');
        exit;
    }

    public function travelerAccess($id) {
        // Logique pour donner accès voyageur à un voyage accepté
        // (Générer un token, envoyer un email, etc.)
        
        $trip = $this->tripModel->getTripById($id);

        if (!$trip || $trip->getStatus() !== 'accepted') {
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }

        $requestId = $trip->getRequestId();
        if (!$requestId) {
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }
        // Check if the email contact already have an account
        $contactRequest = $this->contactRequestModel->getRequestById($requestId);
        $contactEmail = $contactRequest->getEmail();

        if (!$contactEmail) {
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }

        $userModel = new UserModel();
        $existingUser = $userModel->findByEmail($contactEmail);


        if (!$existingUser) {
            // Create a new traveler account
            $password = bin2hex(random_bytes(8)); // Generate a random password with at least 8 characters, including letters and numbers and special characters
            $newUser = new User();
            $newUser->setEmail($contactEmail);
            $newUser->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            $newUser->setFirstName($contactRequest->getFirstName() ?? '');
            $newUser->setLastName($contactRequest->getLastName() ?? '');
            $newUser->setPhone($contactRequest->getPhone() ?? null );
            $newUser->setMustChangePassword(true); // Force password change on first login
            $newUser->setRole('traveler');
            $newUserId = $userModel->create($newUser);

            if ($newUserId === false) {
                $_SESSION['traveler_access_info'] = "Une erreur est survenue lors de la création du compte voyageur pour l'email $contactEmail. Veuillez vérifier manuellement.";
                header('Location: ' . BASE_URL . '/admin/trips');
                exit;
            } else {
                // update trip with the new traveler user id
                $this->tripModel->updateUserId($id, $newUserId);

                // Send email with access details (not implemented here)
                // For now we just pass the generated password to admin(in a real app, you would send this by email and not display it)
                $_SESSION['traveler_access_info'] = "Un compte voyageur a été créé pour l'email $contactEmail avec le mot de passe : $password. Veuillez transmettre ces informations au client.";
            }
        } else {
            // User already exists, link the trip to the existing user
            $this->tripModel->updateUserId($id, $existingUser->getId());
            $_SESSION['traveler_access_info'] = "Le voyage a été associé au compte existant de $contactEmail.";
            // Send email to say that the travel is ready (not implemented here)
        }

        // Send a welcome message to the traveler on his trip chat
        $welcomeMessage = new Message(
            $id,                          // tripId
            $this->user->getId(),         // senderId (Admin)
            "Bienvenue dans ce nouveau voyage ! Votre voyage est prêt."  // message
        );
        
        $this->messagesModel->addMessage($welcomeMessage);


        header('Location: ' . BASE_URL . '/admin/trips');
        exit;
    }

    public function chats() {
        $this->renderAdminView('admin/chats', [
            'currentPage' => 'chats'
        ]);
    }
}