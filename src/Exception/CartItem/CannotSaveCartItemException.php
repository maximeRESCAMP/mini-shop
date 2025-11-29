<?php

namespace App\Exception\CartItem;

use Exception;

class CannotSaveCartItemException extends \Exception
{
    public static string $messageSave='Une erreur  est survenue lors de l\enregistrement d\'un article au pannier';

}
