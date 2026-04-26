<?php overall_header(__("Feedback")); ?>
<?php print_adsense_code('header_bottom'); ?>
<div class="container margin-bottom-50 atlas-public-shell">
    <div class="atlas-public-hero margin-bottom-40">
        <span class="atlas-public-eyebrow"><?php _e("Product Feedback") ?></span>
        <h1><?php _e("Help us improve Atlas") ?></h1>
        <p><?php _e("Tell us what is working, what feels unclear, and what would make Atlas more useful in your real workflow. We treat this as product input, not noise.") ?></p>
    </div>
    <div class="row">
        <div class="col-xl-8 margin-0-auto">
            <h2 class="margin-bottom-20"><?php _e("Tell us what you think of us") ?></h2>
            <span><?php _e("We would like to hear your opinions about the website. We would be grateful if you could take the time to fill out this form") ?></span>
            <div class="feed-back-form margin-top-20">
                <form method="post">
                    <div class="submit-field">
                        <h5><?php _e("Full Name") ?></h5>
                        <input type="text" class="with-border" name="name" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Email Address") ?></h5>
                        <input type="email" class="with-border" name="email" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Subject") ?></h5>
                        <input type="text" class="with-border" name="subject" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Is there anything you would like to tell us?") ?></h5>
                        <textarea type="text" class="with-border" name="message" placeholder="<?php _e("Message") ?>..." required=""></textarea>
                    </div>
                    <div class="submit-field">
                        <?php
                        if($config['recaptcha_mode'] == '1'){
                            echo '<div class="g-recaptcha" data-sitekey="'._esc($config['recaptcha_public_key'],false).'"></div>';
                        }
                        if($recaptcha_error != ''){
                            echo '<span class="status-not-available">'.$recaptcha_error.'</span>';
                        }
                        ?>
                    </div>

                    <input type="submit" name="Submit" class="button" value="<?php _e("Submit") ?>">
                </form>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 content-left-offset">
            <div class="sidebar-container">
                <?php print_adsense_code('blog_sidebar_top'); ?>
                <div class="margin-bottom-40">
                    <h3 class="widget-title"><?php _e("Recent Blog") ?></h3>
                    <div class="recent-post-widget">
                        <?php
                        foreach($recent_blog as $recent_blogs){
                            $image_url = $config['site_url'].'storage/blog/'.$recent_blogs['image'];
                            ?>
                            <div>
                                <?php
                                if($config['blog_banner']){ ?>
                                    <a href="<?php _esc($recent_blogs['link']) ?>">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?php _esc($image_url) ?>" alt="<?php _esc($recent_blogs['title']) ?>"
                                             class="post-thumb lazy-load">
                                    </a>
                                <?php } ?>
                                <div class="recent-post-widget-content">
                                    <h2><a href="<?php _esc($recent_blogs['link']) ?>"><?php _esc($recent_blogs['title']) ?></a></h2>
                                    <div class="post-date">
                                        <i class="icon-feather-clock"></i> <?php _esc($recent_blogs['created_at']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>


                <?php
                if($config['testimonials_enable'] && $config['show_testimonials_blog']){
                    ?>
                    <!-- Testimonials Widget -->
                    <div class="sidebar-widget">
                        <h3><?php _e("Testimonials") ?></h3>
                        <div class="single-carousel">
                            <?php
                            foreach($testimonials as $testimonial){
                                ?>
                                <div class="single-testimonial">
                                    <div class="single-inner">
                                        <div class="testimonial-content">
                                            <p><?php _esc($testimonial['content']) ?></p>
                                        </div>
                                        <div class="testi-author-info">
                                            <div class="image"><img src="<?php _esc($config['site_url']);?>storage/testimonials/<?php _esc($testimonial['image']) ?>" alt="<?php _esc($testimonial['name']) ?>"></div>
                                            <h5 class="name"><?php _esc($testimonial['name']) ?></h5>
                                            <span class="designation"><?php _esc($testimonial['designation']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Testimonials Widget / End-->
                <?php } ?>

                <!-- Social Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Social Profiles") ?></h3>
                    <div class="freelancer-socials margin-top-25">
                        <ul>
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
                    </div>
                </div>
                <?php print_adsense_code('blog_sidebar_bottom'); ?>
            </div>
        </div>
    </div>
</div>
<!-- main -->
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
overall_footer();
?>
