
window._ = require('lodash');

window.$ = window.jQuery = require('jquery');

// para que jquery mande siempre el token en el header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': window.Laravel['csrfToken']
    }
});
