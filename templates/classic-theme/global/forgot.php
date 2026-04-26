<?php
overall_header(__("Forgot Password?"));
$logo_dark = !empty($config['site_logo']) ? $config['site_url'] . 'storage/logo/' . $config['site_logo'] : '';
?>
<?php print_adsense_code('header_bottom'); ?>
<div class="container atlas-login-shell">
    <div class="row">
        <div class="col-xl-10 col-lg-11 col-md-12 mx-auto">
            <div class="atlas-auth-stage">
                <div class="atlas-auth-story">
                    <div class="atlas-auth-story-inner">
                        <span class="atlas-auth-eyebrow"><?php _e("Secure Access") ?></span>
                        <h1><?php _e("Recover your studio without losing momentum") ?></h1>
                        <p><?php _e("Enter your email and we will send a guided recovery link so you can get back into Atlas, your brand systems, and active campaigns quickly.") ?></p>

                        <div class="atlas-auth-promise">
                            <div class="atlas-auth-promise-card">
                                <strong><?php _e("Fast reset") ?></strong>
                                <span><?php _e("We send the recovery path directly to the email attached to your workspace.") ?></span>
                            </div>
                            <div class="atlas-auth-promise-card">
                                <strong><?php _e("Protected workspace") ?></strong>
                                <span><?php _e("Recovery is tied to your account details so your data and campaign assets stay secure.") ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login-register-page atlas-login-page atlas-auth-card">
                    <div class="welcome-text atlas-login-brand atlas-login-brand-left">
                        <?php if (!empty($logo_dark)) { ?>
                            <img src="<?php _esc($logo_dark) ?>" alt="<?php _esc($config['site_title']) ?>" class="atlas-login-logo">
                        <?php } else { ?>
                            <h3><?php _esc($config['site_title']) ?></h3>
                        <?php } ?>
                        <span><?php _e("Password recovery") ?></span>
                    </div>

                    <div class="atlas-auth-heading">
                        <h2><?php _e("Request a reset link") ?></h2>
                        <p><?php _e("We will email you the next step to restore access to your Atlas workspace.") ?></p>
                    </div>

                    <?php
                    if($success != ''){
                        echo '<span class="status-available">'.__("Confirmation mail sent.").'<br>'._esc($success,false).'</span>';
                    }
                    if($login_error != ''){
                        echo '<span class="status-not-available">'._esc($login_error,false).'</span>';
                    }
                    ?>

                    <form method="post">
                        <div class="input-with-icon-left">
                            <i class="la la-envelope"></i>
                            <input type="email" class="input-text with-border" name="email" id="email"
                                   placeholder="<?php _e("Email Address") ?>" required/>
                        </div>
                        <button class="button full-width button-sliding-icon ripple-effect margin-top-10" name="submit" type="submit"><?php _e("Send Recovery Link") ?> <i class="icon-feather-arrow-right"></i></button>
                    </form>

                    <div class="atlas-login-footnote">
                        <span><?php _e("Remembered it?") ?> <a href="<?php url("LOGIN") ?>"><?php _e("Return to login") ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="atlas-login-footer">
    <div class="atlas-login-footer-copy">2026 Hatchers.ai, All right reserved</div>
    <div class="atlas-login-footer-social">
        <?php if ($config['facebook_link'] != "") { ?>
            <a href="<?php _esc($config['facebook_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
        <?php } ?>
        <?php if ($config['instagram_link'] != "") { ?>
            <a href="<?php _esc($config['instagram_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
        <?php } ?>
        <?php if ($config['twitter_link'] != "") { ?>
            <a href="<?php _esc($config['twitter_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="X"><i class="fa fa-twitter"></i></a>
        <?php } ?>
        <?php if ($config['youtube_link'] != "") { ?>
            <a href="<?php _esc($config['youtube_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="YouTube"><i class="fa fa-youtube"></i></a>
        <?php } ?>
    </div>
</div>
<style>
    .atlas-login-shell {
        min-height: calc(100vh - 170px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .atlas-login-shell > .row {
        width: 100%;
        justify-content: center;
    }

    .atlas-auth-stage {
        display: grid;
        grid-template-columns: 1.02fr .98fr;
        gap: 28px;
        align-items: stretch;
        max-width: 1120px;
        margin: 0 auto;
    }

    .atlas-auth-story,
    .atlas-auth-card {
        min-height: 70vh;
        border-radius: 28px;
    }

    .atlas-auth-story {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.7), transparent 45%),
            linear-gradient(145deg, #f4eee6 0%, #e8ddcf 100%);
        box-shadow: 0 30px 80px rgba(67, 45, 19, 0.12);
    }

    .atlas-auth-story-inner {
        padding: 54px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .atlas-auth-eyebrow {
        display: inline-flex;
        width: fit-content;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.68);
        color: #6f655b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .atlas-auth-story h1 {
        font-size: clamp(34px, 4vw, 50px);
        line-height: 1.05;
        letter-spacing: -0.04em;
        margin-bottom: 18px;
        color: #171717;
    }

    .atlas-auth-story p {
        font-size: 17px;
        line-height: 1.75;
        color: #5f564e;
        max-width: 540px;
        margin-bottom: 28px;
    }

    .atlas-auth-promise {
        display: grid;
        gap: 14px;
    }

    .atlas-auth-promise-card {
        padding: 18px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(133, 109, 80, 0.12);
    }

    .atlas-auth-promise-card strong {
        display: block;
        color: #171717;
        font-size: 15px;
        margin-bottom: 6px;
    }

    .atlas-auth-promise-card span {
        display: block;
        color: #6a6259;
        font-size: 14px;
        line-height: 1.65;
    }

    .atlas-auth-card {
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 30px 80px rgba(59, 40, 18, 0.12);
        padding: 42px 38px;
    }

    .atlas-login-brand {
        margin-bottom: 20px;
    }

    .atlas-login-brand-left {
        text-align: left;
    }

    .atlas-login-logo {
        display: block;
        width: auto;
        max-width: 220px;
        max-height: 72px;
        margin: 0 0 14px;
    }

    .atlas-login-brand span {
        color: #8d877f;
        font-size: 15px;
        font-weight: 500;
    }

    .atlas-auth-heading {
        margin-bottom: 22px;
    }

    .atlas-auth-heading h2 {
        font-size: 30px;
        line-height: 1.08;
        letter-spacing: -0.03em;
        margin-bottom: 10px;
    }

    .atlas-auth-heading p {
        margin: 0;
        color: #756d64;
        line-height: 1.7;
        font-size: 15px;
    }

    .atlas-login-footnote {
        margin-top: 22px;
        text-align: center;
        color: #8d877f;
        font-size: 14px;
    }

    .atlas-login-footer {
        width: min(1320px, calc(100% - 48px));
        margin: 0 auto 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        color: #8d877f;
        font-size: 15px;
    }

    .atlas-login-footer-social {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .atlas-login-footer-social a {
        color: #4f4a43;
        font-size: 18px;
        line-height: 1;
    }

    .atlas-login-footer-social a:hover {
        color: #171717;
    }

    @media (max-width: 991px) {
        .atlas-auth-stage {
            grid-template-columns: 1fr;
        }

        .atlas-auth-story,
        .atlas-auth-card {
            min-height: auto;
        }

        .atlas-auth-story-inner,
        .atlas-auth-card {
            padding: 34px 26px;
        }
    }

    @media (max-width: 767px) {
        .atlas-login-footer {
            width: calc(100% - 32px);
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>
<?php
overall_footer();
?>
