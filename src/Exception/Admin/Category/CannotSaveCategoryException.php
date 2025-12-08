<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotSaveCategoryException extends \Exception
{
    public static string $messageSave='category.exception.save';

}
