<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotQueryCategoryException extends \Exception
{
    public static string $messageQuerry='category.exception.query';

}
