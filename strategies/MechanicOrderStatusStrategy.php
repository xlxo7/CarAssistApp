<?php

require_once __DIR__ . '/OrderStatusStrategy.php';

class MechanicOrderStatusStrategy implements OrderStatusStrategy
{
    public function getNextStatus(string $current): string
    {
        return match ($current) {
            'accepted' => 'on_the_way',
            'on_the_way' => 'completed',
            default => $current, // لا تغيّر لو الحالة خارج النظام
        };
    }
}
