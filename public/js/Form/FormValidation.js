

/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

if (document.querySelector('.js-form')) {
    (async () => {

        const validateInstances = await initValidateInput();

        const submitButton = document.querySelector('.js-submit-button');



        if (submitButton) {
            const {SubmitForValidateClass} = await import("./SubmitForValidateClass.js");

            new SubmitForValidateClass(submitButton, validateInstances);
        }

    })();
}
async function initValidateInput() {
    const {ValidateClass} = await import("./ValidateClass.js");

    const validateInstances = [];

    document.querySelectorAll('.js-validation').forEach(elem => {

        validateInstances.push(new ValidateClass(elem));

    });

    return validateInstances;
}



