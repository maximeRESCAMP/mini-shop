<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotQueryCategoryException extends \Exception
{
    public static string $messageQuerry='Une erreur  est survenue lors de la récuprération de la categorie';

}
