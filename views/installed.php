<div class="wrap">
<h1>Activate or Test-drive</h1>

<p class="ppi-intro narrow">
    Your site is completely compatible with P6, and the theme is installed and ready
    to use. You can now choose to make P6 your active theme, or test-drive it while
    keeping another theme active. For more information on test-driving, see below.
</p>

<a href="<?php echo $testDriveUrl; ?>" class="button button-primary button-hero">
    Test-drive
</a>

<a href="<?php echo $activateUrl; ?>" class="button button-primary button-hero">
    Activate
</a>

<br />
<br />
<hr />

<h3>Test-driving P6</h3>

<p class="narrow">
    If you choose to test-drive ProPhoto 6, only logged-in admin users will have P6
    as their active theme.  All other users will see your active theme:
    <b><?php echo ppi_get_non_test_drive_theme_name(); ?></b>. This allows you to work on your
    conversion to P6 and your updated responsive design in privacy, until you're ready
    to go live.  In the meantime, all of your normal web visitors will see your
    existing design and functionality.
</p>
