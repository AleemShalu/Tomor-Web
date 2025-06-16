<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderStatusEnum: string
{
    use EnumTrait;

    case RECEIVED = 'received';
    case PROCESSING = 'processing';
    case FINISHED = 'finished';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case CANCELLED = 'canceled';
    case PENDING_PAYMENT = 'pending_payment';

    public function getColor(): string
    {
        return match ($this) {
            self::RECEIVED => '#ADD8E6',         // Light Blue
            self::PROCESSING => '#FFEB3B',       // Bright Yellow
            self::FINISHED => '#4CAF50',         // Medium Green
            self::DELIVERING, self::DELIVERED => '#2E7D32',  // Medium Green, Dark Green
            self::CANCELLED => '#FF5733',         // Crimson Red
            self::PENDING_PAYMENT => '#00ffc2',  // Cyan
            default => '#9E9E9E'                 // Gray
        };
    }
}
