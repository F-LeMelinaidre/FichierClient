/**
 * @author Le Mélinaidre Frédéric
 */

import {listStyle} from "./Rules.js";

export class AlertBox {


    /** @type {HTMLElement} */
    #element
    /** @type {string} */
    #elementId
    /** @type {string} */
    #message
    /** @type {string} */
    #style
    /** @type {HTMLElement} */
    #alertDiv

    constructor(element) {
        this.#element = element;
        this.#elementId = this.#element.id;
    }


    /**
     *
     * @param message
     * @param style
     */
    addBox(message, style) {

        this.#message = message;
        this.#style = style;

        this.#alertDiv = this.#createDiv();

        this.#element.insertAdjacentElement('afterend', this.#alertDiv);
    }

    /**
     *
     */
    removeBox() {

        if(this.#alertDiv) {
            this.#alertDiv.remove();
        }

    }

    /**
     *
     * @returns {HTMLElement}
     */
    #createDiv() {

        let div = document.getElementById(`Alert${this.#elementId}`);

        if(!div) {
            div = document.createElement('div');
            div.id = `Alert${this.#elementId}`;
            div.className = `invalid-message ${this.#style}`;
        }

        // si la div est déjà pour ne pas avoir 2 fois le même style ou si elle change de style
        // supprime les class de la div correspondant à la list des class
        // de Rules.js
        this.#removeStyle(div);
        div.classList.add(this.#style);

        div.textContent = this.#message;
        div.setAttribute('role', 'alert');

        return div;
    }

    /**
     * @var listStyle array Rules.js
     */
    #removeStyle(element) {
        let elementClassList = element.classList;
        elementClassList.remove.call(elementClassList, ...listStyle);
    }
}