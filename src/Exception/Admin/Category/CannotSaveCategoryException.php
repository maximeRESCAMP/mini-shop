<?php

namespace App\Exception\Admin\Category;

use Exception;

class CannotSaveCategoryException extends \Exception
{
    public static string $messageSave='Une erreur  est survenue lors de l\'enregistrement de la categorie';

}
