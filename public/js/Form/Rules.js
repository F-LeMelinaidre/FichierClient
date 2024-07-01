/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

/** @author Le Mélinaidre Frédéric
 *
 * @type {{equal: {callback: (function(*): boolean), style: string, message: string}, password: {callback: (function(*): boolean), style: string, message: string}, alpha: {callback: (function(*): boolean), style: string, message: string}, alphaNumeric: {callback: (function(*): boolean), style: string, message: string}, minLength: {callback: (function(*, *): boolean), style: string, message: (function(*): string)}, numeric: {callback: (function(*): boolean), style: string, message: string}, require: {callback: (function(*): boolean), style: string, message: string}, alphaNumericExtended: {callback: (function(*): boolean), style: string, message: string}, email: {callback: (function(*): boolean), style: string, message: string}}}
 */
export const rules = {
    alpha: {
        callback: (value) => {
            const regex = new RegExp(/^[a-zA-Zàâçéèêëïîôùûüÿ\-\s]+$/);

            return regex.test(value) || 0 === value.length;
        },
        message: `Caractères alphabétiques uniquement !`,
        style: `error`
    },
    alphaNumeric: {
        callback: (value) => {
            const regex = new RegExp(/^[0-9a-zA-Zàâçéèêëïîôùûüÿ\-\s]+$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `Caractères alpha-numériques uniquement !`,
        style: `error`
    },
    alphaNumericExtended: {
        callback: (value) => {
            const regex = new RegExp(/^[0-9a-zA-Zàâçéèêëïîôùûüÿ\-!?:,.\s()&']+$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `Uniquement les caractères alpha-numériques et -!?:,.()& autorisés !`,
        style: `error`
    },
    email: {
        callback: (value) => {
            const regex = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `Votre email ne respete pas la norme RFC2822 !`,
        style: `error`
    },
    equal: {
        callback: (value, compValue) => {
            return value === compValue;
        },
        message: `Les champs ne sont pas identique !`,
        style: `invalid`
    },
    minLength: {
        callback: (value, min) => {
            return (min <= value.length) || 0 === value.length;
        },
        message: (min) => `Doit comporter au minimum ${min} caratères !`,
        style: `error`
    },
    numeric: {
        callback: (value) => {
            const regex = new RegExp(/^[0-9]+$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `Caractères numériques uniquement !`,
        style: `error`
    },
    password: {
        callback: (value) => {
            const regex = new RegExp(/^(?=.*[a-zA-Z0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[!?:_\-*#&%+]).{8,}$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `8 caratères minimum, comprenant au minimum une majascule, une minusclule, un chiffre, et un caratère !?:_-*#&%+ !`,
        style: `error`
    },
    require: {
        callback: (value) => {
            return value.length !== 0;
        },
        message: `Champs requis !`,
        style: `invalid`
    },
    streetNumber: {
        callback: (value) => {
            const regex = new RegExp(/^\d[\d]*[a-zA-Z]*$/);
            return regex.test(value) || 0 === value.length;
        },
        message: `Formats uniquement autorisés 1, 1bis, 1ter ...`,
        style: `error`
    }
}


export const listStyle = [
    'error',
    'invalid',
    'valid'
]