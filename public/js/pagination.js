/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 * TODO Rendre compatible avec apiRequest
 */

const PARAMS = {
    template: 'tbody tr:first',
    pagination: {
        per_page: 10,
        range: 3,
        boundary: true
    }
}

let url;
let table;
let template;
let current_page = 1;
let nb_page;

$(document).ready(function() {
    table = $('table[data-paginate]');
    url = table.data('url');
    template = table.find(PARAMS.template).clone();

    loadTableData();

});

function loadTableData() {
    let per_page = PARAMS.pagination.per_page;

    $.ajax({
        url: `${url}/${current_page}/${per_page}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            table.find('tbody').empty();

            response.data.forEach(item => {
                let row = template.clone();

                for(let k in item) {
                    if(item.hasOwnProperty(k)) {
                        let class_name = k.replace('_', '-');
                        row.find(`.${class_name}`).text(item[k]);
                    }
                }

                table.find('tbody').append(row);
            });

            nb_page = response.nb_page;
            let paging = createPagingData(current_page);
            let paging_template = getPagingTemplate(paging, current_page);

            table.siblings('.paginator').remove();
            table.after(paging_template);

            addEventListener(paging_template, paging);
        }
    });

}



function createPagingData(current_page) {
    let range = PARAMS.pagination.range;
    let paging = [];

    if (current_page > range) {
        paging.push({
            text: 'Première Page',
            type: 'first'
        });
    }

    if (current_page > 1) {
        paging.push({
            text: 'Précédant',
            type: 'previous'
        });
    }

    let start_page = Math.max(1, current_page - range);
    let end_page = Math.min(nb_page, current_page + range);

    let i = start_page;
    while (i <= end_page) {
        paging.push({
            text: i,
            type: i === current_page ? 'active' : 'page'
        });
        i++;
    }

    if (current_page < nb_page) {
        paging.push({
            text: 'Suivant',
            type: 'next'
        });
    }

    if (current_page < nb_page && i !== nb_page + 1) {
        paging.push({
            text: 'Dernière Page',
            type: 'last'
        });
    }

    return paging;
}

function getPagingTemplate(paging, current_page) {
    const paging_template = $('<div class="paginator">');
    const ul = $('<ul>');

    paging.forEach(page => {
        let li = $('<li>').text(page.text);

        if (page.text === current_page) {
            li.addClass('active');
        }
        ul.append(li);
    });
    return paging_template.append(ul);
}

function addEventListener(paging_template, paging) {
    paging_template.find('li').each(function(index) {
        let page = paging[index]; // Récupère la page correspondante à l'index

        $(this).on('click', function() {
            switch (page.type) {
                case 'first':
                    current_page = 1;
                    break;
                case 'previous':
                    current_page--;
                    break;
                case 'next':
                    current_page++;
                    break;
                case 'last':
                    current_page = nb_page;
                    break;
                case 'page':
                    current_page = page.text;
                    break;
                default:
                    break;
            }

            loadTableData();
        });
    });
}