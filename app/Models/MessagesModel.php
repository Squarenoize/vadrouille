<?php

class MessagesModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getMessagesByTripId(int $tripId): array {
        $stmt = $this->db->prepare("SELECT messages.*, users.first_name AS firstname, users.last_name AS lastname FROM messages LEFT JOIN users ON messages.sender_id = users.id WHERE messages.trip_id = :trip_id ORDER BY messages.created_at ASC");
        $stmt->execute(['trip_id' => $tripId]);
        $messagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => Message::fromArray($data), $messagesData);
    }

    public function addMessage(Message $message): bool {
        $stmt = $this->db->prepare("INSERT INTO messages (trip_id, sender_id, body, created_at) VALUES (:trip_id, :sender_id, :body, :created_at)");
        return $stmt->execute([
            'trip_id' => $message->getTripId(),
            'sender_id' => $message->getSenderId(),
            'body' => $message->getMessage(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function countUnreadByAdminId(int $adminId): int {
        $sql = "SELECT COUNT(*) 
                FROM messages m
                WHERE m.sender_id != :admin_id
                AND m.is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'admin_id' => $adminId
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * NOTE USED NOW FOR TRAVELER DASHBOARD
     * Count unread messages for a traveler across all their trips
     * @param int $travelerId The user ID of the traveler
     * @return int Number of unread messages
     */
    public function countUnreadByTravelerId(int $travelerId): int {
        $sql = "SELECT COUNT(*) 
                FROM messages m
                INNER JOIN trips t ON m.trip_id = t.id
                WHERE t.user_id = :user_id 
                AND m.sender_id != :sender_id
                AND m.is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $travelerId,
            'sender_id' => $travelerId
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mark all unread messages in a trip as read for a specific user
     * @param int $tripId The trip ID
     * @param int $userId The user ID marking messages as read
     * @return bool Success status
     */
    public function markAsReadByTrip(int $tripId, int $userId): bool {
        $sql = "UPDATE messages 
                SET is_read = 1 
                WHERE trip_id = :trip_id 
                AND sender_id != :user_id 
                AND is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trip_id' => $tripId,
            'user_id' => $userId
        ]);
    }

    public function countUnreadByTripIdAndUserId(int $tripId, int $userId): int {
        $sql = "SELECT COUNT(*) 
                FROM messages 
                WHERE trip_id = :trip_id 
                AND sender_id != :user_id 
                AND is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'trip_id' => $tripId,
            'user_id' => $userId
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function getAllAdminConversations(int $adminId): array {
        $sql = "SELECT t.id AS trip_id, t.name AS trip_name, u.first_name AS sender_firstname, u.last_name AS sender_lastname, m.body AS last_message, m.created_at AS last_message_time
                FROM trips t
                INNER JOIN messages m ON t.id = m.trip_id
                INNER JOIN users u ON m.sender_id = u.id
                WHERE m.sender_id != :admin_id
                GROUP BY t.id
                ORDER BY last_message_time DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['admin_id' => $adminId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}