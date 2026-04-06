<section class="py-5">
    <div class="container">
        <div class="bg-primary-rgb bg-changer rounded-2 p-4">
            <div class="have-project-contain row align-items-center justify-content-center">
                <div class="col-lg-5 overflow-hidden d-lg-block d-none">
                    <div class="d-flex justify-content-center">
                        <img src="<?php echo e(@helper::image_path(helper::landingsettings()->subscribe_image)); ?>"
                            alt="" class="img-fluid object-fit-cover project-img">
                    </div>
                </div>
                <div class="col-lg-6 overflow-hidden">
                    <div>
                        <div>
                            <h6 class="have-project-title color-changer p-0">
                                <?php echo e(trans('landing.subscribe_section_title')); ?>

                            </h6>
                        </div>
                        <p class="have-project-subtitle mt-4 text-muted col-md-11 sub-title-mein">
                            <?php echo e(trans('landing.subscribe_section_description')); ?>

                        </p>
                        <form action="<?php echo e(URL::to('/emailsubscribe')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="mt-4 mb-sm-0 mb-3 d-flex gap-2 border-0 input-btn">
                                <input type="email" class="form-control border-0" name="email"
                                    placeholder="<?php echo e(trans('labels.email')); ?>" required>
                                <button type="submit"
                                    class="btn-secondary mx-0 rounded-2"><?php echo e(trans('landing.subscribe')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/landing/newslatter.blade.php ENDPATH**/ ?>