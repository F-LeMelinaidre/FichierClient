/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

export class SubmitForValidateClass {

    /** @type {HTMLElement} */
    #button
    /** @type {array} */
    #validationInstances
    #timer;

    constructor(button, inputInstances) {
        this.#button = button
        this.#button.disabled = true;

        this.#initEventListener(inputInstances);
    }

    /**
     *
     * @param instances
     */
    #initEventListener(instances) {

        this.#validationInstances = instances;

        this.#validationInstances.forEach(instance => {
            const input = instance.getElement();

            input.addEventListener('validationChange', () => {
                this.#updateValidity();
            });

        });



        const containerButton = this.#button.parentElement;
        containerButton.addEventListener('mouseover', () => {
            this.#checkAllFieldsValidity(); // Vérifier la validité de tous les champs lors du survol du parent du bouton
        });
    }

    /**
     *
     */
    #updateValidity() {
        const allValid = this.#validationInstances.every(instance => instance.getValid());
        this.#button.disabled = !allValid;
    }

    #checkAllFieldsValidity() {
        this.#validationInstances.forEach(instance => {
            if (typeof instance.checkValidity === 'function') {
                instance.checkValidity();
            } else {
                console.error('Instance checkValidity method:', instance);
            }
        });
        this.#updateValidity();
    }

}