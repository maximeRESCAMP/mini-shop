<?php

namespace App\Exception\Order;

use Exception;

class CannotQueryOrderException extends \Exception
{
    public static string $messageQuerry='Une erreur  est survenue lors de la récuprération des commandes.';

}
