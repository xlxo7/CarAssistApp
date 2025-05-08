<?php

require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Mechanic.php';
require_once __DIR__ . '/../models/Admin.php'; // ✅ أضف هذا السطر

class UserFactory
{
    public static function createUser(array $data): User
    {
        $common = [
            'id' => $data['id'] ?? 0,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'user_type' => $data['user_type'],
        ];

        return match ($data['user_type']) {
            'mechanic' => new Mechanic(
                $common['id'],
                $common['name'],
                $common['email'],
                $common['phone'],
                $common['password'],
                $common['user_type'],
                $data['latitude'] ?? 0.0,
                $data['longitude'] ?? 0.0,
                $data['availability'] ?? false,
                $data['service_types'] ?? [],
                $data['rating'] ?? 0.0
            ),
            'customer' => new Customer(
                $common['id'],
                $common['name'],
                $common['email'],
                $common['phone'],
                $common['password'],
                $common['user_type']
            ),
            'admin' => new Admin( // ✅ تم إضافته هنا
                $common['id'],
                $common['name'],
                $common['email'],
                $common['phone'],
                $common['password'],
                $common['user_type']
            ),
            default => throw new Exception("Unsupported user type: " . $data['user_type']),
        };
    }
}
