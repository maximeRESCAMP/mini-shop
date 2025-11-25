<?php

namespace App\Exception;

use Exception;

class AddressAlreadyExistsException extends Exception
{
    public static string $addressAlready = 'Adresse déja éxistante';
}
