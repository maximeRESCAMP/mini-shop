<?php

namespace App\Exception\Address;

use Exception;

class AddressAlreadyExistsException extends Exception
{
    public static string $addressAlready = 'delivery.address.exception.already_exist';
}
