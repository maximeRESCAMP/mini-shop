<?php

namespace App\Exception;
use Exception;
class CodeNotFoundException extends Exception
{
    public static string $codeNotFound = 'general.exception.not_found';

}
