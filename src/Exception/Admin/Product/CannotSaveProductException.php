<?php

namespace App\Exception\Admin\Product;

use Exception;

class CannotSaveProductException extends \Exception
{
    public static string $messageQuerry='Une erreur  est survenue lors de la sauvegarde du produit';

}
