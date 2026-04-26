<?php
overall_header(__("Report Violation"));
?>
<div class="container margin-bottom-50 atlas-public-shell">
    <div class="atlas-public-hero margin-bottom-40">
        <span class="atlas-public-eyebrow"><?php _e("Trust & Safety") ?></span>
        <h1><?php _e("Report a violation") ?></h1>
        <p><?php _e("If something in the product or community breaks the rules, tell us here. We review these reports to keep the workspace safer and more trustworthy.") ?></p>
    </div>
    <div class="row"><!-- user-login -->
        <div class="col-xl-8 margin-0-auto">
            <div class="user-account clearfix">
                <form action="#" method="post">
                    <div class="submit-field">
                      <h5><?php _e("Your Name") ?></h5>
                      <input class="with-border" type="text" name="name" value="<?php _esc($name);?>">
                        <?php
                        if($name_error != ''){
                            echo '<span class="status-not-available">'.$name_error.'</span>';
                        }
                        ?>
                    </div>
                    <div class="submit-field">
                      <h5><?php _e("Your E-Mail") ?></h5>
                      <input class="with-border" type="email" name="email" value="<?php _esc($email);?>">
                        <?php
                        if($email_error != ''){
                            echo '<span class="status-not-available">'.$email_error.'</span>';
                        }
                        ?>
                    </div>
                    <div class="submit-field">
                      <h5><?php _e("Your Username") ?></h5>
                      <input class="with-border" type="text" name="username" value="<?php _esc($username);?>">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Violation") ?> <?php _e("Type") ?></h5>
                        <select name="violation" class="selectpicker with-border">
                            <option><?php _e("Select") ?> <?php _e("Violation") ?> <?php _e("Type") ?></option>
                            <option value="<?php _e("Posting contact information") ?>"><?php _e("Posting contact information") ?></option>
                            <option value="<?php _e("Advertising another website") ?>"><?php _e("Advertising another website") ?></option>
                            <option value="<?php _e("Fake job posted") ?>"><?php _e("Fake job posted") ?></option>
                            <option value="<?php _e("Non-featured job posted requiring abnormal bidding") ?>"><?php _e("Non-featured job posted requiring abnormal bidding") ?></option>
                            <option value="<?php _e("Other") ?>"><?php _e("Other") ?></option>
                        </select>
                    </div>
                    <div class="submit-field">
                      <h5><?php _e("Username of other person") ?></h5>
                      <input class="with-border" type="text" name="username2" value="<?php _esc($username2);?>">
                    </div>
                    <div class="submit-field">
                      <h5><?php _e("URL of violation") ?></h5>
                      <input class="with-border" type="text" name="url" value="<?php _esc($redirect_url);?>">
                    </div>
                    <div class="submit-field">
                      <h5><?php _e("Violation Details") ?></h5>
                      <textarea class="with-border" name="details"><?php _esc($details);?></textarea>
                        <?php
                        if($viol_error != ''){
                            echo '<span class="status-not-available">'.$viol_error.'</span>';
                        }
                        ?>
                    </div>
                    <button type="submit" name="Submit" id="submit" class="button"><?php _e("Report Violation") ?></button>
                </form>
                <!-- checkbox -->
            </div>
        </div>
        <!-- user-login -->
    </div>
    <!-- row -->
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
