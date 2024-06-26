<?php

namespace Core\Util;

use Error;

class Validation
{

    public const ALPHA = 'alpha';
    public const ALPHA_NUMERIC = 'alpha_numeric';
    public const DATE = 'date';
    public const EMAIL = 'email';
    public const NUMERIC = 'numeric';
    public const PASSWORD = 'password';
    public const REQUIRED = 'required';
    public const STREET_NUMBER = 'street_number';


    // Regex Email norme RFC2822
    // Validation mot de passe 8 caratères minimum et au moins un caratères majuscule, minuscule, un chiffre, et un caratères spéciaux !?:_\-*#&%+
    private const PATTERNS = [
        'alpha'         => '/^[a-zA-Zàâçéèêëïîôùûüÿ\-\s]+$/',
        'alpha_numeric' => '/^[0-9a-zA-Zàâçéèêëïîôùûüÿ\-\s]+$/',
        'date'          => '/^\d{4}-\d{2}-\d{2}$/',
        'email'         => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
        'numeric'       => '/^[0-9]+$/',
        'password'      => '/^(?=.*[a-zA-Z0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[!?:_\-*#&%+]).{8,}$/',
        'street_number' => '/^\d[\d]*[a-zA-Z]*$/'
    ];

    private const MESSAGES = [
        'alpha'         => 'Caractères alphabétiques uniquement!',
        'alpha_numeric' => 'Caractères alpha-numériques uniquement!',
        'date'          => 'La date n\'est pas au bon format!',
        'email'         => 'Votre email ne respecte pas la norme RFC2822',
        'numeric'       => 'Caractères numériques uniquement!',
        'password'      => '8 caractères minimum, comprenant au minimum une majuscule, une minuscule, un chiffre, et un caractère !?:_-*#&%+',
        'required'      => 'Champ requis!',
        'street_number' => 'Formats uniquement autorisés 1, 1bis, 1ter ...'
    ];

    public static function getPattern(string $name): string
    {
        try {
            if (!array_key_exists($name, self::PATTERNS)) {
                throw new Error("Le pattern '$name' n'existe pas.");
            }

            return self::PATTERNS[$name];
        } catch (Error $e) {
            echo $e->getMessage();

            return '';
        }

    }

    public static function getMessage(string $name): string
    {
        try {
            if (!array_key_exists($name, self::MESSAGES)) {
                throw new Error("Le message pour le pattern '$name' n'existe pas.");
            }

            return self::MESSAGES[$name];
        } catch (Error $e) {

            echo $e->getMessage();
            return '';
        }
    }
}