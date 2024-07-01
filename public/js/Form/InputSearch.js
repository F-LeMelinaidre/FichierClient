/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

import {Ajax} from "../Ajax.js";
import {Autocomplete} from "./Autocomplete.js";

class InputSearch {

    /** @type {Class} */
    #ajax

    /** @type {Class} */
    #autocomplete


    #input_search

    /** @type {String} */
    #search_item

    /** @type {String} */
    #url

    /** @type {Array} */
    #params

    match = []

    i = 0

    constructor(element) {

        this.#input_search = $(element);

        if (this.#input_search.length === 0) throw new Error("Input #Input-Search manquant !");

        if (!this.#input_search.data('search')) throw new Error("Paramètre data-search manquant !");

        let params = this.#input_search.data('search');

        this.#setSearchItem(params.items);

        this.#initAjax(params.end_point);

        this.#autocomplete = new Autocomplete(this.#input_search, params);

        this.#run();
    }

    #initAjax(end_point) {

        if (typeof end_point === 'string' && end_point.trim() !== '') {

            let url = end_point.replace(/\./g, '/');
            this.#ajax = new Ajax(url, 'GET');

        } else {

            throw new Error("Paramètre data-search end_point doit être une chaîne de caractères non vide !");

        }

    }

    #setSearchItem(items) {

        if (items == null) {

            throw new Error("Paramètre data-search items est manquant !");

        } else if (!Array.isArray(items)) {

            throw new Error("Paramètre data-search items doit être un tableau !");

        }

        this.#search_item = items;

    }

    async #run() {
        // fichier Json chargé dans son intégralité
        // TODO Modifier pour le chargement partiel / bd
        let items = await this.#ajax.run();

        this.#addEventListener(items);

    }

    #addEventListener(items) {

        this.#input_search.on('input', () => {

            if (this.#input_search.val().length > 1) {
                clearTimeout(this.timer);

                this.timer = setTimeout(() => {

                    this.#searchItem(this.#input_search.val(), items)

                }, 300);

            } else {
                this.#autocomplete.refresh();
            }


        });

        this.#input_search.on('click', () => {
            if (this.#input_search.val().length > 1) {


                this.#searchItem(this.#input_search.val(), items);

            } else {
                this.#autocomplete.refresh();
            }

        });

    }


    #searchItem(value, items) {
        value = (typeof value === 'string')? value.toLowerCase() : value;
        value.trim();

        let items_list = items.filter(item => {

            // Concaténation des valeurs des propriétés spécifiées dans #search_item

            let concat_values = this.#search_item.map(key => {
                let value = item[key];
                return (typeof value === 'string')? value.toLowerCase() : value;
            }).join(' ');

            // Si la chaîne concaténée commence par la recherche,
            // sortie de la fonction filter,
            // ajout de l'item courant testé au tableau filter_items
            if (concat_values.startsWith(value)) {
                return true;
            }


            // tableau des mots de la recherche
            let search_terms = value.split(' ');

            // pour chaque mot dans la recherche
            return search_terms.every(term => {

                // teste si au moins un mot de la chaîne concaténée, transformée en tableau,
                // commence par le terme courant de every
                return concat_values.split(' ').some(word => word.startsWith(term));
            });
            // si les conditions de every et some son remplies, retourne true,
            // ajout de l'item courant au tableau filter_items
        });

        this.#autocomplete.refresh(items_list);
    }

}
$(document).ready(function() {
    $('[data-search]').each(function() {
        try {
            new InputSearch(this);
        } catch (error) {
            console.error("Erreur lors de l'initialisation de InputSearch() :", error.message);
        }
    });
});
export { InputSearch };