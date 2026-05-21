<?php

/**
 * TripsModel handles database operations for trips
 * It provides methods to save, retrieve, and update trips
 * Table trips in DB
 */
class TripsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retrieve all trips
     * @return Trip[]
     */
    public function getAllTrips(): array
    {
        $sql = "SELECT * FROM trips ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert each row into an entity
        return array_map(fn($row) => Trip::fromArray($row), $results);
    }

    /**
     * Retrieve a trip by its ID
     * @param int $id
     * @return Trip|null
     */
    public function getTripById(int $id): ?Trip
    {
        $sql = "SELECT * FROM trips WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new RuntimeException("Voyage $id introuvable");
        }

        return Trip::fromArray($row);
    }

    /**
     * Retrieve trips by their status
     * @param string $status
     * @return Trip[]
     */
    public function getTripsByStatus(string $status): array
    {
        $sql = "SELECT * FROM trips WHERE status = :status ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => Trip::fromArray($row), $results);
    }


    /**
     * Create a new trip based on a request
     * @param Trip $newTrip
     * @return bool
     */
    public function save(Trip $newTrip): bool
    {
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

    /**
     * Count trips by status
     * @param string $status
     * @return int
     */
    public function countByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) FROM trips WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Update the status of a trip
     * @param int $id
     * @param string $newStatus
     * @return bool
     */
    public function updateStatus(int $id, string $newStatus): bool
    {
        $sql = "UPDATE trips SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $newStatus, 'id' => $id]);
    }

    /**
     * Update the user_id of a trip (assigning it to a traveler)
     * @param int $tripId
     * @param int $userId
     * @return bool
     */
    public function updateUserId(int $tripId, int $userId): bool
    {
        $sql = "UPDATE trips SET user_id = :userId, updated_at = NOW() WHERE id = :tripId";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['userId' => $userId, 'tripId' => $tripId]);
    }

    /**
     * Retrieve trips by traveler ID
     * @param int $userId
     * @return Trip[]
     */
    public function getTripsByTravelerId(int $userId): array
    {
        $sql = "SELECT * FROM trips WHERE user_id = :userId ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            throw new RuntimeException("Aucun voyage trouvé pour l'utilisateur $userId");
        }

        return array_map(fn($row) => Trip::fromArray($row), $results);
    }

    /**
     * Count trips by traveler ID
     * @param int $userId
     * @return int
     */
    public function countByTravelerId(int $userId): int
    {
        $sql = "SELECT COUNT(*) FROM trips WHERE user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Retrieve trip ID by request ID
     * @param int $requestId
     * @return int
     */
    public function getTripIdByRequestId(int $requestId): int
    {
        $sql = "SELECT id FROM trips WHERE request_id = :requestId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['requestId' => $requestId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 0;
        }

        return (int)$result['id'];
    }
}
