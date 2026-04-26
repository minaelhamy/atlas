<?php
overall_header(__("Frequently Asked Questions"));
?>
<?php print_adsense_code('header_bottom'); ?>
    <div class="container atlas-public-shell">
        <div class="atlas-public-hero margin-bottom-40">
            <span class="atlas-public-eyebrow"><?php _e("Help Center") ?></span>
            <h1><?php _e("Frequently asked questions") ?></h1>
            <p><?php _e("Here are the most common questions about Atlas, workspace setup, billing, and how the product fits into your day-to-day creative workflow.") ?></p>
        </div>
        <div class="margin-bottom-50">

            <!-- Accordion -->
            <div class="accordion js-accordion">

                <?php
                $i = 0;
                foreach($faq as $qa){ ?>
                    <!-- Accordion Item -->
                    <div class="accordion__item js-accordion-item <?php if($i==0){ ?> active <?php } ?>">
                        <div class="accordion-header js-accordion-header"><?php _esc($qa['title']) ?></div>

                        <!-- Accordtion Body -->
                        <div class="accordion-body js-accordion-body">

                            <!-- Accordion Content -->
                            <div class="accordion-body__contents">
                                <?php _esc($qa['content']) ?>
                            </div>

                        </div>
                        <!-- Accordion Body / End -->
                    </div>
                    <!-- Accordion Item / End -->
                <?php $i++; } ?>

            </div>
            <!-- Accordion / End -->
        </div>
        <!-- faq-page -->
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
