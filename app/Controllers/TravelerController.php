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
        if (!$this->user || !$this->user->isTraveler()) {
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
     * View the dashboard for the current traveler
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
    }

    /**
     * View details of a specific trip
     * @param int $tripId The ID of the trip
     */
    public function viewTrip($tripId) {
        $trip = $this->tripsModel->getTripById($tripId);
        $messages = $this->messagesModel->getMessagesByTripId($tripId);
        $this->messagesModel->markAsReadByTrip($tripId, $this->user->getId());
        $this->renderTravelerView('traveler/trip_detail', [
            'trip' => $trip,
            'messages' => $messages,
            'currentPage' => 'trips'
        ]);
    }

    /**
     * View and update traveler settings
     */
    public function settings() {
        $this->renderTravelerView('traveler/settings', [
            'currentPage' => 'settings'
        ]);
    }
}
