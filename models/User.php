<?php

class User {
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected string $password;
    protected string $user_type;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phone,
        string $password,
        string $user_type
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->user_type = $user_type;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUserType(): string {
        return $this->user_type;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setUserType(string $user_type): void {
        $this->user_type = $user_type;
    }
}
