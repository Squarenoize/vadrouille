<?php
/**
 * This class represents a trip created from a contact request
 * It encapsulates the data and provides validation
 * Table trips in DB
 */
class Trip {
    private ?int $id = null;
    private ?int $userId = null;
    private int $requestId; // ID de la demande associée (obligatoire)
    private string $name;
    private string $description;
    private string $destination;
    private string $startDate;
    private string $endDate;
    private ?float $totalPrice = null;
    private ?string $adminNote = null;
    private string $status = 'draft'; 
    private ?string $quoteToken = null; // Token pour accéder au devis
    private ?string $quoteSentAt = null; // Date d'envoi du devis
    private ?string $acceptedAt = null; // Date d'acceptation du devis
    private ?string $createdAt = null;
    private ?string $updatedAt = null;

    /**
     * Hydrate the entity from an array of data
     * Accepts both camelCase (form data) and snake_case (database) keys
     */
    public static function fromArray(array $data): self {
        $instance = new self();
        $instance->id = $data['id'] ?? null;
        $instance->userId = $data['userId'] ?? $data['user_id'] ?? null;
        $instance->requestId = $data['requestId'] ?? $data['request_id'] ?? 0;
        $instance->name = $data['tripName'] ?? $data['name'] ?? '';
        $instance->description = $data['description'] ?? '';
        $instance->destination = $data['destination'] ?? '';
        $instance->startDate = $data['startDate'] ?? $data['start_date'] ?? '';
        $instance->endDate = $data['endDate'] ?? $data['end_date'] ?? '';
        $instance->totalPrice = $data['totalPrice'] ?? $data['total_price'] ?? null;
        $instance->adminNote = $data['adminNote'] ?? $data['admin_note'] ?? null;
        $instance->status = $data['status'] ?? 'draft';
        $instance->quoteToken = $data['quoteToken'] ?? $data['quote_token'] ?? null;
        $instance->quoteSentAt = $data['quoteSentAt'] ?? $data['quote_sent_at'] ?? null;
        $instance->acceptedAt = $data['acceptedAt'] ?? $data['accepted_at'] ?? null;
        $instance->createdAt = $data['createdAt'] ?? $data['created_at'] ?? null;
        $instance->updatedAt = $data['updatedAt'] ?? $data['updated_at'] ?? null;
        return $instance;
    }

    public function validate(): array {
        $errors = [];
        
        if (!$this->requestId) {
            $errors[] = 'La demande associée est requise.';
        }
        if (!$this->name) {
            $errors[] = 'Le titre du voyage est requis.';
        }
        if (!$this->destination) {
            $errors[] = 'La destination est requise.';
        }
        if (!$this->startDate) {
            $errors[] = 'La date de départ est requise.';
        }
        if (!$this->endDate) {
            $errors[] = 'La date de retour est requise.';
        }
        if ($this->startDate && $this->endDate && $this->startDate > $this->endDate) {
            $errors[] = 'La date de départ doit être antérieure à la date de retour.';
        }
        
        return $errors;
    }
    
    /* Convert the entity to an array for database insertion
     * Uses snake_case keys to match database columns
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'requestId' => $this->requestId,
            'name' => $this->name,
            'description' => $this->description,
            'destination' => $this->destination,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'totalPrice' => $this->totalPrice,
            'adminNote' => $this->adminNote,
            'status' => $this->status,
            'quoteToken' => $this->quoteToken,
            'quoteSentAt' => $this->quoteSentAt,
            'acceptedAt' => $this->acceptedAt,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getUserId(): ?int {
        return $this->userId;
    }
    public function getRequestId(): int {
        return $this->requestId;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getDestination(): string {
        return $this->destination;
    }
    public function getStartDate(): string {
        return $this->startDate;
    }
    public function getEndDate(): string {
        return $this->endDate;
    }
    public function getTotalPrice(): ?float {
        return $this->totalPrice;
    }
    public function getAdminNote(): ?string {
        return $this->adminNote;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function getQuoteToken(): ?string {
        return $this->quoteToken;
    }
    public function getQuoteSentAt(): ?string {
        return $this->quoteSentAt;
    }
    public function getAcceptedAt(): ?string {
        return $this->acceptedAt;
    }
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }
    public function getDaysCount(): int {
        $start = new DateTime($this->startDate);
        $end = new DateTime($this->endDate);
        return $start->diff($end)->days + 1; // +1 pour inclure le jour de départ
    }
    public function getDaysBeforeStart(): int {
        $today = new DateTime();
        $start = new DateTime($this->startDate);
        return max(0, $today->diff($start)->days); // Retourne 0 si la date de début est passée
    }

    // Setters
    public function setName (string $name): void {
        $this->name = $name;
    }
    public function setDescription (string $description): void {
        $this->description = $description;
    }
    public function setDestination (string $destination): void {
        $this->destination = $destination;
    }
    public function setStartDate (string $startDate): void {
        $this->startDate = $startDate;
    }
    public function setEndDate (string $endDate): void {
        $this->endDate = $endDate;
    }
    public function setTotalPrice (?float $totalPrice): void {
        $this->totalPrice = $totalPrice;
    }
    public function setUserId(?int $userId): void {
        $this->userId = $userId;
    }
    public function setAdminNote(?string $adminNote): void {
        $this->adminNote = $adminNote;
    }
    public function setStatus(string $status): void {
        $this->status = $status;
    }
    public function setQuoteToken(?string $quoteToken): void {
        $this->quoteToken = $quoteToken;
    }
    public function setQuoteSentAt(?string $quoteSentAt): void {
        $this->quoteSentAt = $quoteSentAt;
    }
    public function setAcceptedAt(?string $acceptedAt): void {
        $this->acceptedAt = $acceptedAt;
    }
    public function setCreatedAt(?string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(?string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }



}