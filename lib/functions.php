<?php

/**
 * Get the unique installer plugin token, if available
 *
 * @return string|null
 */
function ppi_get_token() {
    $tokenPath = PPI_DIR . '/token.php';

    if (! @file_exists($tokenPath)) {
        return null;
    }

    return include($tokenPath);
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
