<?php

namespace App\Exception\CartItem;

use Exception;

class CannotDeleteCartItemException extends Exception
{
    public static string $messageDelete='cart_item.exception.delete';

}
