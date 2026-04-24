<?php

class TripsModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllTrips(): array {
        $sql = "SELECT * FROM trips ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert each row into an entity
        return array_map(fn($row) => Trip::fromArray($row), $results);
    }

    public function getTripById(int $id): ?Trip {
        $sql = "SELECT * FROM trips WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Trip::fromArray($row) : null;
    }

    public function getTripsByStatus(string $status): array {
        $sql = "SELECT * FROM trips WHERE status = :status ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(fn($row) => Trip::fromArray($row), $results);
    }


    /**
     * Create a new trip based on a request
     */
    public function save(Trip $newTrip) :bool {
        $sql = "INSERT INTO trips (
                    request_id, name, description, destination, start_date, end_date, admin_note
                ) VALUES (
                    :requestId, :name, :description, :destination, :startDate, :endDate, :adminNote
                )";
        
        $stmt = $this->db->prepare($sql);
        
        // Get the cleaned data from the entity
        $data = $newTrip->toArray();
        
        return $stmt->execute([
            'requestId' => $data['requestId'],
            'name' => $data['name'],
            'description' => $data['description'],
            'destination' => $data['destination'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'adminNote' => $data['adminNote']
        ]);
    }

    public function countByStatus(string $status): int {
        $sql = "SELECT COUNT(*) FROM trips WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    public function updateStatus(int $id, string $newStatus): bool {
        $sql = "UPDATE trips SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $newStatus, 'id' => $id]);
    }

    public function updateUserId(int $tripId, int $userId): bool {
        $sql = "UPDATE trips SET user_id = :userId, updated_at = NOW() WHERE id = :tripId";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['userId' => $userId, 'tripId' => $tripId]);
    }

    public function getTripsByTravelerId(int $userId): array {
        $sql = "SELECT * FROM trips WHERE user_id = :userId ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(fn($row) => Trip::fromArray($row), $results);
    }

    public function countByTravelerId(int $userId): int {
        $sql = "SELECT COUNT(*) FROM trips WHERE user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return (int)$stmt->fetchColumn();
    }
        
}