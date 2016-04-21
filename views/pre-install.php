<h1>ProPhoto 6 Installer</h1>

<hr />

<h2>Install compatibility check:</h2>

<ul id="pp-compatibility">
    <?php if ($phpIsCompatible) { ?>
    <li class="good">
        Your PHP version is compatible.
    </li>
    <?php } else if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION === 7) { ?>
    <li class="bad">
        Your PHP version is not compatible.
        <span>
            ProPhoto 6 is <strong>not yet</strong> compatible with <code>PHP 7</code>.
            It will be very soon, but for now, you'll need to downgrade to PHP 5.6 or 5.5.
        </span>
    </li>
    <?php } else { ?>
    <li class="bad">
        Your PHP version is not compatible.
        <span>
            Please contact your webhost tech support and have them upgrade your
            server to use a faster, safer, and more modern version of PHP.
            Tell them you need to be running at least on PHP version 5.3.6, but 5.4,
            5.5, and 5.6 are even better, because each newer version gets faster
            and more secure.
        </span>
    </li>
    <?php } ?>

    <?php if ($wpIsCompatible) { ?>
    <li class="good">
        Your WordPress version is compatible.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your WordPress version is not compatible.
        <span>
            ProPhoto 6 requires that you are running a very recent version of
            WordPress, which is also the most effective method of keeping your
            website safe and secure from attacks. Before you can use ProPhoto 6,
            you'll need to <a href="<?php echo admin_url('update-core.php'); ?>">
            update your WordPress version</a>.
        </span>
    </li>
    <?php } ?>


    <?php if ($gdIsCompatible) { ?>
    <li class="good">
        Your server supports the <code>GD</code> image library.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your server does not support <code>GD</code> image library.
        <span>
            Please contact your web-host technical support and ask them to
            make sure you are on a build of PHP that includes this required
            library for image manipulation.
        </span>
    </li>
    <?php } ?>

    <?php if ($jsonIsComptible) { ?>
    <li class="good">
        Your server is compiled with the required <code>json</code> extension.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your server is missing the required <code>json</code> extension.
        <span>
            Please contact your webhost technical support and ask them to make sure
            you are on a build of PHP containing the <cod>json</cod> extension.
        </span>
    </li>
    <?php } ?>
</ul>

<?php if (! $isCompatible) { ?>
<p>
    Sorry, your site is not yet ready to use ProPhoto 6. Please read the
    above area(s) highlighted in red, and work with your webhost technical support
    to resolve them. Once you've gotten everything green, this page will change
    and show you more options for continuing with the installation of ProPhoto 6.
</p>

<?php return; } ?>
<p>
    Huzzah! You're site is totally compatible with ProPhoto 6.
</p>

<?php if (! get_option('ppi_hide_recommendations') ) ppi_render_recommendations(); ?>

<?php ppi_render_install_or_test_drive(); ?>
