<?php

namespace App\Exception\Address;

use Exception;

class CannotQueryAddressException extends Exception
{
     public static string $queryMessage = 'delivery.address.exception.query';
}
