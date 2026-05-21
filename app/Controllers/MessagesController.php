<?php

/**
 * Controller for handling messages between users and trips
 */
class MessagesController
{

    private $user;
    private MessagesModel $messagesModel;
    private TripsModel $tripsModel;

    /**
     * Constructor - Check authentication and initialize models
     */
    public function __construct()
    {
        $this->user = Auth::user();

        // Global authentication check
        if (!$this->user) {
            $_SESSION['flash_error'] = "Vous devez être connecté pour envoyer un message.";
            header('Location: /login');
            exit;
        }

        // Initialize models
        $this->messagesModel = new MessagesModel();
        $this->tripsModel = new TripsModel();
    }

    /**
     * Send a new message (form POST handler)
     */
    public function send()
    {
        // Validate POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = "Méthode non autorisée.";
            header('Location: /');
            exit;
        }

        $tripId = $_POST['trip_id'] ?? null;
        $messageBody = $_POST['message'] ?? '';
        $redirectUrl = $_POST['redirect_url'] ?? '/';

        // Validate input
        if (!$tripId || empty(trim($messageBody))) {
            $_SESSION['flash_error'] = "Le voyage et le message sont requis.";
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Check if trip exists and user has access
        $trip = $this->tripsModel->getTripById($tripId);
        if (!$trip || !$this->canAccessTrip($trip)) {
            $_SESSION['flash_error'] = "Accès refusé à ce voyage.";
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Create message entity
        $message = new Message(
            (int)$tripId,
            $this->user->getId(),
            trim($messageBody)
        );

        // Validate message
        $errors = $message->validate();
        if (!empty($errors)) {
            $_SESSION['flash_error'] = $errors[0];
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Save message
        $success = $this->messagesModel->addMessage($message);

        if ($success) {
            $_SESSION['flash_success'] = "Message envoyé avec succès.";
        } else {
            $_SESSION['flash_error'] = "Erreur lors de l'envoi du message.";
        }

        // Redirect back to the chat page
        header('Location: ' . $redirectUrl);
        exit;
    }

    /**
     * Check if current user can access a trip
     * @param Trip $trip
     * @return bool
     */
    private function canAccessTrip($trip): bool
    {
        // Admin can access all trips
        if ($this->user->isAdmin()) {
            return true;
        }

        // Travelers can only access their own trips
        return $trip->getUserId() === $this->user->getId();
    }
}
