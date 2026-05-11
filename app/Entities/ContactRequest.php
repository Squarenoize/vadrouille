<?php
/**
 * This class encapsulates the data and provides validation
 * Table contact_requests in DB
 */
class ContactRequest {
    
    private ?int $id = null;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;
    private string $tripType;                    // trip_type (ENUM)
    private string $destination;                 // destination (ENUM)
    private int $travelersAdultCount;            // travelers_adult_count
    private int $travelersChildCount;            // travelers_child_count
    private ?string $desiredStart;               // desired_start (DATE)
    private ?int $duration;                      // duration (INT)
    private ?float $budget;                      // budget (DECIMAL)
    private ?string $startCountry;               // start_country (pays de départ)
    private string $message;
    private bool $conditionsAccepted;            // conditions_accepted
    private string $status = 'new';              // status (ENUM)
    private ?int $convertedToTrip = null;        // converted_to_trip
    private ?string $createdAt = null;
    
    /**
     * Hydrate the entity from an array of data
     */
    public static function fromArray(array $data): self {
        $instance = new self();
        
        $instance->firstName = $data['first_name'] ?? '';
        $instance->lastName = $data['last_name'] ?? '';
        $instance->email = $data['email'] ?? '';
        $instance->phone = $data['phone'] ?? '';
        $instance->tripType = $data['trip_type'] ?? '';
        $instance->destination = $data['destination'] ?? '';
        $instance->travelersAdultCount = (int)($data['travelers_adult_count'] ?? 1);
        $instance->travelersChildCount = (int)($data['travelers_child_count'] ?? 0);
        $instance->desiredStart = $data['desired_start'] ?? null;
        $instance->duration = isset($data['duration']) ? (int)$data['duration'] : null;
        $instance->budget = isset($data['budget']) ? (float)$data['budget'] : null;
        $instance->startCountry = $data['start_country'] ?? null;
        $instance->message = $data['message'] ?? '';
        $instance->conditionsAccepted = $data['conditions_accepted'] ?? false;
        
        if (isset($data['id'])) {
            $instance->id = (int)$data['id'];
        }
        if (isset($data['status'])) {
            $instance->status = $data['status'];
        }
        if (isset($data['converted_to_trip'])) {
            $instance->convertedToTrip = (int)$data['converted_to_trip'];
        }
        if (isset($data['created_at'])) {
            $instance->createdAt = $data['created_at'];
        }
        
        return $instance;
    }
    
    /**
     * Validate the entity and return an array of errors
     * @return array Associative array [field => error message]
     */
    public function validate(): array {
        $errors = [];
        
        // First name
        if (!DataVerification::isNotEmpty($this->firstName)) {
            $errors['first_name'] = "Le prénom est requis";
        } elseif (!DataVerification::hasValidLength($this->firstName, 2, 80)) {
            $errors['first_name'] = "Le prénom doit contenir entre 2 et 80 caractères";
        }
        
        // Last name
        if (!DataVerification::isNotEmpty($this->lastName)) {
            $errors['last_name'] = "Le nom est requis";
        } elseif (!DataVerification::hasValidLength($this->lastName, 2, 80)) {
            $errors['last_name'] = "Le nom doit contenir entre 2 et 80 caractères";
        }
        
        // Email
        if (!DataVerification::isNotEmpty($this->email)) {
            $errors['email'] = "L'email est requis";
        } elseif (!DataVerification::isValidEmail($this->email)) {
            $errors['email'] = "L'email n'est pas valide";
        }
        
        // Phone (optional in DB)
        if (DataVerification::isNotEmpty($this->phone) && !DataVerification::isValidPhone($this->phone)) {
            $errors['phone'] = "Le numéro de téléphone n'est pas valide";
        }
        
        // Trip type (ENUM)
        $allowedTypes = ['adventure', 'weekend', 'relaxation', 'cultural', 'other'];
        if (!DataVerification::isNotEmpty($this->tripType)) {
            $errors['trip_type'] = "Le type de voyage est requis";
        } elseif (!DataVerification::isInList($this->tripType, $allowedTypes)) {
            $errors['trip_type'] = "Type de voyage invalide";
        }
        
        // Destination (ENUM)
        $allowedDestinations = ['france', 'canada', 'japan', 'other'];
        if (!DataVerification::isNotEmpty($this->destination)) {
            $errors['destination'] = "La destination est requise";
        } elseif (!DataVerification::isInList($this->destination, $allowedDestinations)) {
            $errors['destination'] = "Destination invalide";
        }
        
        // Number of adults
        if (!DataVerification::isValidTravelerCount($this->travelersAdultCount)) {
            $errors['travelers_adult_count'] = "Le nombre d'adultes n'est pas valide (0-20)";
        }
        
        // Number of children
        if (!DataVerification::isValidTravelerCount($this->travelersChildCount)) {
            $errors['travelers_child_count'] = "Le nombre d'enfants n'est pas valide (0-20)";
        }
        
        // Desired start date (optional)
        if ($this->desiredStart !== null && !DataVerification::isFutureDate($this->desiredStart)) {
            $errors['desired_start'] = "La date de départ doit être dans le futur";
        }
        
        // Trip duration (optional)
        if ($this->duration !== null && !DataVerification::isValidDuration($this->duration)) {
            $errors['duration'] = "La durée doit être entre 1 et 365 jours";
        }
        
        // Budget (optional, DECIMAL)
        if ($this->budget !== null && $this->budget <= 0) {
            $errors['budget'] = "Le budget doit être positif";
        }
        
        // Conditions accepted
        if (!$this->conditionsAccepted) {
            $errors['conditions_accepted'] = "Vous devez accepter les conditions";
        }
        
        return $errors;
    }
    
    /**
     * Convert the entity to an array for database insertion
     */
    public function toArray(): array {
        return [
            'first_name' => DataVerification::sanitize($this->firstName),
            'last_name' => DataVerification::sanitize($this->lastName),
            'email' => trim($this->email),
            'phone' => $this->phone ? trim($this->phone) : null,
            'trip_type' => $this->tripType,
            'destination' => $this->destination,
            'travelers_adult_count' => $this->travelersAdultCount,
            'travelers_child_count' => $this->travelersChildCount,
            'desired_start' => $this->desiredStart,
            'duration' => $this->duration,
            'budget' => $this->budget,
            'start_country' => $this->startCountry ? DataVerification::sanitize($this->startCountry) : null,
            'message' => DataVerification::sanitize($this->message),
            'conditions_accepted' => $this->conditionsAccepted ? 1 : 0,
            'status' => $this->status
        ];
    }
    
    // ========== GETTERS ==========
    
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getFirstName(): string {
        return $this->firstName;
    }
    
    public function getLastName(): string {
        return $this->lastName;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function getPhone(): string {
        return $this->phone;
    }
    
    public function getTripType(): string {
        return $this->tripType;
    }
    
    public function getDestination(): string {
        return $this->destination;
    }
    
    public function getTravelersAdultCount(): int {
        return $this->travelersAdultCount;
    }
    
    public function getTravelersChildCount(): int {
        return $this->travelersChildCount;
    }
    
    public function getDesiredStart(): ?string {
        return $this->desiredStart;
    }
    
    public function getDuration(): ?int {
        return $this->duration;
    }
    
    public function getBudget(): ?float {
        return $this->budget;
    }
    
    public function getStartCountry(): ?string {
        return $this->startCountry;
    }
    
    public function getMessage(): string {
        return $this->message;
    }
    
    public function hasConditionsAccepted(): bool {
        return $this->conditionsAccepted;
    }
    
    public function getStatus(): string {
        return $this->status;
    }
    
    public function getConvertedToTrip(): ?int {
        return $this->convertedToTrip;
    }
    
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
}
