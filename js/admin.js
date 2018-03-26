(function() {

  var $ = window.jQuery;
  var $body;

  /**
   * Save a WordPress option via ajax
   *
   * @param {string} key
   * @param {mixed} value
   * @return {$.Deferred}
   */
  function save_option(key, value) {
    return $.ajax({
      dataType: 'json',
      contentType: 'application/json',
      method: 'POST',
      url: window.ppi.links.save_option,
      data: JSON.stringify({
        key: key,
        value: value
      })
    });
  }

  /**
   * Process successful installation of ProPhoto 7
   *
   * @return {void}
   */
  function install_p7_done() {
    $('#download-p7').removeClass('pending').addClass('success');
    $('#p7-installed-successfully').show();
  }

  /**
   * Process failure to install ProPhoto 7
   *
   * @return {void}
   */
  function install_p7_fail() {
    $('#download-p7').removeClass('pending').addClass('failure');
    $body.addClass('download-p7-failure ppi-installing-error');
  }

  $(document).ready(function(){

    $body = $('body');

    $('#install-from-registration').on('click', function() {
      $(this).remove();
      $body.addClass('installing-from-registration');

      $.get(window.ppi.links.install_p7)
        .done(install_p7_done)
        .fail(install_p7_fail);
    });

    $('#dismiss-recommendations a').on('click', function() {
      $('#recommendations-wrap').hide();
      save_option('ppi_hide_recommendations', true);
    });
  });
})();
