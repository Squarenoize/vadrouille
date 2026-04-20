<?php

class ContactRequestModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Save a contact request
     * @param ContactRequest $contactRequest The entity to persist
     * @return bool
     */
    public function save(ContactRequest $contactRequest): bool {
        $sql = "INSERT INTO contact_requests (
                    first_name, last_name, email, phone, 
                    trip_type, destination, travelers_adult_count, travelers_child_count,
                    desired_start, duration, budget, start_country, message, 
                    conditions_accepted, status, created_at
                ) VALUES (
                    :first_name, :last_name, :email, :phone,
                    :trip_type, :destination, :travelers_adult_count, :travelers_child_count,
                    :desired_start, :duration, :budget, :start_country, :message,
                    :conditions_accepted, :status, NOW()
                )";
        
        $stmt = $this->db->prepare($sql);
        
        // Get the cleaned data from the entity
        $data = $contactRequest->toArray();
        
        return $stmt->execute($data);
    }
    
    /**
     * Retrieve all contact requests
     * @return ContactRequest[]
     */
    public function getAllRequests(): array {
        $sql = "SELECT * FROM contact_requests ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert each row into an entity
        return array_map(fn($row) => ContactRequest::fromArray($row), $results);
    }
    
    /**
     * Retrieve a contact request by ID
     * @param int $id
     * @return ContactRequest|null
     */
    public function getRequestById(int $id): ?ContactRequest {
        $sql = "SELECT * FROM contact_requests WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? ContactRequest::fromArray($row) : null;
    }

    public function updateStatus(int $id, string $newStatus): bool {
        $sql = "UPDATE contact_requests SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $newStatus, 'id' => $id]);
    }

    public function countByStatus(string $status): int {
        $sql = "SELECT COUNT(*) FROM contact_requests WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int)$stmt->fetchColumn();
    }
}