<?php
/**
 * Controller for traveler-related pages and actions
 */
class TravelerController {

    private $user;
    private $sharedData = [];
    private TripsModel $tripsModel;
    private MessagesModel $messagesModel;

    /**
     * Constructor - Initialize common data for all traveler pages
     */
    public function __construct() {
        $this->user = Auth::user();
        
        // Global traveler check
        if (!$this->user) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        // Initialize models to fetch common data
        $this->tripsModel = new TripsModel();
        $this->messagesModel = new MessagesModel();

        // Common data for the sidebar (available in all views)
        $this->sharedData = [
            'user' => $this->user
        ];
    }
    
    /**
     * Helper to render a traveler view with shared data
     * @param string $template Path to the template (e.g., 'traveler/dashboard')
     * @param array $data Specific data for the view
     */
    private function renderTravelerView(string $template, array $data = []): void {
        // Merge shared data + specific data
        $viewData = array_merge($this->sharedData, $data);
        
        $view = new View($template, $viewData, 'traveler');
        $view->render();
    }

    /**
     * View the dashboard for the current traveler --TODO: add stats, etc.
     */
    public function dashboard() {
        $this->renderTravelerView('traveler/dashboard', [
            'currentPage' => 'dashboard'
        ]);
    }

    /**
     * View the list of trips for the current traveler
     */
    public function trips() {
        try {
            $user = $this->user;
            $trips = $this->tripsModel->getTripsByTravelerId($user->getId());

            // Get the number of unread messages for each trip
            $unreadCounts = [];
            foreach ($trips as $trip) {
                $unreadCounts[$trip->getId()] = $this->messagesModel->countUnreadByTripIdAndUserId($trip->getId(), $user->getId());
            }
            
            $this->renderTravelerView('traveler/trips', [
                'trips' => $trips,
                'unreadCounts' => $unreadCounts,
                'currentPage' => 'trips'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering traveler trips: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement de vos voyages.";
            header('Location: ' . BASE_URL . '/traveler/dashboard');
            exit;
        }
    }

    /**
     * View details of a specific trip
     * @param int $tripId The ID of the trip
     */
    public function viewTrip($tripId) {
        try {
            $trip = $this->tripsModel->getTripById($tripId);
            $messages = $this->messagesModel->getMessagesByTripId($tripId);
            $this->messagesModel->markAsReadByTrip($tripId, $this->user->getId());
            $this->renderTravelerView('traveler/trip_detail', [
                'trip' => $trip,
                'messages' => $messages,
                'currentPage' => 'trips'
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering trip details: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des détails du voyage.";
            header('Location: ' . BASE_URL . '/traveler/trips');
            exit;
        }
    }

    /**
     * View and update traveler settings -- TODO: implement actual settings functionality
     */
    public function settings() {
        try {
            //Get user info
            $userId = $this->user->getId();
            $userModel = new UserModel();
            $userSettings = $userModel->getCommonUserSettings($userId);
            $this->renderTravelerView('traveler/settings', [
                'currentPage' => 'settings',
                'userSettings' => $userSettings
            ]);
        } catch (Exception $e) {
            // Handle any exceptions that occur during rendering
            error_log("Error rendering traveler settings: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors du chargement des paramètres.";
            header('Location: ' . BASE_URL . '/traveler/dashboard');
            exit;
        }
    }

    public function updateSettings() {
        try {
            $userModel = new UserModel();
            $userId = $this->user->getId();

            $email = $_POST['email'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $emailNotif = isset($_POST['email_notif']) ? 1 : 0;

            // Basic validation (can be expanded)
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email invalide.");
            }
            if (empty($firstName) || empty($lastName)) {
                throw new Exception("Le prénom et le nom sont requis.");
            }

            // Update user settings
            $success = $userModel->updateUserSettings($userId, $email, $firstName, $lastName, $phone, $emailNotif);
            
            if ($success) {
                $_SESSION['successMessage'] = "Paramètres mis à jour avec succès.";
            } else {
                throw new Exception("Échec de la mise à jour des paramètres.");
            }
        } catch (Exception $e) {
            error_log("Error updating traveler settings: " . $e->getMessage());
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de la mise à jour des paramètres : " . htmlspecialchars($e->getMessage());
        }

        if ($this->user->isAdmin()) {
            header('Location: ' . BASE_URL . '/admin/trips');
        } else {
            header('Location: ' . BASE_URL . '/traveler/trips');
        }
        exit;
    }
}
