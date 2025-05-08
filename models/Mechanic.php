<?php

require_once __DIR__ . '/User.php';

class Mechanic extends User {
    private float $latitude;
    private float $longitude;
    private bool $availability;
    private array $service_types;
    private float $rating;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phone,
        string $password,
        string $user_type,
        float $latitude,
        float $longitude,
        bool $availability,
        array $service_types,
        float $rating
    ) {
        parent::__construct($id, $name, $email, $phone, $password, $user_type);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->availability = $availability;
        $this->service_types = $service_types;
        $this->rating = $rating;
    }

    // Getters
    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function isAvailable(): bool {
        return $this->availability;
    }

    public function getServiceTypes(): array {
        return $this->service_types;
    }

    public function getRating(): float {
        return $this->rating;
    }

    // Setters
    public function setLatitude(float $latitude): void {
        $this->latitude = $latitude;
    }

    public function setLongitude(float $longitude): void {
        $this->longitude = $longitude;
    }

    public function setAvailability(bool $availability): void {
        $this->availability = $availability;
    }

    public function setServiceTypes(array $service_types): void {
        $this->service_types = $service_types;
    }

    public function setRating(float $rating): void {
        $this->rating = $rating;
    }
}
