<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotDeleteCategoryException extends \Exception
{
    public static string $messageRemove='Une erreur  est survenue lors de la supression de la categorie';

}
