<?php

namespace App\Exception\CartItem;

use Exception;

class CannotQueryCartItemException extends \Exception
{
    public static string $messageQuerry = 'cart_item.exception.query';

}
