<?php

/**
 * Check if the P6 installer plugin is active
 *
 * @return booelan
 */
function p7i_p6_installer_active() {
    $activePlugins = get_option('active_plugins');
    return in_array('pp-installer-prophoto6/pp-installer.php', $activePlugins);
}

/**
 * Get the unique installer plugin registration data, if available
 *
 * @return string|null
 */
function p7i_get_registration() {
    $registrationPath = P7I_DIR . '/registration.php';

    if (! @file_exists($registrationPath)) {
        return null;
    }

    return include($registrationPath);
}

/**
 * Detect ProPhoto 7 sites that are not registered
 *
 * @return boolean
 */
function p7i_not_registered() {
  try {
      $prophoto = p7i_get_theme();
      if (! $prophoto) {
          return false;
      }

      $templatePath = $prophoto->get_stylesheet_directory();
      $autoload = "{$templatePath}/vendor/autoload.php";
      if (! @file_exists($autoload)) {
          return false;
      }

      require_once $autoload;
      $container = include("{$templatePath}/src/php/services.php");
      if (! $container) {
          return false;
      }

      $site = $container->make('ProPhoto\Core\Model\Site\Site');
      return ! $site->isRegistered();

  } catch (Exception $e) {
      return false;
  }
}

/**
 * Run on plugin deactivation. Disable test-drive mode, unfreezing any widgets
 *
 * @return void
 */
function p7i_deactivation() {
  if (p7i_test_driving()) {
    p7i_disable_test_drive();
  }
}

/**
 * Register prophoto container bindings
 *
 * @param \NetRivet\Container\Container $container
 * @return void
 */
function p7i_container_bindings($container) {
  if (! p7i_test_driving()) {
    return;
  }

  require_once(P7I_DIR . '/classes/ActiveDesign.php');

  $container->singleton(
    'ProPhoto\Core\Service\Design\ActiveDesign',
    'ProPhoto\InstallerPlugin\TestDrive\ActiveDesign'
  );
}

/**
 * Bootstrap js data for ProPhoto Manage Designs screen
 *
 * @return void
 */
function p7i_manage_designs_bootstrap() {
  if (! isset($_GET['page']) || $_GET['page'] !== 'pp-designs') {
      return;
  }

  $wpTheme = wp_get_theme(get_option('template'));
  $themeData = json_encode(array(
      'name' => $wpTheme->get('Name'),
      'screenshot' => $wpTheme->get_screenshot(),
  ));

  echo "<script>window.testDriveLiveTheme = $themeData;</script>";
}

/**
 * Bootstrap js data indicating we are test-driving
 *
 * @return void
 */
function p7i_testdriving_bootstrap() {
  echo "<script>window.testDriving = true;</script>";
}

/**
 * When test-driving, a request to set user working design should change live design
 *
 * @param string $designId
 * @param int $userId
 * @param \ProPhoto\Core\Model\Settings\SiteSettingsInterface $settings
 * @return void
 */
function p7i_set_working_design($designId, $userId, $settings) {
  p7i_delete_user_working_designs();
  $settings->set('live_design_id', $designId);
}

/**
 * Delete all user-meta designations of prophoto "working" designs
 *
 * @return void
 */
function p7i_delete_user_working_designs() {
  global $wpdb;
  $wpdb->delete($wpdb->usermeta, array('meta_key' => 'pp_working_design_id'));
}


/**
 * Extract registerable domain from string input
 *
 * @param string $url
 * @return string|false
 */
function p7i_extract_domain($url) {
    if (! is_string($url)) {
        return false;
    }

    $pattern = "/^(?:\w+:\/\/)?[^:?#\/\s]*?([^.\s]+\.(?:[a-z]{2,}|co\.uk|org\.uk|ac\.uk|net\.au|org\.au|com\.au|co\.za|co\.nz|com\.br|com\.ph|fot\.br|com\.sg|com\.tw|com\.pl))(?:[:?#\/]|$)/xi";
    preg_match($pattern, $url, $matches);
    if (! isset($matches[1])) {
        return false;
    }

    return strtolower($matches[1]);
}
