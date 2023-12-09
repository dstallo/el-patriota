
/*
<a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request
- Or, request confirmation in the process -
<a href="posts/2" data-method="delete" data-confirm="Are you sure?">
*/

(function() {

  var a_metodo_rest_laravel = {
    initialize: function() {
      this.methodLinks = $('a[data-method]');

      this.registerEvents();
    },

    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function(e) {
      var link = $(this);
      var httpMethod = link.data('method').toUpperCase();
      var form;

      // If the data-method attribute is not PUT or DELETE,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
        return;
      }

      // Allow user to optionally provide data-confirm="Are you sure?"
      if ( link.data('confirm') ) {
        if ( ! a_metodo_rest_laravel.verifyConfirm(link) ) {
          return false;
        }
      }

      form = a_metodo_rest_laravel.createForm(link);
      form.submit();

      e.preventDefault();
    },

    verifyConfirm: function(link) {
      //TODO: reemplazar esto por sweetalert
      return confirm(link.data('confirm'));
    },

    createForm: function(link) {
      var form = 
      $('<form>', {
        'method': 'POST',
        'action': link.attr('href')
      });

      var token = 
      $('<input>', {
        'type': 'hidden',
        'name': 'csrf_token',
          'value': window.Laravel.csrfToken //depende de completar esta variable en los layout de blade
        });

      var hiddenInput =
      $('<input>', {
        'name': '_method',
        'type': 'hidden',
        'value': link.data('method')
      });

      return form.append(token, hiddenInput)
                 .appendTo('body');
    }
  };

  a_metodo_rest_laravel.initialize();

})();