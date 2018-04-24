<div class="wrap">
<h1>Activate or Test-drive</h1>

<p class="ppi-intro narrow">
    Your site is completely compatible with P7, and the theme is installed and ready
    to use. You can now choose to make P7 your active theme, or test-drive it while
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

<h3>Test-driving P7</h3>

<p class="narrow">
    If you choose to test-drive ProPhoto 7, only logged-in admin users will have P7
    as their active theme.  All other users will see your active theme:
    <b><?php echo p7i_get_non_test_drive_theme_name(); ?></b>. This allows you to work on your
    conversion to P7 and your updated responsive design in privacy, until you're ready
    to go live.  In the meantime, all of your normal web visitors will see your
    existing design and functionality.
</p>
