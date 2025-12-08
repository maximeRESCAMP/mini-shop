<?php
namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum OrderStatus :string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

}


