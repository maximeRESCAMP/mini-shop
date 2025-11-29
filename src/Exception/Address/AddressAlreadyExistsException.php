<?php

namespace App\Exception\Address;

use Exception;

class AddressAlreadyExistsException extends Exception
{
    public static string $addressAlready = 'Adresse déja éxistante';
}
