<?php

class LogoutController
{
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /car_assist_app/public/index.php");
        exit;
    }
}
?>