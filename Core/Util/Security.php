<?php

namespace Core\Util;

class Security
{

    public static function sanitize($value): string
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

}