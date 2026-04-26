<?php
overall_header(__("Testimonials"));
?>
<?php print_adsense_code('header_bottom'); ?>
    <div class="container margin-bottom-50 atlas-public-shell">
        <div class="atlas-public-hero margin-bottom-40">
            <span class="atlas-public-eyebrow"><?php _e("Proof") ?></span>
            <h1><?php _e("What people say about the Atlas experience") ?></h1>
            <p><?php _e("These stories show how customers describe the support, workflow clarity, and outcomes they experienced while using the product.") ?></p>
        </div>
        <div class="row">
            <?php foreach($testimonials as $testimonial){ ?>
                <div class="col-md-4">
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
                </div>
            <?php } ?>
        </div>
        <?php if($show_paging){ ?>
            <div class="pagination-container margin-top-20">
                <nav class="pagination">
                    <ul>
                        <?php
                        foreach($pages as $page) {
                            if ($page['current'] == 0)
                                echo '<li><a href="' . _esc($page['link'],false) . '" class="ripple-effect">' . _esc($page['title'],false) . '</a></li>';
                            else
                                echo '<li><a href="#" class="current-page ripple-effect">' . _esc($page['title'],false) . '</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        <?php } ?>
    </div>
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
