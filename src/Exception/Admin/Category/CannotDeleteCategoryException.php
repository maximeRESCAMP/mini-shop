<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotDeleteCategoryException extends \Exception
{
    public static string $messageRemove='category.exception.delete';

}
