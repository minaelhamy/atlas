<?php
overall_header(__("Sitemap"));
?>
    <div class="container margin-bottom-50 atlas-public-shell">
        <div class="atlas-public-hero margin-bottom-40">
            <span class="atlas-public-eyebrow"><?php _e("Directory") ?></span>
            <h1><?php _e("Browse the Atlas sitemap") ?></h1>
            <p><?php _e("Use this page to move through the major content categories and discover the sections available across the public Atlas experience.") ?></p>
        </div>
        <div class="section">
            <h2 class="text-center sitemap-h2"><?php _e("Categories") ?></h2>
            <hr>
            <div class="row cg-nav-wrapper cg-nav-wrapper-row-2" data-role="cg-nav-wrapper">
                <?php foreach ($category as $cat){ ?>
                <div>
                    <div class="anchor-wrap anchor<?php _esc($cat['main_id']) ?>-wrap" data-role="anchor<?php _esc($cat['main_id']) ?>">
                        <a class="anchor<?php _esc($cat['main_id']) ?> jumper" data-role="cont" href="#anchor<?php _esc($cat['main_id']) ?>">
                            <i class="caticon <?php _esc($cat['icon']) ?>"></i>
                        <span class="desc">
                            <?php _esc($cat['main_title']) ?>
                        </span>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="cg-main">
                <?php foreach ($subcategory as $subcat){ ?>
                <div class="item clearfix" data-spm="0">
                    <h3 class="big-title anchor<?php _esc($subcat['main_id']) ?> anchor-agricuture" data-role="anchor<?php _esc($subcat['main_id']) ?>-scroll">
                        <span id="anchor<?php _esc($subcat['main_id']) ?>" class="anchor-subsitution"></span>
                        <i class="cg-icon <?php _esc($subcat['icon']) ?>"></i><?php _esc($subcat['main_title']) ?>
                    </h3>

                    <div class="sub-item-wrapper clearfix">
                        <div class="sub-item">
                            <h4 class="sub-title">
                                <a href="<?php _esc($subcat['catlink']) ?>"><?php _esc($subcat['main_title']) ?></a><span> (<?php _esc($subcat['main_ads_count']) ?>)</span>
                            </h4>
                            <?php if($subcat['sub_cat']){ ?>
                            <div class="sub-item-cont-wrapper">
                                <ul class="sub-item-cont clearfix">
                                    <?php _esc($subcat['sub_title']) ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
                <?php } ?>
            </div>

        </div>
    </div>
<script>
    $(document).ready(function() {
        $(".jumper").on("click", function( e ) {

            e.preventDefault();

            $("body, html").animate({
                scrollTop: $( $(this).attr('href') ).offset().top
            }, 600);

        });
    });
</script>
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
