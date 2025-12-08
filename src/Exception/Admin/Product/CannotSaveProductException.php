<?php

namespace App\Exception\Admin\Product;

use Exception;

class CannotSaveProductException extends \Exception
{
    public static string $messageQuerry='product.error.save';

}
