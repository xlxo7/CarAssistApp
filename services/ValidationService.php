<?php

namespace Services;

class ValidationService
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePasswordMatch(string $pass, string $confirm): bool
    {
        return $pass === $confirm;
    }

    public static function validateRequiredFields(array $data, array $required): array
    {
        $errors = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[] = ucfirst($field) . " is required.";
            }
        }
        return $errors;
    }

    public static function isPDF(array $file): bool
    {
        return isset($file['type']) && mime_content_type($file['tmp_name']) === 'application/pdf';
    }
}
