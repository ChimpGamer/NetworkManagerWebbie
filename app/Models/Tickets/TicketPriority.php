<?php

namespace App\Models\Tickets;

enum TicketPriority: int
{
    case NONE = 0;
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function name(): string
    {
        return self::getName($this);
    }

    public function buttonStyleClass(): string
    {
        return match ($this) {
            TicketPriority::NONE => "btn-outline-secondary",
            TicketPriority::LOW => "btn-outline-success",
            TicketPriority::MEDIUM => "btn-outline-warning",
            TicketPriority::HIGH => "btn-outline-danger"
        };
    }

    public function iconColorClass(): string
    {
        return match ($this) {
            TicketPriority::NONE => "text-secondary",
            TicketPriority::LOW => "text-success",
            TicketPriority::MEDIUM => "text-warning",
            TicketPriority::HIGH => "text-danger"
        };
    }

    public static function getName(self $type): string
    {
        return match ($type) {
            TicketPriority::NONE => 'None',
            TicketPriority::LOW => 'Low',
            TicketPriority::MEDIUM => 'Medium',
            TicketPriority::HIGH => 'High',
        };
    }
}
