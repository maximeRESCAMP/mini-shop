<?php

namespace App\Exception\CartItem;

use Exception;

class CannotSaveCartItemException extends Exception
{
    public static string $messageSave='cart_item.exception.save';

}
