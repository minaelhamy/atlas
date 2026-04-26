<?php overall_header(__('Advertise here')); ?>
<script src="https://checkout.stripe.com/v2/checkout.js"></script>
<div class="container margin-bottom-50 atlas-public-shell">
    <div class="atlas-public-hero margin-bottom-40">
        <span class="atlas-public-eyebrow"><?php _e("Partnerships") ?></span>
        <h1><?php _e("Advertise with Atlas") ?></h1>
        <p><?php _e("Use this page to explore promotional placements and partnership opportunities inside the Atlas ecosystem.") ?></p>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="found-section section">
                <?php if($is_login){
                    echo '<div class="section html-pages"><div class="qbm-box"></div></div>';
                }else{
                    ?>
                    <h1 class="margin-bottom-20"><?php _e("Login required") ?></h1>
                    <p><?php _e("Login required to access this page.") ?></p>
                    <a href="#sign-in-dialog" class="button ripple-effect popup-with-zoom-anim "><?php _e("Click Here") ?>   </a>
                <?php } ?>

            </div>
        </div>
    </div>
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
