<?php

namespace App\Exception\Product;

use Exception;

class CannotFoundProductException extends \Exception
{
    public static string $messageNotFound='Une erreur  est survenue lors de la récupération du produit';

}
