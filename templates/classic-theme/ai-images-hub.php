<?php
overall_header(__("Social Media Automation"));
$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : __('your company');
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3><?php _e("Social Media Automation") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Social Media Automation") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="notification notice margin-bottom-30">
                <?php echo sprintf(__('Build repeatable social output for %s with campaign generation, Instagram grid planning, and fast content creation tools.'), _esc($companyName, 0)); ?>
            </div>

            <div class="row atlas-automation-card-grid">
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_IMAGES_CAMPAIGN") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-automation-icon"><i class="icon-feather-target"></i></span>
                            <h4><?php _e("Create a campaign") ?></h4>
                            <p><?php _e("Generate 9 strategic social posts for a specific campaign goal, funnel stage, and focus area. Atlas turns your company context into a conversion-minded content funnel you can publish across your social channels.") ?></p>
                            <span class="atlas-automation-link"><?php _e("Open campaign generator") ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_IMAGES_GRID") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-automation-icon"><i class="icon-feather-grid"></i></span>
                            <h4><?php _e("Create an Instagram grid") ?></h4>
                            <p><?php _e("Generate 9 posts designed as a coordinated Instagram grid that should be posted in exact sequence. Atlas builds a visually connected layout, previews it inside a mobile-style profile view, and gives you the final tiles ready to upload.") ?></p>
                            <span class="atlas-automation-link"><?php _e("Build a grid") ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_TEMPLATES") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-automation-icon"><i class="icon-feather-edit-3"></i></span>
                            <h4><?php _e("Create content") ?></h4>
                            <p><?php _e("Jump into your daily templates to create focused content assets such as Facebook posts, paid ads, Amazon product descriptions, blog articles, website copy, and other on-demand marketing outputs.") ?></p>
                            <span class="atlas-automation-link"><?php _e("Open daily templates") ?></span>
                        </div>
                    </a>
                </div>
            </div>

            <?php print_adsense_code('footer_top'); ?>
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright"><?php _esc($config['copyright_text']); ?></div>
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
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
