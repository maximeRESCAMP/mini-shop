<?php

namespace App\Exception\Address;

use Exception;

class AddressUserNotFoundException extends Exception
{
    public static string $userNotFound = 'Impossible de trouver des adresses';
}
