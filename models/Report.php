<?php

class Report {
    private int $id;
    private int $mechanic_id;
    private int $customer_id;
    private string $description;
    private string $created_at;

    public function __construct(
        int $id,
        int $mechanic_id,
        int $customer_id,
        string $description,
        string $created_at
    ) {
        $this->id = $id;
        $this->mechanic_id = $mechanic_id;
        $this->customer_id = $customer_id;
        $this->description = $description;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getMechanicId(): int {
        return $this->mechanic_id;
    }

    public function getCustomerId(): int {
        return $this->customer_id;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // Setters
    public function setDescription(string $desc): void {
        $this->description = $desc;
    }
}
