<?php
overall_header(__("Account Setting"));
?>
    <!-- Dashboard Container -->
    <div class="dashboard-container">

<?php include_once TEMPLATE_PATH.'/dashboard_sidebar.php'; ?>

        <!-- Dashboard Content
        ================================================== -->
        <div class="dashboard-content-container" data-simplebar>
            <div class="dashboard-content-inner" >
                <?php print_adsense_code('header_bottom'); ?>
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3><?php _e("Account Setting") ?></h3>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                            <li><?php _e("Account Setting") ?></li>
                        </ul>
                    </nav>
                </div>

                <!-- Row -->
                <div class="row">
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">
                        <div class="dashboard-box margin-top-0">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-settings"></i> <?php _e("Account Setting") ?></h3>
                            </div>
                            <div class="content with-padding">
                                <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e('Avatar'); ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="images/*" id="avatar"
                                                       name="avatar"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="avatar"><?php _e('Upload Avatar') ?></label>
                                                <span class="uploadButton-file-name"><?php _e('Use 150x150px for better use') ?></span>
                                            </div>
                                            <?php if(!empty($avatar_error)){ _esc($avatar_error) ; }?>
                                        </div>
                                        </div>
                                        <div class="col-xl-6 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Username") ?> *</h5>
                                                <div class="input-with-icon-left">
                                                    <i class="la la-user"></i>
                                                    <input type="text" class="with-border" id="username" name="username" value="<?php _esc($username)?>" onBlur="checkAvailabilityUsername()">
                                                </div>
                                                <span id="user-availability-status"><?php if($username_error != ""){ _esc($username_error) ; }?></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Email Address") ?> *</h5>
                                                <div class="input-with-icon-left">
                                                    <i class="la la-envelope"></i>
                                                    <input type="text" class="with-border" id="email" name="email" value="<?php _esc($email_field)?>" onBlur="checkAvailabilityEmail()">
                                                </div>
                                                <span id="email-availability-status"><?php if($email_error != ""){ _esc($email_error) ; }?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <h5><?php _e("New Password") ?></h5>
                                                <input type="password" id="password" name="password" class="with-border" onkeyup="checkAvailabilityPassword()">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Confirm Password") ?></h5>
                                                <input type="password" id="re_password" name="re_password" class="with-border" onkeyup="checkRePassword()">
                                            </div>
                                        </div>
                                    </div>
                                    <span id="password-availability-status"><?php if($password_error != ""){ _esc($password_error) ; }?></span>
                                    <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save Changes") ?></button>
                                </form>
                            </div>
                        </div>
                        <div class="dashboard-box">
                            <div class="headline">
                                <h3><i class="icon-feather-briefcase"></i> <?php _e("Company Profile") ?></h3>
                            </div>
                            <div class="content with-padding">
                                <div class="notification notice"><?php _e("This profile powers every AI agent and the social media generator.") ?></div>
                                <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Founder Photo") ?></h5>
                                                <div class="uploadButton">
                                                    <input class="uploadButton-input" type="file" accept="images/*" id="founder_photo" name="founder_photo"/>
                                                    <label class="uploadButton-button ripple-effect" for="founder_photo"><?php _e('Upload Founder Photo') ?></label>
                                                    <span class="uploadButton-file-name"><?php _e('Used in generated social assets and agent context.') ?></span>
                                                </div>
                                                <?php if (!empty($founder_photo)) { ?>
                                                    <img src="<?php echo _esc($config['site_url'], 0) . 'storage/company/' . $founder_photo; ?>" alt="" style="max-width: 120px; border-radius: 12px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Company Logo") ?></h5>
                                                <div class="uploadButton">
                                                    <input class="uploadButton-input" type="file" accept="images/*" id="company_logo" name="company_logo"/>
                                                    <label class="uploadButton-button ripple-effect" for="company_logo"><?php _e('Upload Company Logo') ?></label>
                                                    <span class="uploadButton-file-name"><?php _e('Used as branding on generated posts.') ?></span>
                                                </div>
                                                <?php if (!empty($company_logo)) { ?>
                                                    <img src="<?php echo _esc($config['site_url'], 0) . 'storage/company/' . $company_logo; ?>" alt="" style="max-width: 120px; border-radius: 12px; background: #fff; padding: 8px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Founder Name") ?></h5>
                                                <input type="text" name="founder_name" class="with-border" value="<?php _esc($founder_name) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Founder Title") ?></h5>
                                                <input type="text" name="founder_title" class="with-border" value="<?php _esc($founder_title) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Company Name") ?></h5>
                                                <input type="text" name="company_name" class="with-border" value="<?php _esc($company_name) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Company Website") ?></h5>
                                                <input type="text" name="company_website" class="with-border" value="<?php _esc($company_website) ?>" placeholder="https://example.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Industry") ?></h5>
                                                <input type="text" name="company_industry" class="with-border" value="<?php _esc($company_industry) ?>" placeholder="<?php _e('SaaS, eCommerce, fintech, agency...') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Instagram Handle") ?></h5>
                                                <input type="text" name="instagram_handle" class="with-border" value="<?php _esc($instagram_handle) ?>" placeholder="@yourbrand">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Company Description") ?></h5>
                                        <textarea name="company_description" class="with-border" rows="4"><?php _esc($company_description) ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Target Audience") ?></h5>
                                                <textarea name="target_audience" class="with-border" rows="4"><?php _esc($target_audience) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Brand Voice") ?></h5>
                                                <textarea name="brand_voice" class="with-border" rows="4"><?php _esc($brand_voice) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Content Goals") ?></h5>
                                                <textarea name="content_goals" class="with-border" rows="4"><?php _esc($content_goals) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Key Products or Services") ?></h5>
                                                <textarea name="key_products" class="with-border" rows="4"><?php _esc($key_products) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Differentiators") ?></h5>
                                        <textarea name="differentiators" class="with-border" rows="4"><?php _esc($differentiators) ?></textarea>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Competitor Websites or Instagram URLs") ?></h5>
                                        <textarea name="competitors" class="with-border" rows="4" placeholder="https://competitor.com&#10;https://instagram.com/competitor"><?php _esc($competitors) ?></textarea>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Competitor Notes") ?></h5>
                                        <textarea name="competitor_notes" class="with-border" rows="4"><?php _esc($competitor_notes) ?></textarea>
                                    </div>
                                    <?php if(!empty($social_error)){ _esc($social_error); } ?>
                                    <button type="submit" name="social-submit" class="button ripple-effect"><?php _e("Save Company Profile") ?></button>
                                </form>
                            </div>
                        </div>
                        <?php if (get_option('enable_tax_billing', 1)) { ?>
                        <div class="dashboard-box">
                            <div class="headline">
                                <h3><i class="icon-material-outline-description"></i> <?php _e("Billing Details") ?></h3>
                            </div>
                            <div class="content">
                                <div class="content with-padding">
                                    <div class="notification notice"><?php _e("These details will be used in invoice and payments.") ?></div>
                                    <?php if($billing_error == "1"){ ?>
                                        <div class="notification error"><?php _e("All fields are required.") ?></div>
                                    <?php } ?>
                                    <form method="post" accept-charset="UTF-8">
                                        <div class="submit-field">
                                            <h5><?php _e("Type") ?></h5>
                                            <select name="billing_details_type" id="billing_details_type"  class="with-border selectpicker" required>
                                                <option value="personal" <?php if($billing_details_type == "personal"){ echo 'selected';} ?> ><?php _e("Personal") ?></option>
                                                <option value="business" <?php if($billing_details_type == "business"){ echo 'selected';} ?> ><?php _e("Business") ?></option>
                                            </select>
                                        </div>
                                        <div class="submit-field billing-tax-id">
                                            <h5>
                                               <?php
                                               if($config['invoice_admin_tax_type'] != ""){
                                                   _esc($config['invoice_admin_tax_type']);
                                               }else{
                                                   _e("Tax ID");
                                               }
                                               ?>
                                            </h5>
                                            <input type="text" id="billing_tax_id" name="billing_tax_id" class="with-border" value="<?php _esc($billing_tax_id)?>">
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Name") ?> *</h5>
                                            <input type="text" id="billing_name" name="billing_name" class="with-border" value="<?php _esc($billing_name)?>" required>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Address") ?> *</h5>
                                            <input type="text" id="billing_address" name="billing_address" class="with-border" value="<?php _esc($billing_address)?>" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("City") ?> *</h5>
                                                    <input type="text" id="billing_city" name="billing_city" class="with-border" value="<?php _esc($billing_city)?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="submit-field">
                                                    <h5><?php _e("State") ?> *</h5>
                                                    <input type="text" id="billing_state" name="billing_state" class="with-border" value="<?php _esc($billing_state)?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="submit-field">
                                                    <h5><?php _e("Zip code") ?> *</h5>
                                                    <input type="text" id="billing_zipcode" name="billing_zipcode" class="with-border" value="<?php _esc($billing_zipcode)?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Country") ?> *</h5>
                                            <select name="billing_country" id="billing_country" class="with-border selectpicker" data-live-search="true" required>
                                                <?php
                                                foreach($countries as $country){
                                                    ?>
                                                    <option value="<?php _esc($country['code']) ?>" <?php _esc($country['selected']) ?>><?php _esc($country['asciiname']) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="billing-submit" class="button ripple-effect"><?php _e("Save Changes") ?></button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="dashboard-box">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-trash-2"></i> <?php _e("Delete Account") ?></h3>
                            </div>
                            <div class="content with-padding">
                                <form method="post" accept-charset="UTF-8" onsubmit="return confirm('<?php echo escape(__('By deleting the account, all of your stored data will be deleted and you can not undo this action. Are you sure?')); ?>')">
                                    <div class="notification error"><?php _e('By deleting the account, all of your stored data will be deleted and you can not undo this action.'); ?></div>
                                    <div class="submit-field">
                                        <h5><?php _e("Current Password") ?></h5>
                                        <input type="password" id="password" name="password" class="with-border" required>
                                        <?php if(!empty($delete_account_error)){ _esc($delete_account_error) ; }?>
                                    </div>
                                    <button type="submit" name="delete-account" class="button red ripple-effect"><?php _e("Delete Account") ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row / End -->
                <?php print_adsense_code('footer_top'); ?>
                <!-- Footer -->
                <div class="dashboard-footer-spacer"></div>
                <div class="small-footer margin-top-15">
                    <div class="footer-copyright">
                        <?php _esc($config['copyright_text']); ?>
                    </div>
                    <ul class="footer-social-links">
                        <?php
                        if($config['facebook_link'] != "")
                            echo '<li><a href="'._esc($config['facebook_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                        if($config['twitter_link'] != "")
                            echo '<li><a href="'._esc($config['twitter_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                        if($config['instagram_link'] != "")
                            echo '<li><a href="'._esc($config['instagram_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                        if($config['linkedin_link'] != "")
                            echo '<li><a href="'._esc($config['linkedin_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                        if($config['pinterest_link'] != "")
                            echo '<li><a href="'._esc($config['pinterest_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                        if($config['youtube_link'] != "")
                            echo '<li><a href="'._esc($config['youtube_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                        ?>
                    </ul>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
<?php ob_start() ?>
    <script>
        var error = "";
        function checkAvailabilityUsername() {
            jQuery.ajax({
                url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
                data: 'username=' + $("#username").val(),
                type: "POST",
                success: function (data) {
                    if (data != "success") {
                        error = 1;
                        $("#user-availability-status").html(data);
                    }
                    else {
                        error = 0;
                        $("#user-availability-status").html("");
                    }
                },
                error: function () {
                }
            });
        }
        function checkAvailabilityEmail() {
            jQuery.ajax({
                url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
                data: 'email=' + $("#email").val(),
                type: "POST",
                success: function (data) {
                    if (data != "success") {
                        error = 1;
                        $("#email-availability-status").html(data);
                    }
                    else {
                        error = 0;
                        $("#email-availability-status").html("");
                    }
                    $("#loaderIcon").hide();
                },
                error: function () {
                }
            });
        }
        function checkAvailabilityPassword() {
            var length = $('#password').val().length;
            if (length != 0) {
                var PASSLENG = "<?php _e('Password must be between 4 and 20 characters long') ?>";
                if (length < 5 || length > 21) {
                    $("#password-availability-status").html("<span class='status-not-available'>" + PASSLENG + "</span>");
                }
                else {
                    $("#password-availability-status").html("");
                }
            }
        }
        function checkRePassword(){
            if($('#password').val() != $('#re_password').val()){
                var PASS = "<?php _e('The passwords you entered did not match') ?>";
                $("#password-availability-status").html("<span class='status-not-available'>" + PASS + "</span>");
            }else{
                $("#password-availability-status").html("");
            }
        }
        jQuery(window).on('load',function () {
            jQuery('#password').val("");
        });

        $('#billing_details_type').on('change', function () {
            if($(this).val() == 'business')
                $('.billing-tax-id').slideDown();
            else
                $('.billing-tax-id').slideUp();
        }).trigger('change');
    </script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
