/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

import {AlertBox} from "./AlertBox.js";
import {rules} from "./Rules.js";

/**
 * @author Le Mélinaidre Frédéric
 *
 * Validation des champs
 * import Rules.js
 * Constante des règles de validation
 *
 * propriétés à définir sur les inputs :
 *
 * nom de class .js-validation
 *
 * propriétés data
 *
 * data-validation = règle de validation
 *
 * propriété aria-required prise en compte
 */
export class ValidateClass {

    /** @type {AlertBox} */
    #alertBox
    /** @type {HTMLElement} */
    #elementToCompare
    /** @type {HTMLElement} */
    #element
    /** @type {string} */
    #message
    /** @type {boolean} */
    #required
    /** @type {string} */
    #style
    /** @type {array} */
    #validations
    /** @type {boolean | null} */
    #valid= null;
    /** @type {Event} */
    validationChangeEvent

    /**
     *
     * @param element
     */
    constructor (element) {

        this.#element = element
        this.#alertBox = new AlertBox(this.#element);

        this.#validations = (element.dataset.validation) ? element.dataset.validation.split(' ') : [];
        if(this.#validations.includes('equal')) this.#setCompareValidation();

        this.#required = element.getAttribute('aria-required') === 'true';
        if(this.#required) this.#validations.push('require');

        this.validationChangeEvent = new Event('validationChange');

        this.#addFocusEvent(this.#element);
        this.#addInputEvent(this.#element);
        this.#addChangeEvent(this.#element);

    }

    getElement() {
        return this.#element;
    }

    getValid() {
        return this.#valid;
    }
    /**
     * l'input de comparaison
     * @returns {HTMLElement}
     */
    #setCompareValidation() {
        let elementToCompareId = this.#element.dataset.compareWith.substring(1);
        this.#elementToCompare = document.getElementById(elementToCompareId);

        this.#addFocusEvent(this.#elementToCompare);

        this.#addInputEvent(this.#elementToCompare);
    }

    /**
     *
     * @param element
     */
    #addFocusEvent(element) {
        element.addEventListener('focusout', () => { this.checkValidity() });
    }

    /**
     *
     * @param element
     */
    #addInputEvent(element) {
        element.addEventListener('input', () => {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => { this.checkValidity() }, 500);
        });
    }

    /**
     *
     * @param element
     */
    #addChangeEvent(element) {

        element.addEventListener('autocomplete', () => { this.checkValidity() });
    }

    /**
     *
     */
    checkValidity() {
        this.#valid = null;

        let i = 0;
        let nb = this.#validations.length-1;
        while((this.#valid || null === this.#valid) && i<= nb ) {

            let rule = this.#validations[i];
            this.#valid = this.#isValid(rule);
            i++;

        }

        this.#element.dispatchEvent(this.validationChangeEvent);
        this.#setDisplay(!this.#valid);

    }


    /**
     *
     * @param rule
     * @returns {boolean}
     */
    #isValid(rule) {
        let values = [this.#element.value];
        let validationRule = rules[rule];

        this.#message = validationRule.message;
        this.#style = validationRule.style;

        if('equal' === rule) {
            values.push(this.#elementToCompare.value);
        }

        return validationRule.callback(...values);

    }

    /**
     * @param show
     * @param rule
     */
    #setDisplay(show) {
        let alertBox = this.#alertBox;

        if(show) {
            this.#addAriaAttribute();

            alertBox.addBox(this.#message,this.#style);

        } else {

            this.#message = '';
            this.#style = 'valid';
            this.#removeAriaAttribute();

            alertBox.removeBox();

        }

        if(this.#elementToCompare) this.#updateInputStyle(this.#elementToCompare);
        this.#updateInputStyle(this.#element);
    }

    /**
     *
     * @param element
     */
    #updateInputStyle(element) {
        element.classList.remove('valid','invalid','error');
        element.classList.add(this.#style);
    }

    /**
     * Utilisé pour les utilisateurs utilisant des technologies d'assistance
     * aria-describedby cible l'élément concerné par le message
     * aria-invalid défini l'input comme invalid
     */
    #addAriaAttribute() {
        let id = this.#element.id;

        this.#element.setAttribute('aria-describedby', `Alert${id}`);
        this.#element.setAttribute('aria-invalid', 'true');
    }

    /**
     *
     */
    #removeAriaAttribute() {
        this.#element.removeAttribute('aria-describedby');
        this.#element.removeAttribute('aria-invalid');
    }

}