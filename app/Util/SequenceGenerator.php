<?php
namespace App\Util;

class SequenceGenerator{

    const ORDER_PREFIX='SOC';
    public static function generateOrderId(): string
    {
        return self::ORDER_PREFIX . time();
    }
}
