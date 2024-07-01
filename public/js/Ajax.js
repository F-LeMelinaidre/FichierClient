/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

export class Ajax {

    /** @type {Array} */
    #params

    /** @type {String} */
    #method

    /** @type {String} */
    #url

    constructor(url,method,params = [])
    {
        this.#url = url;
        this.#method = method.toUpperCase();
        this.#params = params;
    }

    async loadData()
    {

        try {
            const response = await $.ajax({
                url: `../${this.#url}`,
                method: this.#method,
                data: this.#params
            });

            return response;
        } catch (error) {
            console.error('Erreur de requête :', error.statusText);
            return false;
        }
    }
}
