<?php

namespace App\Exception\Address;

use Exception;

class CannotQueryAddressException extends Exception
{
    public static string $cannotMessage = 'Erreur lors de la récupération de l\'adresse';
}
