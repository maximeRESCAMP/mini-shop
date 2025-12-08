<?php

namespace App\Exception\Product;

use Exception;

class CannotQueryProductException extends \Exception
{
    public static string $queryMessage='product.exception.query';

}
