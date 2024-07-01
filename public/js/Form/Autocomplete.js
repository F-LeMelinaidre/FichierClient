/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */


export class Autocomplete {

    /** @type {HTMLElement} */
    #ul

    /** @type {HTMLElement} */
    #li

    /** @type {HTMLElement} */
    #input

    /** @type {Array} */
    #items

    /** @type {Array} */
    #item_keys;

    constructor(input, params)
    {

        this.#input = input;
        this.#item_keys = params.items;

        this.#createList();
        this.#addEventListener();
    }

    refresh(items = [])
    {
        if (items.length > 0) {

            this.#items = items;
            this.#ul.empty();
            this.#addList(items);

        } else {
            this.#removeList()
        }


        console.log(`\x1b[36mitems.length ${items.length}`)
        console.log("\x1b[32mrefresh() Autocomplete...");
    }


    #createList()
    {
        this.#ul = $('<ul class="autocomplete hidden">');

        this.#ul.insertAfter(this.#input);
    }


    #addList(items)
    {
        items.forEach((item) => {
            this.#ul.append(`<li data-id="${item.id}">${this.#formatLabel(item)}</li>`);
        });

        this.#ul.removeClass('hidden');
    }

    #removeList()
    {
        this.#items = [];
        this.#ul.addClass('hidden');
        this.#ul.empty();
    }

    #addEventListener() {

        this.#ul.on('click', 'li', (event) => {

            this.#li = $(event.currentTarget);
            this.#ul.addClass('hidden');

            this.#updateInput(this.#li);

            let item = this.#items.find(item => item.id === this.#li.data('id'));

            let selectEvent = new CustomEvent('selectEvent', {
                bubbles: true,
                cancelable: true,
                detail: item
            });

            document.dispatchEvent(selectEvent);

            this.#input.val('');
            this.#removeList();
        });

        $(document).on('click', (event) => {
            if (!$(event.target).closest(this.#input).length && !$(event.target).closest(this.#ul).length) {
                this.#removeList();
            }
        });
    }

    #updateInput(li) {

        let id = li.data('id');
        let item = this.#items.find(item => item.id === id);
        this.#input.val(this.#formatLabel(item));
    }

    #formatLabel(item) {
        return this.#item_keys.map(key => item[key]).join(' ');
    }
}