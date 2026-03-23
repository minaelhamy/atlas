<?php
overall_header(__("Social Media Generator"));
$profileReady = !empty($social_profile['company_name']) && !empty($social_profile['company_description']);
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Social Media Generator") ?>
                    <div class="word-used-wrapper margin-left-10">
                        <i class="icon-feather-bar-chart-2"></i>
                        <?php echo '<i id="quick-images-left">' .
                            _esc(number_format((float)$total_images_used), 0) . '</i> / ' .
                            ($images_limit == -1
                                ? __('Unlimited')
                                : _esc(number_format($images_limit + get_user_option($_SESSION['user']['id'], 'total_images_available', 0)), 0)); ?>
                        <strong><?php _e('Posts Used'); ?></strong>
                    </div>
                </h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Social Media Generator") ?></li>
                    </ul>
                </nav>
            </div>

            <?php if (!$profileReady) { ?>
                <div class="notification warning">
                    <?php _e("Complete your company profile in Account Settings before generating posts."); ?>
                    <a href="<?php url("ACCOUNT_SETTING") ?>"><strong><?php _e("Open Account Settings") ?></strong></a>
                </div>
            <?php } else { ?>
                <div class="notification notice">
                    <?php _e("Each run generates 9 pieces: 3 posts, 3 carousels, and 3 reels. Covers are rendered automatically using your company profile and admin-managed assets."); ?>
                </div>
            <?php } ?>

            <form id="ai_images" name="ai_images" method="post" action="#">
                <div class="dashboard-box margin-top-0">
                    <div class="headline">
                        <h3><i class="icon-feather-instagram"></i> <?php _e("Plan A Campaign") ?></h3>
                    </div>
                    <div class="content with-padding">
                        <div class="submit-field">
                            <h5><?php _e("Campaign Brief") ?></h5>
                            <textarea name="description" class="with-border" rows="4" placeholder="<?php _e('Example: Launch a 4-week campaign about our AI outbound product for B2B SaaS founders. Focus on trust, speed, and practical use cases.') ?>" <?php echo $profileReady ? '' : 'disabled'; ?> required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Focus Area") ?></h5>
                                    <input type="text" name="focus_area" class="with-border" placeholder="<?php _e('Lead generation, education, founder story, product launch...') ?>" <?php echo $profileReady ? '' : 'disabled'; ?>>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Campaign Goal") ?></h5>
                                    <input type="text" name="campaign_goal" class="with-border" placeholder="<?php _e('Drive DMs, book calls, get signups, warm audience...') ?>" <?php echo $profileReady ? '' : 'disabled'; ?>>
                                </div>
                            </div>
                        </div>
                        <small class="form-error"></small>
                        <button type="submit" name="submit" class="button ripple-effect" <?php echo $profileReady ? '' : 'disabled'; ?>>
                            <?php _e("Generate 9 Posts") ?> <i class="icon-feather-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="dashboard-box">
                <div class="headline">
                    <h3><i class="icon-feather-grid"></i> <?php _e("Generated Social Content") ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="row" id="generated_images_wrapper">
                        <?php foreach ($social_posts as $post) {
                            $meta = !empty($post['metadata']) && is_array($post['metadata']) ? $post['metadata'] : [];
                            $hashtags = !empty($meta['hashtags']) && is_array($meta['hashtags']) ? implode(' ', $meta['hashtags']) : '';
                            $design = !empty($meta['design']) && is_array($meta['design']) ? $meta['design'] : [];
                            $videoUrl = !empty($meta['rendered_video']) ? _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video'] : '';
                            $previewUrl = _esc($config['site_url'], 0) . 'storage/social_posts/' . $post['preview_image'];
                            ?>
                            <div class="col-xl-4 col-md-6 margin-bottom-30">
                                <div class="dashboard-box social-post-card margin-top-0">
                                    <div class="content">
                                        <div class="social-post-preview">
                                            <?php if (!empty($videoUrl) && $post['post_type'] === 'reel') { ?>
                                                <video src="<?php echo $videoUrl; ?>" autoplay muted loop playsinline controls preload="metadata"></video>
                                            <?php } else { ?>
                                                <img src="<?php echo $previewUrl; ?>" alt="<?php _esc($post['title']) ?>">
                                            <?php } ?>
                                        </div>
                                        <div class="social-post-body with-padding">
                                            <span class="dashboard-status-button yellow"><?php _esc(ucfirst($post['post_type'])) ?></span>
                                            <h4 class="margin-top-15"><?php _esc($post['title']) ?></h4>
                                            <p class="margin-bottom-10"><strong><?php _e('Overlay') ?>:</strong> <?php _esc($post['overlay_text']) ?></p>
                                            <p class="margin-bottom-10"><strong><?php _e('Caption') ?>:</strong> <?php _esc($post['caption']) ?></p>
                                            <?php if (!empty($meta['cta'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('CTA') ?>:</strong> <?php _esc($meta['cta']) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($hashtags)) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Hashtags') ?>:</strong> <?php _esc($hashtags) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['slides'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Carousel Flow') ?>:</strong> <?php _esc(implode(' | ', $meta['slides'])) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['reel_script'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Reel Script') ?>:</strong> <?php _esc(implode(' | ', $meta['reel_script'])) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['asset']['title'])) { ?>
                                                <p class="margin-bottom-0"><strong><?php _e('Asset') ?>:</strong> <?php _esc($meta['asset']['title']) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($design['headline_font_key']) || !empty($design['background_tone'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Design') ?>:</strong>
                                                    <?php _esc(!empty($design['headline_font_key']) ? $design['headline_font_key'] : ''); ?>
                                                    <?php if (!empty($design['body_font_key'])) { ?> / <?php _esc($design['body_font_key']) ?><?php } ?>
                                                    <?php if (!empty($design['headline_size'])) { ?>, <?php _esc($design['headline_size']) ?>px<?php } ?>
                                                    <?php if (!empty($design['background_tone'])) { ?>, <?php _esc($design['background_tone']) ?><?php } ?>
                                                </p>
                                            <?php } ?>
                                            <?php
                                            $captionExport = $post['caption'];
                                            if (!empty($hashtags)) {
                                                $captionExport .= "\n\n" . $hashtags;
                                            }
                                            $downloadUrl = (!empty($videoUrl) && $post['post_type'] === 'reel') ? $videoUrl : $previewUrl;
                                            $downloadLabel = (!empty($videoUrl) && $post['post_type'] === 'reel') ? __('Download Reel') : __('Download Post');
                                            ?>
                                            <div class="d-flex margin-top-15 flex-wrap">
                                                <a href="<?php echo $downloadUrl; ?>" class="button ripple-effect btn-sm margin-right-5" download><?php _esc($downloadLabel) ?></a>
                                                <a href="#" class="button ripple-effect btn-sm margin-right-5 download-caption" data-title="<?php _esc($post['title']) ?>" data-caption="<?php _esc($captionExport) ?>"><?php _e('Download Caption') ?></a>
                                                <?php if (!empty($meta['rendered_video'])) { ?>
                                                    <a href="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video']; ?>" class="button ripple-effect btn-sm margin-right-5" target="_blank"><?php _e('Open Reel Video') ?></a>
                                                    <a href="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video']; ?>" class="button ripple-effect btn-sm margin-right-5" download><?php _e('Download Reel Video') ?></a>
                                                    <a href="<?php echo $previewUrl; ?>" class="button ripple-effect btn-sm margin-right-5" download><?php _e('Download Cover') ?></a>
                                                <?php } ?>
                                                <a href="#" class="button red ripple-effect btn-sm quick-delete" data-id="<?php _esc($post['id']) ?>" data-action="delete_image"><?php _e('Delete') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php print_adsense_code('footer_top'); ?>
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright"><?php _esc($config['copyright_text']); ?></div>
                <ul class="footer-social-links">
                    <?php
                    if ($config['facebook_link'] != "")
                        echo '<li><a href="' . _esc($config['facebook_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if ($config['twitter_link'] != "")
                        echo '<li><a href="' . _esc($config['twitter_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if ($config['instagram_link'] != "")
                        echo '<li><a href="' . _esc($config['instagram_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if ($config['linkedin_link'] != "")
                        echo '<li><a href="' . _esc($config['linkedin_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                    if ($config['pinterest_link'] != "")
                        echo '<li><a href="' . _esc($config['pinterest_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                    if ($config['youtube_link'] != "")
                        echo '<li><a href="' . _esc($config['youtube_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php ob_start() ?>
<style>
    .social-post-card .social-post-preview img {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
    }
    .social-post-card .social-post-preview video {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
        background: #000;
    }
    .social-post-card .social-post-body {
        background: #fff;
    }
</style>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
