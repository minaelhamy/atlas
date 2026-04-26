<?php
overall_header(__("Message"));
?>
<div class="container atlas-status-shell">
    <div class="atlas-status-card">
        <span class="atlas-status-eyebrow"><?php _e("Atlas Message") ?></span>
        <h2><?php _esc($heading)?>!</h2>
        <p><?php _esc($message) ?></p>
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

    .atlas-status-card h2 {
        font-size: clamp(28px, 4vw, 40px);
        line-height: 1.08;
        letter-spacing: -0.04em;
        margin-bottom: 14px;
    }

    .atlas-status-card p {
        max-width: 620px;
        margin: 0 auto;
        color: #665e56;
        font-size: 16px;
        line-height: 1.75;
    }
</style>
<?php
overall_footer();
?>
