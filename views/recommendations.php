<div id="recommendations-wrap">

    <hr />

    <h2>Recommendations:</h2>

    <p>
        Your site is capable of running ProPhoto 7, but we would recommend addressing
        the below items for the best possible experience.<br /><i id="dismiss-recommendations">(You can also <a>click here</a> to dismiss these recommendations.)</i>
    </p>

    <ul id="pp-recommendations">
        <?php if ($phpOutdated) { ?>
        <li>
            Your PHP version is out of date
            <span>
                Your PHP version:
                <code><?php echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION; ?></code>,
                is technically compatible, but is insecure and outdated. We recommend
                that you call your webhost and ask them to upgrade your PHP to at
                least <code>7.0</code>.
                Version <code>7.0</code> and higher run <em>nearly twice as fast</em>
                as older versions, and are more secure, so it's definitely worth a quick
                call to get updated.
            </span>
        </li>
        <?php } ?>

        <?php if ($memoryLimitLow) { ?>
        <li>
            Your PHP memory limit is low
            <span>
                ProPhoto is a really powerful theme, so it uses a good amount of PHP memory
                (like RAM on your computer), especially when you're designing and building your
                site in the admin area. Your current memory limit is <?php echo $memoryLimit; ?>
                megabytes. We recommend a limit of at least <code>256M</code>, or, ideally
                <code>512M</code>. Usually a five-minute phone call to your webhost tech support
                is enough to get your PHP memory limit raised up to a better number.
            </span>
        </li>
        <?php } ?>

        <?php if ($missingImagick) { ?>
        <li>
            You are missing the Imagick image library
            <span>
                To make your site responsive and fast, ProPhoto needs to be able to resize and
                watermark your upladed images. There are two main image libraries that allow
                ProPhoto to do this: <code>GD</code>, and <code>Imagick</code> (sometimes called
                "Image Magick"). You have <code>GD</code> installed, but not <code>Imagick</code>.
                <code>Imagick</code> is the newer, faster, and more powerful of the two.  It does
                a better job of preserving image quality and color than <code>GD</code>. Most (but
                not all) webhosts can enable <code>Imagick</code> for you, so it's probably worth
                a phone call to your webhost tech support, to see if they can enable this improved
                image handling library.
            </span>
        </li>
        <?php } ?>
    </ul>

</div>
