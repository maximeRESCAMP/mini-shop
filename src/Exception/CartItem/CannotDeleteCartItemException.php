<?php

namespace App\Exception\CartItem;

use Exception;

class CannotDeleteCartItemException extends \Exception
{
    public static string $messageDelete='Une erreur  est survenue lors de la supression d\'un article du pannier';

}
