<?php

class TravelerController {

    private $user;
    private $sharedData = [];
    private TripsModel $tripsModel;
    private MessagesModel $messagesModel;

    /**
     * Constructeur - Initialise les données communes à toutes les pages traveler
     */
    public function __construct() {
        $this->user = Auth::user();
        
        // Vérification voyageur globale
        if (!$this->user || !$this->user->isTraveler()) {
            header('Location: ' . BASE_URL . '/connexion');
            exit;
        }

        // Initialisation des Models pour récupérer les données communes
        $this->tripsModel = new TripsModel();
        $this->messagesModel = new MessagesModel();

        // Données communes au sidebar (disponibles dans toutes les vues)
        $this->sharedData = [
            'user' => $this->user
        ];
    }
    
    /* Helper pour rendre une vue traveler avec les données partagées
     * @param string $template Chemin du template (ex: 'traveler/dashboard')
     * @param array $data Données spécifiques à la vue
     */
    private function renderTravelerView(string $template, array $data = []): void {
        // Merge des données partagées + données spécifiques
        $viewData = array_merge($this->sharedData, $data);
        
        $view = new View($template, $viewData, 'traveler');
        $view->render();
    }

    public function dashboard() {
        $this->renderTravelerView('traveler/dashboard', [
            'currentPage' => 'dashboard'
        ]);
    }

    public function trips() {
        $user = $this->user;
        $trips = $this->tripsModel->getTripsByTravelerId($user->getId());

        // Récupérer le nombre de messages non lus pour chaque voyage
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

    public function settings() {
        $this->renderTravelerView('traveler/settings', [
            'currentPage' => 'settings'
        ]);
    }
}
