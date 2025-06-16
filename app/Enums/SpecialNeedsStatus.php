<?php

namespace App\Enums;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
enum SpecialNeedsStatus
{
    case APPROVED;
    case PENDING;
    case REVIEWING;
    case REJECTED;

    public function getSpecialNeedsStatus() {
        return match ($this) {
            SpecialNeedsStatus::APPROVED => 'approved',
            SpecialNeedsStatus::PENDING => 'pending',
            SpecialNeedsStatus::REVIEWING => 'reviewing',
            SpecialNeedsStatus::REJECTED => 'rejected',
        };
    }
}
