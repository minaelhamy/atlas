<?php
overall_header(__("Contact Us"));
?>
<?php print_adsense_code('header_bottom'); ?>
    <div class="container margin-bottom-50 atlas-public-shell">
        <div class="atlas-public-hero margin-bottom-40">
            <span class="atlas-public-eyebrow"><?php _e("Support") ?></span>
            <h1><?php _e("Contact the Atlas team") ?></h1>
            <p><?php _e("If you need help with your workspace, campaigns, billing, or setup, send us a note and we will get you to the right team quickly.") ?></p>
        </div>
        <?php if (!empty($latitude)) { ?>
            <div class="map margin-bottom-50" id="singleListingMap" data-latitude="<?php _esc($latitude) ?>"
                 data-longitude="<?php _esc($longitude) ?>" data-map-icon="fa fa-marker"></div>
        <?php } ?>
        <div class="business-info">
            <div class="row">
                <div class="col-sm-8">
                    <div class="contactUs">
                        <h2 class="margin-bottom-30"><?php _e("Contact Us") ?></h2>
                        <form id="contact-form" class="contact-form" name="contact-form" method="post" action="#">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" class="with-border" required="required"
                                               placeholder="<?php _e("Your Name") ?>" name="name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" class="with-border" required="required"
                                               placeholder="<?php _e("Your E-Mail") ?>" name="email">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="with-border" required="required"
                                               placeholder="<?php _e("Subject") ?>" name="subject">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" required="required" class="with-border"
                                                  rows="7" placeholder="<?php _e("Message") ?>"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <?php
                                        if ($config['recaptcha_mode'] == '1') {
                                            echo '<div class="g-recaptcha" data-sitekey="' . _esc($config['recaptcha_public_key'], false) . '"></div>';
                                        }
                                        if ($recaptcha_error != '') {
                                            echo '<span class="status-not-available">' . $recaptcha_error . '</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="Submit" class="button"><?php _e("Send Message") ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Enquiry Form-->
                <!-- contact-detail -->
                <div class="col-sm-4">
                    <div class="dashboard-box margin-top-0">
                        <div class="headline">
                            <h3><?php _e("Get In Touch") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php _e("Please get in touch and our expert support team will answer all your questions.") ?>
                        </div>
                    </div>
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><?php _e("Contact Information") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <ul>
                                <?php
                                if ($address != '') {
                                    echo '<li class="job-property margin-bottom-10"><i class="la la-map-marker"></i>
                                        ' . _esc($address, false) . '</li>';
                                }
                                if ($phone != '') {
                                    echo '<li class="job-property margin-bottom-10"><i class="la la-phone"></i>
                                        <a href="tel:' . _esc($phone, false) . '" rel="nofollow">' . _esc($phone, false) . '</a></li>';
                                }
                                if ($email != '') {
                                    echo '<li class="job-property margin-bottom-10"><i class="la la-envelope"></i>
                                        <a href="mailto:' . _esc($email, false) . '" rel="nofollow">' . _esc($email, false) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- contact-detail -->
            </div>
            <!-- row -->
        </div>
    </div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
<style>
    .atlas-public-shell {
        padding-top: 28px;
    }

    .atlas-public-hero {
        padding: 38px 40px;
        border-radius: 28px;
        background:
            radial-gradient(circle at top left, rgba(255,255,255,0.74), transparent 40%),
            linear-gradient(145deg, #f6f0e8 0%, #ece2d5 100%);
        box-shadow: 0 24px 60px rgba(67, 45, 19, 0.10);
    }

    .atlas-public-eyebrow {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.7);
        color: #6f655b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .atlas-public-hero h1 {
        font-size: clamp(34px, 5vw, 54px);
        line-height: 1.03;
        letter-spacing: -0.04em;
        margin-bottom: 14px;
    }

    .atlas-public-hero p {
        max-width: 760px;
        margin: 0;
        color: #665e56;
        font-size: 16px;
        line-height: 1.75;
    }
</style>

<?php
if (!empty($latitude)) {
    if ($config['map_type'] == "google") {
        ?>
        <link href="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/google/map-marker.css"
              type="text/css" rel="stylesheet">
        <script type='text/javascript'
                src='//maps.google.com/maps/api/js?key=<?php _esc($config['gmap_api_key']) ?>&#038;libraries=places%2Cgeometry&#038;ver=2.2.1'></script>
        <script type='text/javascript'
                src='<?php _esc($config['site_url']); ?>includes/assets/plugins/map/google/richmarker-compiled.js'></script>
        <script type='text/javascript'
                src='<?php _esc($config['site_url']); ?>includes/assets/plugins/map/google/markerclusterer_packed.js'></script>
        <script type='text/javascript'
                src='<?php _esc($config['site_url']); ?>includes/assets/plugins/map/google/gmapAdBox.js'></script>
        <script type='text/javascript'
                src='<?php _esc($config['site_url']); ?>includes/assets/plugins/map/google/maps.js'></script>
        <script>
            var element = "singleListingMap";
            var getCity = false;
            var _latitude = '<?php _esc($latitude)?>';
            var _longitude = '<?php _esc($longitude)?>';
            var color = '<?php _esc($map_color)?>';
            var site_url = '<?php _esc($config['site_url']);?>';
            var path = site_url;
            simpleMap(_latitude, _longitude, element);
        </script>
        <?php
    } else {
        ?>
        <script>
            var openstreet_access_token = '<?php _esc($config['openstreet_access_token'])?>';
        </script>
        <link rel="stylesheet"
              href="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/css/style.css">
        <!-- Leaflet // Docs: https://leafletjs.com/ -->
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet.min.js"></script>

        <!-- Leaflet Maps Scripts (locations are stored in leaflet-quick.js) -->
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet-markercluster.min.js"></script>
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet-gesture-handling.min.js"></script>
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet-quick.js"></script>

        <!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet-autocomplete.js"></script>
        <script src="<?php _esc($config['site_url']); ?>includes/assets/plugins/map/openstreet/leaflet-control-geocoder.js"></script>

        <?php
    }
}
overall_footer();
?>
