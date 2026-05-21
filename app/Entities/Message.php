<?php

/**
 * This class represents a message in the context of a trip discussion
 * It encapsulates the data and provides validation
 * Table messages in DB
 */

class Message
{
    private ?int $id = null;
    private int $tripId;
    private int $senderId;
    private string $message;
    private bool $isRead = false;
    private ?string $createdAt = null;
    private ?string $senderFirstname = null;
    private ?string $senderLastname = null;

    public function __construct(int $tripId, int $senderId, string $message, ?int $id = null, bool $isRead = false, ?string $createdAt = null, ?string $senderFirstname = null, ?string $senderLastname = null)
    {
        $this->tripId = $tripId;
        $this->senderId = $senderId;
        $this->message = $message;
        $this->id = $id;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt;
        $this->senderFirstname = $senderFirstname;
        $this->senderLastname = $senderLastname;
    }

    // Getters and setters...

    public static function fromArray(array $data): self
    {
        return new self(
            (int)$data['trip_id'],
            (int)$data['sender_id'],
            $data['body'],
            isset($data['id']) ? (int)$data['id'] : null,
            (bool)($data['is_read'] ?? 0),
            $data['created_at'] ?? null,
            $data['firstname'] ?? null,
            $data['lastname'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'trip_id' => $this->tripId,
            'sender_id' => $this->senderId,
            'body' => $this->message,
            'is_read' => $this->isRead ? 1 : 0,
            'created_at' => $this->createdAt
        ];
    }

    public function validate(): array
    {
        $errors = [];

        if (empty($this->message)) {
            $errors[] = 'Le message ne peut pas être vide.';
        } elseif (strlen($this->message) > 2000) {
            $errors[] = 'Le message ne peut pas dépasser 2000 caractères.';
        }
        return $errors;
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTripId(): int
    {
        return $this->tripId;
    }
    public function getSenderId(): int
    {
        return $this->senderId;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function getSenderFirstname(): ?string
    {
        return $this->senderFirstname;
    }

    public function getSenderLastname(): ?string
    {
        return $this->senderLastname;
    }

    public function getSenderFullName(): string
    {
        if ($this->senderFirstname && $this->senderLastname) {
            return $this->senderFirstname . ' ' . $this->senderLastname;
        }
        return $this->senderFirstname ?? $this->senderLastname ?? 'Utilisateur';
    }

     public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;
        return $this;
    }
}
