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
                    <?php _e("All Social Posts") ?>
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
                        <li><?php _e("All Social Posts") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="dashboard-box margin-top-0 margin-bottom-30">
                <div class="headline">
                    <h3><i class="icon-feather-grid"></i><?php _e("Social Post Library") ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="row">
                        <?php if (empty($images)) { ?>
                            <div class="col-12">
                                <div class="notification notice"><?php _e("No social posts found.") ?></div>
                            </div>
                        <?php } ?>
                        <?php foreach ($images as $image) { ?>
                            <div class="col-xl-4 col-md-6 margin-bottom-30">
                                <div class="dashboard-box social-post-card margin-top-0">
                                    <div class="content">
                                        <div class="social-post-preview">
                                            <img src="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/' . $image['image']; ?>" alt="<?php _esc($image['title']) ?>">
                                        </div>
                                        <div class="social-post-body with-padding">
                                            <span class="dashboard-status-button yellow"><?php _esc(ucfirst($image['post_type'])) ?></span>
                                            <h4 class="margin-top-15"><?php _esc($image['title']) ?></h4>
                                            <p class="margin-bottom-10"><?php _esc($image['description']) ?></p>
                                            <?php if (!empty($image['hashtags'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e("Hashtags") ?>:</strong> <?php _esc($image['hashtags']) ?></p>
                                            <?php } ?>
                                            <p class="margin-bottom-15"><small><?php echo _esc($image['date'], 0) . ' <strong>' . _esc($image['time'], 0) . '</strong>' ?></small></p>
                                            <?php
                                            $captionExport = $image['description'];
                                            if (!empty($image['hashtags'])) {
                                                $captionExport .= "\n\n" . $image['hashtags'];
                                            }
                                            ?>
                                            <div class="d-flex flex-wrap">
                                                <a href="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/' . $image['image']; ?>" class="button ripple-effect btn-sm margin-right-5" download><i class="fa fa-download"></i></a>
                                                <a href="#" class="button ripple-effect btn-sm margin-right-5 download-caption" data-title="<?php _esc($image['title']) ?>" data-caption="<?php _esc($captionExport) ?>"><?php _e('Caption') ?></a>
                                                <?php if (!empty($image['rendered_video'])) { ?>
                                                    <a href="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $image['rendered_video']; ?>" class="button ripple-effect btn-sm margin-right-5" target="_blank"><i class="fa fa-play"></i></a>
                                                    <a href="<?php echo _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $image['rendered_video']; ?>" class="button ripple-effect btn-sm margin-right-5" download><?php _e("Video") ?></a>
                                                <?php } ?>
                                                <a href="#" class="button red ripple-effect btn-sm quick-delete"
                                                   data-id="<?php _esc($image['id']) ?>"
                                                   data-action="delete_image"
                                                   title="<?php _e("Delete") ?>"><i class="fa fa-trash-o"></i></a>
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
    .social-post-card .social-post-preview img {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
    }
    .social-post-card .social-post-body {
        background: #fff;
    }
</style>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
