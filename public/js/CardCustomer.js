/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

import {Ajax} from "./Ajax";

class CardCustomer {

    /** @type {HTMLElement} */
    #card = $('#Card');

    /** @type {Array} */
    #item

    constructor()
    {
        document.addEventListener('selectEvent', (event) => {

            this.#item = event.detail;
            console.log('\x1b[32mselectEvent :', this.#item);

            this.#showCard();

        });
    }

    #showCard()
    {
        $('#Card').removeClass('hidden');

        $('#Card .fullname').text(`${this.#item.last_name} ${this.#item.first_name}`);
        $('#Card .id span').text(`${this.#item.id}`);
        $('#Card .phone span').text(`${this.#item.phone}`);
        $('#Card .address span').text(`${this.#item.full_address}`);
        $('#Card .zip span').text(`${this.#item.zip}`);
        $('#Card .city span').text(`${this.#item.city}`);

        $('#Card .btn').on('click', () => {
            this.#deleteCustomer();
        });
    }

    #deleteCustomer()
    {
        let ajax = new Ajax('ajax/customer/delete','post',{id:`${this.#item.id}`});
        if (window.confirm("Confirmer la suppression")) {
            if(ajax.run()) {
                window.location.pathname = '/liste-clients';
            }
        }


    }

}

let cardCustomer = new CardCustomer();
