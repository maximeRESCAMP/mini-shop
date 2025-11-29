<?php

namespace App\Exception\CartItem;

use Exception;

class CannotQueryCartItemException extends \Exception
{
    public static string $messageQuerry='Une erreur  est survenue lors de la récuprération des panniers';

}
