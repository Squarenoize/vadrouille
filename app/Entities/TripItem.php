<?php
/**
 * This class represents an item in a trip itinerary
 * It encapsulates the data (and provides validation)
 * Table trip_items in DB
 */
class TripItem {
    private int $id;
    private int $tripId;
    private string $title;
    private string $category;
    private DateTime $startDatetime;
    private DateTime $endDatetime;
    private ?string $description;
    private bool $requiresBooking;
    private ?string $externalLink;
    private ?float $indicativePrice;
    private int $sortOrder;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;

    /* Hydrate the entity from an array of data
     * Accepts both camelCase (form data) and snake_case (database) keys
     */
    public static function fromArray(array $data): self {
        $instance = new self();
        $instance->id = $data['id'] ?? 0;
        $instance->tripId = $data['tripId'] ?? $data['trip_id'] ?? 0;
        $instance->title = $data['title'] ?? '';
        $instance->category = $data['category'] ?? 'LIBRE';
        $instance->startDatetime = new DateTime($data['startDatetime'] ?? $data['start_datetime'] ?? 'now');
        $instance->endDatetime = new DateTime($data['endDatetime'] ?? $data['end_datetime'] ?? 'now');
        $instance->description = $data['description'] ?? null;
        $instance->requiresBooking = isset($data['requiresBooking']) ? (bool)$data['requiresBooking'] : true;
        $instance->externalLink = $data['externalLink'] ?? null;
        $instance->indicativePrice = isset($data['indicativePrice']) ? (float)$data['indicativePrice'] : null;
        $instance->sortOrder = $data['sortOrder'] ?? 0;
        $instance->createdAt = new DateTime($data['createdAt'] ?? $data['created_at'] ?? 'now');
        if (isset($data['updatedAt']) || isset($data['updated_at'])) {
            $instance->updatedAt = new DateTime($data['updatedAt'] ?? $data['updated_at']);
        } else {
            $instance->updatedAt = null;
        }
        return $instance;
    }

    /* Convert the entity to an array for database insertion
    * Uses snake_case keys to match database columns
    */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'tripId' => $this->tripId,
            'title' => $this->title,
            'category' => $this->category,
            'startDatetime' => $this->startDatetime->format('Y-m-d H:i:s'),
            'endDatetime' => $this->endDatetime->format('Y-m-d H:i:s'),
            'description' => $this->description,
            'requiresBooking' => $this->requiresBooking ? 1 : 0,
            'externalLink' => $this->externalLink,
            'indicativePrice' => $this->indicativePrice,
            'sortOrder' => $this->sortOrder,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null
        ];
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getTripId(): int {
        return $this->tripId;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function getCategory(): string {
        return $this->category;
    }
    public function getStartDatetime(): DateTime {
        return $this->startDatetime;
    }
    public function getEndDatetime(): DateTime {
        return $this->endDatetime;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function getRequiresBooking(): bool {
        return $this->requiresBooking;
    }
    public function getExternalLink(): ?string {
        return $this->externalLink;
    }
    public function getIndicativePrice(): ?float {
        return $this->indicativePrice;
    }
    public function getSortOrder(): int {
        return $this->sortOrder;
    }
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?DateTime {
        return $this->updatedAt;
    }

    // Setters
    public function setTripId(int $tripId): void {
        $this->tripId = $tripId;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function setCategory(string $category): void {
        $this->category = $category;
    }
    public function setStartDatetime(DateTime $startDatetime): void {
        $this->startDatetime = $startDatetime;
    }
    public function setEndDatetime(DateTime $endDatetime): void {
        $this->endDatetime = $endDatetime;
    }
    public function setDescription(?string $description): void {
        $this->description = $description;
    }
    public function setRequiresBooking(bool $requiresBooking): void {
        $this->requiresBooking = $requiresBooking;
    }
    public function setExternalLink(?string $externalLink): void {
        $this->externalLink = $externalLink;
    }
    public function setIndicativePrice(?float $indicativePrice): void {
        $this->indicativePrice = $indicativePrice;
    }
    public function setSortOrder(int $sortOrder): void {
        $this->sortOrder = $sortOrder;
    }
    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(?DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

}
