<?php

namespace App\Exception\Admin\Product;

use Exception;

class CannotDeleteProductException extends \Exception
{
    public static string $messageDelete='Une erreur  est survenue lors de la supression du produit';

}
