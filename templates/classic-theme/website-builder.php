<?php
overall_header(__("Your Website"));

$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : (!empty($current_user['fullname']) ? $current_user['fullname'] : __('your business'));
$platformName = !empty($platform_target['name']) ? $platform_target['name'] : __('Website Platform');
$platformType = !empty($platform_target['type']) && $platform_target['type'] === 'ecommerce' ? __('Ecommerce') : __('Services');
$platformBaseUrl = !empty($platform_target['base_url']) ? rtrim($platform_target['base_url'], '/') : '';
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <div class="dashboard-headline">
                <h3><?php _e("My Website") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("My Website") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="dashboard-box margin-bottom-30">
                <div class="content with-padding">
                    <span class="atlas-workflow-eyebrow"><?php _e("Platform detected") ?></span>
                    <h3 class="margin-bottom-10"><?php echo sprintf(__("%s should launch in %s"), _esc($companyName, 0), _esc($platformName, 0)); ?></h3>
                    <p class="margin-bottom-0">
                        <?php
                        echo sprintf(
                            __('Atlas reviewed your Company Intelligence and detected that this business needs the %s flow. We will send you into the dedicated platform with your business name, slug, contact details, description, brand colors, and tone prefilled.'),
                            _esc($platformType, 0)
                        );
                        ?>
                    </p>
                </div>
            </div>

            <?php if (!$profile_ready) { ?>
                <div class="dashboard-box margin-bottom-30">
                    <div class="content with-padding">
                        <span class="atlas-workflow-eyebrow"><?php _e("Company Intelligence required") ?></span>
                        <h4 class="margin-bottom-12"><?php _e("Finish the profile before launching the website platform") ?></h4>
                        <p class="margin-bottom-14"><?php _e("Atlas needs the core business context before it can create your website workspace inside Storemart or BookingDo."); ?></p>
                        <?php if (!empty($profile_status['missing'])) { ?>
                            <div class="small-text margin-bottom-20">
                                <strong><?php _e("Missing fields") ?>:</strong>
                                <?php _esc(implode(', ', $profile_status['missing'])); ?>
                            </div>
                        <?php } ?>
                        <a href="<?php url("COMPANY_INTELLIGENCE") ?>" class="button ripple-effect"><?php _e("Open Company Intelligence") ?></a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="dashboard-box margin-bottom-30">
                    <div class="content with-padding">
                        <div class="row">
                            <div class="col-md-7">
                                <span class="atlas-workflow-eyebrow"><?php _e("Launch workspace") ?></span>
                                <h4 class="margin-bottom-10"><?php echo sprintf(__("%s is ready"), _esc($platformName, 0)); ?></h4>
                                <p class="margin-bottom-18"><?php _e("Use the primary button below to create or reopen this business inside the correct website platform. The first launch will create the workspace. The next launches will log the same Atlas user back into that workspace."); ?></p>
                                <div class="margin-bottom-15">
                                    <a href="<?php _esc($website_launch_url) ?>" class="button ripple-effect big">
                                        <?php echo sprintf(__('Open %s'), _esc($platformName, 0)); ?>
                                    </a>
                                </div>
                                <div class="small-text">
                                    <strong><?php _e("Admin dashboard") ?>:</strong>
                                    <a href="<?php _esc($platformBaseUrl . '/admin/dashboard') ?>" target="_blank"><?php _esc($platformBaseUrl . '/admin/dashboard') ?></a>
                                </div>
                                <div class="small-text margin-top-10">
                                    <strong><?php _e("Public website format") ?>:</strong>
                                    <a href="<?php _esc($website_public_url) ?>" target="_blank"><?php _esc($website_public_url) ?></a>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div style="padding:22px;border:1px solid rgba(54,45,30,0.08);border-radius:18px;background:#fbf8f2;">
                                    <h5 class="margin-bottom-14"><?php _e("What Atlas sends over") ?></h5>
                                    <ul style="margin:0;padding-left:18px;color:#6f6658;line-height:1.8;">
                                        <li><?php _e("Business and owner name") ?></li>
                                        <li><?php _e("Email, phone, and workspace slug") ?></li>
                                        <li><?php _e("Company description and ideal customer") ?></li>
                                        <li><?php _e("Problems solved and unique selling points") ?></li>
                                        <li><?php _e("Brand colors, tone, and references") ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-box margin-bottom-30">
                    <div class="content with-padding">
                        <span class="atlas-workflow-eyebrow"><?php _e("How URLs will work") ?></span>
                        <h4 class="margin-bottom-10"><?php _e("No custom domains for now") ?></h4>
                        <p class="margin-bottom-10"><?php _e("We are keeping all websites on the main Atlas domain for now. That gives us one deployment, one SSL setup, and one place to manage the apps."); ?></p>
                        <p class="margin-bottom-0">
                            <?php
                            echo $platform_target['type'] === 'ecommerce'
                                ? __('Customer-facing stores will live under paths like `/ecom/company-slug`, while the owner dashboard stays under `/ecom/admin/...`.')
                                : __('Customer-facing service sites will live under paths like `/service/company-slug`, while the owner dashboard stays under `/service/admin/...`.');
                            ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if (!empty($website_auto_launch) && !empty($website_launch_url)) { ?>
    <script>
        setTimeout(function () {
            window.location.href = <?php echo json_encode($website_launch_url); ?>;
        }, 300);
    </script>
<?php } ?>
<?php overall_footer(); ?>
