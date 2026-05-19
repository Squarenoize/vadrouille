<?php
class TripItemModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Save a trip item to the database
     * @param TripItem $item
     * @return int The ID of the newly created item
     */
    public function saveTripItem(TripItem $item): int
    {
        $data = $item->toArray();

        // Auto-calculate sort_order based on chronological position if not set
        if (empty($data['sortOrder'])) {
            $data['sortOrder'] = $this->getNextSortOrderByDate($data['tripId'], $data['startDatetime']);
        }

        $sql = "INSERT INTO trip_items (trip_id, title, category, start_datetime, end_datetime, description, requires_booking, external_link, indicative_price, sort_order, created_at) 
                VALUES (:tripId, :title, :category, :startDatetime, :endDatetime, :description, :requiresBooking, :externalLink, :indicativePrice, :sortOrder, :createdAt)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tripId' => $data['tripId'],
            'title' => $data['title'],
            'category' => $data['category'],
            'startDatetime' => $data['startDatetime'],
            'endDatetime' => $data['endDatetime'],
            'description' => $data['description'],
            'requiresBooking' => $data['requiresBooking'],
            'externalLink' => $data['externalLink'],
            'indicativePrice' => $data['indicativePrice'],
            'sortOrder' => $data['sortOrder'],
            'createdAt' => $data['createdAt']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function getItemsByTripId(int $tripId): array
    {
        $sql = "SELECT * FROM trip_items 
                WHERE trip_id = :tripId 
                ORDER BY start_datetime ASC, sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tripId' => $tripId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => TripItem::fromArray($row), $results);
    }

    /**
     * Get a single trip item by ID
     * @param int $itemId
     * @return TripItem|null
     */
    public function getItemById(int $itemId): ?TripItem
    {
        $sql = "SELECT * FROM trip_items WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $itemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? TripItem::fromArray($result) : null;
    }

    /**
     * Update an existing trip item
     * @param TripItem $item
     * @return bool
     */
    public function updateTripItem(TripItem $item): bool
    {
        $data = $item->toArray();

        $sql = "UPDATE trip_items 
                SET title = :title,
                    category = :category,
                    start_datetime = :startDatetime,
                    end_datetime = :endDatetime,
                    description = :description,
                    requires_booking = :requiresBooking,
                    external_link = :externalLink,
                    indicative_price = :indicativePrice,
                    sort_order = :sortOrder,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $data['id'],
            'title' => $data['title'],
            'category' => $data['category'],
            'startDatetime' => $data['startDatetime'],
            'endDatetime' => $data['endDatetime'],
            'description' => $data['description'],
            'requiresBooking' => $data['requiresBooking'],
            'externalLink' => $data['externalLink'],
            'indicativePrice' => $data['indicativePrice'],
            'sortOrder' => $data['sortOrder']
        ]);
    }

    /**
     * Delete a trip item
     * @param int $itemId
     * @return bool
     */
    public function deleteItem(int $itemId): bool
    {
        $sql = "DELETE FROM trip_items WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $itemId]);
    }

    /**
     * Get the next available sort_order for a trip
     * Uses increments of 10 to allow easy insertion between items
     * @param int $tripId
     * @return int
     */
    public function getNextSortOrder(int $tripId): int
    {
        $sql = "SELECT MAX(sort_order) as max_order FROM trip_items WHERE trip_id = :tripId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tripId' => $tripId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $maxOrder = $result['max_order'] ?? 0;
        return $maxOrder + 10;
    }

    /**
     * Calculate sort_order based on chronological position
     * Items with earlier dates get lower sort_order values
     * @param int $tripId
     * @param string $startDatetime Format: 'Y-m-d H:i:s'
     * @return int
     */
    public function getNextSortOrderByDate(int $tripId, string $startDatetime): int
    {
        // Count how many items start before or at the same time
        $sql = "SELECT COUNT(*) as count 
                FROM trip_items 
                WHERE trip_id = :tripId 
                AND start_datetime <= :startDatetime";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tripId' => $tripId,
            'startDatetime' => $startDatetime
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $position = ($result['count'] ?? 0) + 1;
        return $position * 10;
    }

    /**
     * Update the sort order of a specific item
     * @param int $itemId
     * @param int $newSortOrder
     * @return bool
     */
    public function updateSortOrder(int $itemId, int $newSortOrder): bool
    {
        $sql = "UPDATE trip_items SET sort_order = :sortOrder WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'sortOrder' => $newSortOrder,
            'id' => $itemId
        ]);
    }

    /**
     * Reorder all items of a trip sequentially based on start_datetime (10, 20, 30...)
     * Useful after deletions or date changes to maintain clean sort order
     * @param int $tripId
     * @return bool
     */
    public function reorderItems(int $tripId): bool
    {
        $sql = "SELECT id FROM trip_items 
                WHERE trip_id = :tripId 
                ORDER BY start_datetime ASC, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tripId' => $tripId]);
        $items = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sortOrder = 10;
        foreach ($items as $itemId) {
            $this->updateSortOrder($itemId, $sortOrder);
            $sortOrder += 10;
        }

        return true;
    }
}
