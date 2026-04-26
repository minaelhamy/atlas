<?php
overall_header(__("404 - Page Not Found"));
?>
<div class="container atlas-status-shell">
    <div class="atlas-status-card atlas-status-card-wide">
        <span class="atlas-status-eyebrow"><?php _e("Atlas Studio") ?></span>
        <h1>404</h1>
        <h2><?php _e("That page is not part of this workspace anymore") ?></h2>
        <p><?php _e("We could not find the page you were looking for. You can search Atlas content below or head back to the main workspace.") ?></p>

        <form action="<?php url('BLOG') ?>" method="get" class="atlas-status-search">
            <div class="intro-banner-search-form not-found-search">
                <div class="intro-search-field">
                    <input id="intro-keywords" name="s" type="text" placeholder="<?php _e('Looking for other content?') ;?>">
                </div>
                <div class="intro-search-button">
                    <button type="submit" class="button ripple-effect"><?php _e('Search') ;?></button>
                </div>
            </div>
        </form>

        <div class="atlas-status-actions">
            <a href="<?php url("INDEX") ?>" class="button ripple-effect"><?php _e("Go to Home") ?></a>
        </div>
    </div>
</div>
<style>
    .atlas-status-shell {
        min-height: calc(100vh - 190px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 48px;
        padding-bottom: 48px;
    }

    .atlas-status-card {
        width: min(760px, 100%);
        margin: 0 auto;
        padding: 56px 44px;
        border-radius: 30px;
        background:
            radial-gradient(circle at top left, rgba(255,255,255,0.75), transparent 38%),
            linear-gradient(145deg, #f6f0e8 0%, #ece2d5 100%);
        box-shadow: 0 28px 70px rgba(67, 45, 19, 0.12);
        text-align: center;
    }

    .atlas-status-card-wide {
        width: min(860px, 100%);
    }

    .atlas-status-eyebrow {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.7);
        color: #6f655b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .atlas-status-card h1 {
        font-size: clamp(64px, 12vw, 110px);
        line-height: 1;
        letter-spacing: -0.06em;
        margin-bottom: 12px;
    }

    .atlas-status-card h2 {
        font-size: clamp(28px, 4vw, 40px);
        line-height: 1.08;
        letter-spacing: -0.04em;
        margin-bottom: 14px;
    }

    .atlas-status-card p {
        max-width: 620px;
        margin: 0 auto 24px;
        color: #665e56;
        font-size: 16px;
        line-height: 1.75;
    }

    .atlas-status-search {
        margin: 0 auto 18px;
        max-width: 680px;
    }

    .atlas-status-actions {
        display: flex;
        justify-content: center;
    }
</style>
<?php overall_footer();
