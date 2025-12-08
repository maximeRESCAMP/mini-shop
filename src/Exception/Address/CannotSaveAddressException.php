<?php

namespace App\Exception\Address;

use Exception;

class CannotSaveAddressException extends Exception
{
    public static string $cannotMessage = 'delivery.address.exception.save';
}
