<?php

require_once 'User.php';

class Admin extends User
{
    public function __construct($id, $name, $email, $phone, $password, $user_type)
    {
        parent::__construct($id, $name, $email, $phone, $password, $user_type);
    }
}
