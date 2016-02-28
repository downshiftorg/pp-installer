jQuery(document).ready(function($){

  var options = $.extend(ppi_pointer, {
    close: function() {
      $.post(ajaxurl, {
        pointer: 'ppi_pointer',
        action: 'dismiss-wp-pointer'
      });
    }
  });

  $(ppi_pointer.target).pointer(options).pointer('open');
});
