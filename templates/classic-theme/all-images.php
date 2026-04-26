<?php
overall_header(__("All Social Posts"));
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php echo !empty($selected_campaign) ? __('Campaign Output Library') : __('Atlas Output Library') ?>
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
                        <?php if (!empty($selected_campaign)) { ?>
                            <li><a href="<?php _esc(hatchers_campaign_record_url($selected_campaign['id'])) ?>"><?php _e("Campaign Studio") ?></a></li>
                            <li><?php _e("Campaign Output Library") ?></li>
                        <?php } else { ?>
                            <li><?php _e("Atlas Output Library") ?></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>

            <div class="atlas-workflow-hero margin-bottom-24">
                <span class="atlas-workflow-eyebrow"><?php _e("Output archive") ?></span>
                <h2><?php echo !empty($selected_campaign) ? __('Browse this campaign’s full creative output') : __('Browse everything Atlas has generated so far') ?></h2>
                <p><?php echo !empty($selected_campaign)
                    ? __('This library keeps every post connected to the campaign strategy that created it, so review, download, and reuse stay tied to the original direction.')
                    : __('This is your running creative archive across campaigns, one-off generations, and saved outputs.') ?></p>
            </div>

            <?php if (!empty($selected_campaign)) { ?>
                <div class="notification notice">
                    <strong><?php _esc($selected_campaign['title']) ?></strong>
                    <?php _e("Only posts linked to this campaign are shown here.") ?>
                    <a href="<?php url("ALL_IMAGES") ?>" class="margin-left-10"><strong><?php _e("View all posts") ?></strong></a>
                </div>
            <?php } ?>

            <div class="dashboard-box margin-top-0 margin-bottom-30">
                <div class="headline">
                    <h3><i class="icon-feather-grid"></i><?php echo !empty($selected_campaign) ? __('Campaign Creative Library') : __('Creative Library'); ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="row">
                        <?php if (empty($images)) { ?>
                            <div class="col-12">
                                <div class="notification notice"><?php _e("No social posts found.") ?></div>
                            </div>
                        <?php } ?>
                        <?php foreach ($images as $image) { ?>
                            <?php
                            $videoUrl = !empty($image['rendered_video'])
                                ? _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $image['rendered_video']
                                : (!empty($image['source_video']) ? _esc($config['site_url'], 0) . 'storage/social_assets/' . $image['source_video'] : '');
                            $previewUrl = _esc($config['site_url'], 0) . 'storage/social_posts/' . $image['image'];
                            ?>
                            <div class="col-xl-4 col-md-6 margin-bottom-30">
                                <div class="dashboard-box social-post-card margin-top-0">
                                    <div class="content">
                                        <div class="social-post-preview">
                                            <?php if (!empty($videoUrl) && $image['post_type'] === 'reel') { ?>
                                                <video src="<?php echo $videoUrl; ?>" autoplay muted loop playsinline controls preload="metadata"></video>
                                            <?php } else { ?>
                                                <img src="<?php echo $previewUrl; ?>" alt="<?php _esc($image['title']) ?>">
                                            <?php } ?>
                                        </div>
                                        <div class="social-post-body with-padding">
                                            <span class="dashboard-status-button yellow"><?php _esc(ucfirst($image['post_type'])) ?></span>
                                            <h4 class="margin-top-15"><?php _esc($image['title']) ?></h4>
                                            <p class="margin-bottom-10"><?php _esc($image['description']) ?></p>
                                            <?php if (!empty($image['campaign']['title']) && empty($selected_campaign)) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e("Campaign") ?>:</strong> <a href="<?php _esc(hatchers_campaign_record_url($image['campaign']['id'])) ?>"><?php _esc($image['campaign']['title']) ?></a></p>
                                            <?php } ?>
                                            <?php if (!empty($image['hashtags'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e("Hashtags") ?>:</strong> <?php _esc($image['hashtags']) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($image['design'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e("Design") ?>:</strong>
                                                    <?php _esc(!empty($image['design']['headline_font_key']) ? $image['design']['headline_font_key'] : '') ?>
                                                    <?php if (!empty($image['design']['body_font_key'])) { ?> / <?php _esc($image['design']['body_font_key']) ?><?php } ?>
                                                    <?php if (!empty($image['design']['headline_size'])) { ?>, <?php _esc($image['design']['headline_size']) ?>px<?php } ?>
                                                    <?php if (!empty($image['design']['background_tone'])) { ?>, <?php _esc($image['design']['background_tone']) ?><?php } ?>
                                                </p>
                                            <?php } ?>
                                            <?php if (!empty($image['asset']['remote_provider']) && $image['asset']['remote_provider'] === 'unsplash' && !empty($image['asset']['remote_author'])) { ?>
                                                <p class="margin-bottom-10">
                                                    <small>
                                                        <?php _e('Photo by') ?>
                                                        <?php if (!empty($image['asset']['remote_author_url'])) { ?>
                                                            <a href="<?php echo _esc($image['asset']['remote_author_url'], 0); ?>" target="_blank" rel="nofollow noopener"><?php _esc($image['asset']['remote_author']) ?></a>
                                                        <?php } else { ?>
                                                            <?php _esc($image['asset']['remote_author']) ?>
                                                        <?php } ?>
                                                        <?php _e('on') ?>
                                                        <?php if (!empty($image['asset']['remote_page_url'])) { ?>
                                                            <a href="<?php echo _esc($image['asset']['remote_page_url'], 0); ?>" target="_blank" rel="nofollow noopener">Unsplash</a>
                                                        <?php } else { ?>
                                                            Unsplash
                                                        <?php } ?>
                                                    </small>
                                                </p>
                                            <?php } ?>
                                            <?php if (!empty($image['debug'])) { ?>
                                                <details class="margin-bottom-10">
                                                    <summary><strong><?php _e('Debug') ?></strong></summary>
                                                    <div class="margin-top-10">
                                                        <?php if (!empty($image['debug']['generation_source'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Copy Source') ?>:</strong> <?php _esc($image['debug']['generation_source']) ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($image['debug']['openai']['error'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('OpenAI Debug') ?>:</strong> <?php _esc($image['debug']['openai']['attempt'] . ' - ' . $image['debug']['openai']['error']) ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </details>
                                            <?php } ?>
                                            <p class="margin-bottom-15"><small><?php echo _esc($image['date'], 0) . ' <strong>' . _esc($image['time'], 0) . '</strong>' ?></small></p>
                                            <?php
                                            $captionExport = $image['description'];
                                            if (!empty($image['hashtags'])) {
                                                $captionExport .= "\n\n" . $image['hashtags'];
                                            }
                                            $downloadUrl = (!empty($videoUrl) && $image['post_type'] === 'reel') ? $videoUrl : $previewUrl;
                                            ?>
                                            <div class="social-post-actions">
                                                <a href="<?php echo $downloadUrl; ?>" class="social-action-btn" download title="<?php _e("Download Post") ?>" aria-label="<?php _e("Download Post") ?>"><i class="fa fa-download"></i></a>
                                                <a href="#" class="social-action-btn download-caption" data-title="<?php _esc($image['title']) ?>" data-caption="<?php _esc($captionExport) ?>" title="<?php _e("Download Caption") ?>" aria-label="<?php _e("Download Caption") ?>"><i class="fa fa-file-text-o"></i></a>
                                                <?php if (!empty($videoUrl) && $image['post_type'] === 'reel') { ?>
                                                    <a href="<?php echo $videoUrl; ?>" class="social-action-btn" target="_blank" title="<?php _e("Open Reel Video") ?>" aria-label="<?php _e("Open Reel Video") ?>"><i class="fa fa-play"></i></a>
                                                    <a href="<?php echo $videoUrl; ?>" class="social-action-btn" download title="<?php _e("Download Reel Video") ?>" aria-label="<?php _e("Download Reel Video") ?>"><i class="fa fa-film"></i></a>
                                                    <a href="<?php echo $previewUrl; ?>" class="social-action-btn" download title="<?php _e("Download Cover") ?>" aria-label="<?php _e("Download Cover") ?>"><i class="fa fa-image"></i></a>
                                                <?php } ?>
                                                <a href="#" class="social-action-btn social-share-btn" title="<?php _e("Share") ?>" aria-label="<?php _e("Share") ?>"><i class="fa fa-share-alt"></i></a>
                                                <a href="#" class="social-action-btn social-action-danger quick-delete"
                                                   data-id="<?php _esc($image['id']) ?>"
                                                   data-action="delete_image"
                                                   title="<?php _e("Delete") ?>"
                                                   aria-label="<?php _e("Delete") ?>"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($show_paging) { ?>
                        <div class="pagination-container margin-top-20">
                            <nav class="pagination">
                                <ul>
                                    <?php foreach ($pagging as $page) { ?>
                                        <?php if ($page['current'] == 0) { ?>
                                            <li><a href="<?php _esc($page['link']) ?>"><?php _esc($page['title']) ?></a></li>
                                        <?php } else { ?>
                                            <li><a href="#" class="current-page"><?php _esc($page['title']) ?></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </nav>
                        </div>
                    <?php } ?>
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
<style>
    .atlas-output-library-note {
        color: #6f6659;
    }
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
    .social-post-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .social-action-btn {
        flex: 0 0 auto;
    }
</style>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
