<?php

namespace App\Exception\Order;

use Exception;

class CannotQueryOrderException extends \Exception
{
    public static string $messageQuerry='order.exception.query';

}
