<h1>ProPhoto 8 Installer</h1>

<hr />

<h2>Install compatibility check:</h2>

<ul id="pp-compatibility">
    <?php if ($phpIsCompatible) { ?>
    <li class="good">
        Your PHP version is compatible.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your PHP version is not compatible.
        <span>
            Please contact your webhost tech support and have them upgrade your
            server to use a faster, safer, and more modern version of PHP.
            Tell them you need to be running at least PHP version 5.6.20, but
            the highest version of PHP 8 that your host offers would be best,
            because each newer version gets faster and more secure.
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
            ProPhoto 8 requires that you are running a very recent version of
            WordPress, which is also the most effective method of keeping your
            website safe and secure from attacks. Before you can use ProPhoto 8,
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

    <?php if ($jsonIsCompatible) { ?>
    <li class="good">
        Your server is compiled with the required <code>json</code> extension.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your server is missing the required <code>json</code> extension.
        <span>
            Please contact your webhost technical support and ask them to make sure
            you are on a build of PHP containing the <code>json</code> extension.
        </span>
    </li>
    <?php } ?>

    <?php if ($domIsCompatible) { ?>
    <li class="good">
        Your server is compiled with the required <code>dom</code> extension.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your server is missing the required <code>dom</code> extension.
        <span>
            Please contact your webhost technical support and ask them to make sure
            you are on a build of PHP containing the <code>dom</code> extension.
        </span>
    </li>
    <?php } ?>

    <?php if ($mysqlCompatible) { ?>
    <li class="good">
        Your server has proper MySQL permissions.
    </li>
    <?php } else { ?>
    <li class="bad">
        Your MySQL permissions are incorrect.
        <span>
            Please contact your webhost technical support and ask them to increase
            the permissions of database user <code><?php echo DB_USER; ?></code>
            for the database <?php echo DB_NAME; ?> to include
            <code>DROP</code> and <code>ALTER</code> privileges.
        </span>
    </li>
    <?php } ?>

    <?php if ($hostingCompatible) { ?>
    <li class="good">
        Your hosting appears to be compatible.
    </li>
    <?php } else { ?>
    <li class="bad">
        WordPress.com business hosting is NOT supported.
        <span>
            As indicated on our <a href="https://help.pro.photo/?page_id=469">support
            article</a> listing compatible web hosts -- WordPress.com is <i>NOT</i> a supported
            webhost. This is due to the fact that WordPress.com completely restricts all
            FTP access, making it impossible for us to troubleshoot and debug any problems
            should they arise. We take support very seriously and do not feel comfortable
            having our users on web-hosting platforms where we cannot give them full support.
            You will need to choose a different web host in order to use ProPhoto.
        </span>
    </li>
    <?php } ?>

    <?php if ($isRunningNextgenPlugin) { ?>
    <li class="bad">
        The NextGen Gallery plugin is not currently compatible with ProPhoto 8.
        <span>
            Please go to "Plugins" > "Installed Plugins" and deactivate the NextGen Gallery
            plugin. Then return to this page to proceed with installing ProPhoto 8.  We hope
            to work around the NextGen incompatabilities at some point in the future, but
            for the time being, these 2 products do not play nicely together.
        </span>
    </li>
    <?php } ?>

</ul>

<?php if (! $isCompatible) { ?>
<p>
    Sorry, your site is not yet ready to use ProPhoto 8. Please read the
    above area(s) highlighted in red, and work with your webhost technical support
    to resolve them. Once you've gotten everything green, this page will change
    and show you more options for continuing with the installation of ProPhoto 8.
</p>

<?php return; } ?>
<p>
    Huzzah! Your site is totally compatible with ProPhoto 8.
</p>

<?php if (! get_option('ppi_hide_recommendations') ) p8i_render_recommendations(); ?>

<?php p8i_render_install_or_test_drive(); ?>
