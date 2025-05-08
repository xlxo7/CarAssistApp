<?php

require_once __DIR__ . '/User.php';

class Customer extends User {

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phone,
        string $password,
        string $user_type
    ) {
        parent::__construct($id, $name, $email, $phone, $password, $user_type);
    }

    // ممكن نضيف دوال خاصة بالعميل لاحقًا هنا
}
