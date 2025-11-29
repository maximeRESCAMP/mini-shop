<?php

namespace App\Exception;
use Exception;
class CodeNotFoundException extends Exception
{
    public static string $codeNotFound = 'Le code n\'as pas plus être trouvé';

}
