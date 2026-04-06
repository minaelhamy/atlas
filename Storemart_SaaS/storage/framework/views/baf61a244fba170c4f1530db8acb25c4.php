<?php if(@helper::checkaddons('google_recaptcha')): ?>
    <?php if(helper::adminappdata()->recaptcha_version == 'v2'): ?>
        <div class="col-12">
            <div class="g-recaptcha" data-sitekey="<?php echo e(helper::adminappdata()->google_recaptcha_site_key); ?>"></div>
            <?php if($errors->has('g-recaptcha-response')): ?>
                <span class="text-danger"><?php echo e($errors->first('g-recaptcha-response')); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(helper::adminappdata()->recaptcha_version == 'v3'): ?>
        <div class="col-12">
            <?php echo RecaptchaV3::field('contact'); ?>

            <?php if($errors->has('g-recaptcha-response')): ?>
                <span class="text-danger"><?php echo e($errors->first('g-recaptcha-response')); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/landing/layout/recaptcha.blade.php ENDPATH**/ ?>