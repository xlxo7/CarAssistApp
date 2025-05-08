<?php

class Order {
    private int $id;
    private int $customer_id;
    private int $mechanic_id;
    private string $service_type;
    private string $status;
    private float $latitude;
    private float $longitude;
    private string $created_at;

    public function __construct(
        int $id,
        int $customer_id,
        int $mechanic_id,
        string $service_type,
        string $status,
        float $latitude,
        float $longitude,
        string $created_at
    ) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->mechanic_id = $mechanic_id;
        $this->service_type = $service_type;
        $this->status = $status;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getCustomerId(): int {
        return $this->customer_id;
    }

    public function getMechanicId(): int {
        return $this->mechanic_id;
    }

    public function getServiceType(): string {
        return $this->service_type;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // Setters
    public function setServiceType(string $type): void {
        $this->service_type = $type;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setLatitude(float $lat): void {
        $this->latitude = $lat;
    }

    public function setLongitude(float $lng): void {
        $this->longitude = $lng;
    }
}
