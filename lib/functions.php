<?php

/**
 * Get the unique installer plugin registration data, if available
 *
 * @return string|null
 */
function ppi_get_registration() {
    $registrationPath = PPI_DIR . '/registration.php';

    if (! @file_exists($registrationPath)) {
        return null;
    }

    return include($registrationPath);
}

/**
 * Detect ProPhoto 6 sites that are not registered
 *
 * @return boolean
 */
function ppi_p6_not_registered() {
  try {
      $p6 = ppi_get_p6_theme();
      if (! $p6) {
          return false;
      }

      $templatePath = $p6->get_stylesheet_directory();
      $autoload = "{$templatePath}/vendor/autoload.php";
      if (! @file_exists($autoload)) {
          return false;
      }

      require_once $autoload;
      $container = include("{$templatePath}/services.php");
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
function ppi_deactivation() {
  if (ppi_test_driving()) {
    ppi_disable_test_drive();
  }
}

/**
 * Register P6 container bindings
 *
 * @param \NetRivet\Container\Container $container
 * @return void
 */
function ppi_container_bindings($container) {
  if (! ppi_test_driving()) {
    return;
  }

  require_once(PPI_DIR . '/classes/ActiveDesign.php');

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
function ppi_manage_designs_bootstrap() {
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
function ppi_testdriving_bootstrap() {
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
function ppi_set_working_design($designId, $userId, $settings) {
  ppi_delete_user_working_designs();
  $settings->set('live_design_id', $designId);
}

/**
 * Delete all user-meta designations of P6 "working" designs
 *
 * @return void
 */
function ppi_delete_user_working_designs() {
  global $wpdb;
  $wpdb->delete($wpdb->usermeta, array('meta_key' => 'pp_working_design_id'));
}
