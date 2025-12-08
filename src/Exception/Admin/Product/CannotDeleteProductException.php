<?php

namespace App\Exception\Admin\Product;

use Exception;

class CannotDeleteProductException extends \Exception
{
    public static string $messageDelete='product.exception.save';

}
