<?php
overall_header(__("Social Media Automation"));
$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : __('your company');
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
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

            <div class="atlas-workflow-hero margin-bottom-30">
                <span class="atlas-workflow-eyebrow"><?php _e("Atlas studio") ?></span>
                <h2><?php _e("Choose the creative workflow") ?></h2>
                <p><?php echo sprintf(__('Choose the workflow that matches %s best. Atlas now behaves like a studio: brand context first, strategic direction second, and assets third.'), _esc($companyName, 0)); ?></p>
            </div>

            <div class="row atlas-automation-card-grid atlas-automation-card-grid-polished">
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_IMAGES_CAMPAIGN") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <div class="atlas-automation-card-top">
                                <span class="atlas-automation-icon"><i class="icon-feather-target"></i></span>
                                <span class="atlas-automation-pill atlas-automation-pill-dark"><?php _e("Most used") ?></span>
                            </div>
                            <h4><?php _e("Open campaign studio") ?></h4>
                            <p><?php _e("Generate 9 strategic social posts for a specific campaign goal, funnel stage, and focus area. Atlas turns your brand context into a conversion-minded content sequence instead of isolated posts.") ?></p>
                            <ul class="atlas-automation-feature-list">
                                <li><?php _e("Starts with your campaign goal and funnel stage") ?></li>
                                <li><?php _e("Builds 9 posts that each play a role in the sequence") ?></li>
                                <li><?php _e("Best when you want strategy, consistency, and momentum") ?></li>
                            </ul>
                            <span class="atlas-automation-link"><?php _e("Open campaign studio") ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_IMAGES_GRID") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <div class="atlas-automation-card-top">
                                <span class="atlas-automation-icon"><i class="icon-feather-grid"></i></span>
                                <span class="atlas-automation-pill"><?php _e("Visual first") ?></span>
                            </div>
                            <h4><?php _e("Build a visual grid") ?></h4>
                            <p><?php _e("Generate 9 posts designed as a coordinated Instagram grid that should be posted in sequence. Atlas builds a visually connected layout and previews it like a brand system, not just a pile of tiles.") ?></p>
                            <ul class="atlas-automation-feature-list">
                                <li><?php _e("Uses your visual direction and company profile") ?></li>
                                <li><?php _e("Designs 9 tiles that work together on the profile view") ?></li>
                                <li><?php _e("Lets you preview the full grid before export") ?></li>
                            </ul>
                            <span class="atlas-automation-link"><?php _e("Build a grid") ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 margin-bottom-30">
                    <a href="<?php url("AI_TEMPLATES") ?>" class="atlas-automation-card dashboard-box">
                        <div class="content with-padding">
                            <div class="atlas-automation-card-top">
                                <span class="atlas-automation-icon"><i class="icon-feather-edit-3"></i></span>
                                <span class="atlas-automation-pill atlas-automation-pill-success"><?php _e("Quick") ?></span>
                            </div>
                            <h4><?php _e("Create one-off assets") ?></h4>
                            <p><?php _e("Jump into your daily templates to create focused content assets such as Facebook posts, paid ads, Amazon product descriptions, blog articles, website copy, and other on-demand outputs.") ?></p>
                            <ul class="atlas-automation-feature-list">
                                <li><?php _e("Generate single assets fast without a campaign setup") ?></li>
                                <li><?php _e("Ideal for captions, ads, blogs, product copy, and more") ?></li>
                                <li><?php _e("Best when you need one output right now") ?></li>
                            </ul>
                            <span class="atlas-automation-link"><?php _e("Open daily templates") ?></span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="atlas-automation-footnote margin-bottom-10">
                <strong><?php _e("Campaign vs Grid") ?>:</strong>
                <?php _e("Both generate 9 posts, but for different goals. A campaign follows a strategic arc from hook to value to CTA. A grid is designed for visual harmony so your profile looks intentional and on-brand.") ?>
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
