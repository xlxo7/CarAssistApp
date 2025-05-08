<?php

interface OrderStatusStrategy
{
    public function getNextStatus(string $current): string;
}
