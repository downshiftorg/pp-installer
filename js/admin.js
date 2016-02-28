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
   * Process successful retrieval of user registration
   *
   * @param {Object} response
   * @return {void}
   */
  function get_registration_done(response) {
    if (!response || typeof response !== 'object') {
      get_registration_fail();
      return;
    }

    $('#get-registration').removeClass('doing').addClass('success');
    $('#save-registration').removeClass('pending').addClass('doing');

    save_option('ppi_registration', JSON.stringify(response.data.attributes))
      .done(save_registration_done)
      .fail(save_registration_fail);
  }

  /**
   * Process successful saving of retrieved registration data
   *
   * @param {Object} response
   * @param {String} message
   * @param {Object} xhr
   * @return {void}
   */
  function save_registration_done(response, message, xhr) {
    if (!xhr || xhr.status !== 204) {
      save_registration_fail();
      return;
    }

    $('#save-registration').removeClass('doing').addClass('success');
    $('#download-p6').removeClass('pending').addClass('doing');

    $.get(window.ppi.links.install_p6)
      .done(install_p6_done)
      .fail(install_p6_fail);
  }

  /**
   * Process failure to retrieve registration from API
   *
   * @param {jqXhr} xhr
   * @return {void}
   */
  function get_registration_fail(xhr) {
    $('#get-registration').removeClass('doing').addClass('failure');
    $body.removeClass('installing-from-token');

    if (xhr && xhr.status === 403) {
      $body.addClass('too-many-attempts');
      return;
    }

    $body.addClass('get-registration-failure ppi-installing-error');
  }

  /**
   * Process failure to save retrieved registration
   *
   * @return {void}
   */
  function save_registration_fail() {
    $('#save-registration').removeClass('doing').addClass('failure');
    $body.removeClass('installing-from-token');
    $body.addClass('save-registration-failure ppi-installing-error');
  }

  /**
   * Process successful installation of ProPhoto 6
   *
   * @return {void}
   */
  function install_p6_done() {
    $('#download-p6').removeClass('pending').addClass('success');
    $('#p6-installed-successfully').show();
  }

  /**
   * Process failure to install ProPhoto 6
   *
   * @return {void}
   */
  function install_p6_fail() {
    $('#download-p6').removeClass('pending').addClass('failure');
    $body.addClass('download-p6-failure ppi-installing-error');
  }

  $(document).ready(function(){

    $body = $('body');

    $('#install-from-token').on('click', function() {
      $(this).remove();
      $body.addClass('installing-from-token');
      $.get(window.ppi.links.get_registration)
        .done(get_registration_done)
        .fail(get_registration_fail);
    });

    $('#dismiss-recommendations a').on('click', function() {
      $('#recommendations-wrap').hide();
      save_option('ppi_hide_recommendations', true);
    });
  });
})();
