<?php

namespace App\Exception\Address;

use Exception;

class CannotSaveAddressException extends Exception
{
    public static string $cannotMessage = 'Erreur lors de la sauvegarde de l\'adresse';
}
