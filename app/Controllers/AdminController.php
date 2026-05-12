<?php
/**
 * Controller for admin dashboard and management of contact requests, trips, and chats.
 */

class AdminController {
    
    private $user;
    private $sharedData = [];
    private ContactRequestModel $contactRequestModel;
    private TripsModel $tripModel;
    private MessagesModel $messagesModel;

    /**
     * Constructor - Initializing common data and checking admin access
     */
    public function __construct() {
        $this->user = Auth::user();
        
        // Global admin check
        if (!$this->user || !$this->user->isAdmin()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        // Initializing Models (reusable in all methods)
        $this->contactRequestModel = new ContactRequestModel();
        $this->tripModel = new TripsModel();
        $this->messagesModel = new MessagesModel();

        // Common data for the sidebar (available in all views)
        $this->sharedData = [
            'user' => $this->user,
            'newRequestsCount' => $this->contactRequestModel->countByStatus('new'),
            'draftTripsCount' => $this->tripModel->countByStatus('draft'),
            'unreadMessagesCount' => $this->messagesModel->countUnreadByAdminId($this->user->getId()),
        ];
    }

    /**
     * Helper to render an admin view with shared data
     * @param string $template Path to the template (e.g., 'admin/dashboard')
     * @param array $data Specific data for the view
     */
    private function renderAdminView(string $template, array $data = []): void {
        // Merge shared data with specific data
        $viewData = array_merge($this->sharedData, $data);
        
        $view = new View($template, $viewData, 'admin');
        $view->render();
    }
    
    /**
     * Display the admin dashboard --TODO
     */
    public function dashboard() {
        try {
            $this->renderAdminView('admin/dashboard', [
                'currentPage' => 'dashboard'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering admin dashboard: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement du tableau de bord.";
            header('Location: ' . BASE_URL . '/admin/dashboard');
            exit;
        }
    }

    /**
     * Display the list of contact requests with optional status filtering
     */
    public function requests() {
        try {
            $status = $_GET['status'] ?? null;

        if ($status) {
            $requests = $this->contactRequestModel->getRequestsByStatus($status);
        } else {
            $requests = $this->contactRequestModel->getAllRequests();
        }

        // Translation arrays for better display in the view
        $tripTypeTranslations = [
            'adventure' => 'Aventure',
            'weekend' => 'Week-end',
            'relaxation' => 'Détente',
            'cultural' => 'Culturel',
            'other' => 'Autre'
        ];

        $destinationTranslations = [
            'france' => 'France',
            'canada' => 'Canada',
            'japan' => 'Japon',
            'other' => 'Autre'
        ];

        $statusTranslations = [
            'new' => 'Nouvelle',
            'studying' => 'En étude',
            'quoted' => 'Devis envoyé',
            'accepted' => 'Devis accepté',
            'refused' => 'Devis refusé',
            'archived' => 'Archivée'
        ];

        $this->renderAdminView('admin/requests', [
            'requests' => $requests,
            'currentPage' => 'requests',
            'currentStatusFilter' => $status,
            'tripTypeTranslations' => $tripTypeTranslations,
            'destinationTranslations' => $destinationTranslations,
            'statusTranslations' => $statusTranslations
        ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering contact requests: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des demandes de contact.";
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }
    }

    /**
     * Display the details of a specific contact request
     * @param int $id The ID of the contact request
     */
    public function viewRequest($id) {
        try {
            $request = $this->contactRequestModel->getRequestById($id);

            if (!$request) {
                header('Location: ' . BASE_URL . '/admin/requests');
                exit;
            }
            
            // Check if a trip already exist for this request
            $tripId = $this->tripModel->getTripIdByRequestId($id) ?? 0;

            $this->renderAdminView('admin/request_detail', [
                'request' => $request,
                'tripId' => $tripId,
                'currentPage' => 'requests'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering contact request details: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des détails de la demande de contact.";
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }
    }

    /**
     * Update the status of a contact request
     * @param int $id The ID of the contact request
     */
    public function updateRequestStatus($id) {
        try {
            $newStatus = $_POST['status'] ?? null;
            if (!in_array($newStatus, ['new', 'studying', 'quoted', 'accepted', 'refused', 'archived'])) {
                header('Location: ' . BASE_URL . '/admin/requests/' . $id);
                exit;
            }

            $this->contactRequestModel->updateStatus($id, $newStatus);

            header('Location: ' . BASE_URL . '/admin/requests/' . $id);
            exit;
        } catch (Exception $e) {
            // Handle any exceptions that occur during status update
            error_log("Error updating contact request status: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de la mise à jour du statut de la demande de contact.";
            header('Location: ' . BASE_URL . '/admin/requests/' . $id);
            exit;
        }
    }

    /**
     * Display the list of trips with optional status filtering
     */
    public function trips() {
        try {
            $status = $_GET['status'] ?? null;

            if ($status) {
                $trips = $this->tripModel->getTripsByStatus($status);
            } else {
                $trips = $this->tripModel->getAllTrips();
            }

            $this->renderAdminView('admin/trips', [
                'trips' => $trips,
                'currentPage' => 'trips',
                'currentStatusFilter' => $status
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering trips: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des voyages.";
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }
    }

    /**
     * Display the form to create a new trip from a contact request
     * @param int $requestId The ID of the contact request
     */
    public function newTripFromRequest($requestId) {
        // Logic to create a trip from a contact request
        // (Retrieve the request, pre-fill a trip creation form, etc.)
        try {
            $request = $this->contactRequestModel->getRequestById($requestId);

            if (!$request) {
                header('Location: ' . BASE_URL . '/admin/requests');
                exit;
            }

            $this->renderAdminView('admin/newTrip', [
                'request' => $request,
                'currentPage' => 'trips'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering new trip form: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement du formulaire de création de voyage.";
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }
    }

    /**
     * Create a new trip
     */
    public function createTrip() {
        try {
            // 1. Create the entity from POST data
            $newTrip = Trip::fromArray($_POST);
            // 2. Validate the entity (all logic is in the entity)
            $errors = $newTrip->validate();
            // 3. If errors, re-display the form
            if (!empty($errors)) {
                // Grab the request to re-display the form
                $requestId = $_POST['requestId'] ?? null;
                $request = null;
                if ($requestId) {
                    $request = $this->contactRequestModel->getRequestById($requestId);
                }
                
                $this->renderAdminView('admin/newTrip', [
                    'errors' => $errors,
                    'formData' => $_POST,
                    'request' => $request,
                    'currentPage' => 'trips'
                ]);
                return;
            }
            // 4. Validation OK : Save via the Model
            $this->tripModel->save($newTrip);

            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        } catch (Exception $e) {
            // Handle any exceptions that occur during trip creation
            error_log("Error creating trip: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de la création du voyage.";
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }
    }

    /**
     * Display the details of a specific trip
     * @param int $id The ID of the trip
     */
    public function viewTrip($id) {
        try {
            // Logic to display the details of a trip
            // (Retrieve the trip by ID, display the information, etc.)
            
            $trip = $this->tripModel->getTripById($id);

            if (!$trip) {
                header('Location: ' . BASE_URL . '/admin/trips');
                exit;
            }
            // Retrieve messages related to this trip
            $messages = $this->messagesModel->getMessagesByTripId($id);
            $this->messagesModel->markAsReadByTrip($id, $this->user->getId());

            $this->renderAdminView('admin/trip_detail', [
                'trip' => $trip,
                'messages' => $messages,
                'currentPage' => 'trips'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering trip details: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des détails du voyage.";
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }
    }

    public function updateTripStatus($id) {
        try {
            $newStatus = $_POST['status'] ?? null;
            if (!in_array($newStatus, ['draft', 'quoted', 'accepted', 'ongoing', 'finished', 'cancelled'])) {
                header('Location: ' . BASE_URL . '/admin/trips/' . $id);
                exit;
            }

            $this->tripModel->updateStatus($id, $newStatus);

            // Update the related contact request status
            $trip = $this->tripModel->getTripById($id);
            if ($trip && $trip->getRequestId()) {
                $relatedRequestStatus = null;
                switch ($newStatus) {
                    case 'draft':
                        $relatedRequestStatus = 'studying';
                        break;
                    case 'quoted':
                        $relatedRequestStatus = 'quoted';
                        break;
                    case 'accepted':
                        $relatedRequestStatus = 'accepted';
                        break;
                    case 'cancelled':
                        $relatedRequestStatus = 'refused';
                        break;
                }
                if ($relatedRequestStatus) {
                    $this->contactRequestModel->updateStatus($trip->getRequestId(), $relatedRequestStatus);
                }
            }

            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        } catch (Exception $e) {
            // Handle any exceptions that occur during status update
            error_log("Error updating trip status: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de la mise à jour du statut du voyage.";
            header('Location: ' . BASE_URL . '/admin/trips/' . $id);
            exit;
        }
    }

    /**
     * Give traveler access to an accepted trip
     * @param int $id The ID of the trip
     */
    public function travelerAccess($id) {
        // Logic to give traveler access to an accepted trip
        // (Generate a token, send an email, etc.)
        try {
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
        } catch (Exception $e) {
            // Handle any exceptions that occur during traveler access granting
            error_log("Error granting traveler access: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de l'octroi de l'accès au voyageur.";
            header('Location: ' . BASE_URL . '/admin/trips');
            exit;
        }
    }

    /**
     * Display the list of chats (conversations) for the admin
     */
    public function chats() {
        try {
            $chats = $this->messagesModel->getAllAdminConversations($this->user->getId());

            $this->renderAdminView('admin/chats', [
                'currentPage' => 'chats',
                'chats' => $chats
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering admin chats: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des conversations.";
            header('Location: ' . BASE_URL . '/admin/chats');
            exit;
        }
    }

    public function tripItem($id) {
        // Logic to add an item to a trip itinerary
        // (Validate input, save the item, etc.)
        $this->renderAdminView('admin/tripItem', [
            'currentPage' => 'tripItem',
            'tripId' => $id
        ]);
    }
}