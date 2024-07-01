/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

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

            this.#showForm();

        });
    }

    #showForm()
    {
        $('form').removeClass('hidden');

        $('#Customer #LastName').val(`${this.#item.last_name}`);
        $('#Customer #FirstName').val(`${this.#item.first_name}`);
        $('#Customer-Id span').val(`${this.#item.id}`);
        $('#Customer #Phone').val(`${this.#item.phone}`);
        $('#Customer #StreetNumber').val(`${this.#item.address.street_number}`);
        $('#Customer #StreetType').val(`${this.#item.address.street_type}`);
        $('#Customer #StreetName').val(`${this.#item.address.street_name}`);
        $('#Customer #Zip').val(`${this.#item.zip}`);
        $('#Customer #City').val(`${this.#item.city}`);
    }


}

let cardCustomer = new CardCustomer();
