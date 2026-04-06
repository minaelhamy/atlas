<?php $__env->startSection('content'); ?>
    <section>
        <div class="row align-items-center g-0 w-100 h-100vh position-relative">
            <div class="col-xl-7 col-lg-6 col-md-6 d-md-block d-none">
                <img src="<?php echo e(helper::image_path(helper::appdata('')->admin_auth_pages_bg_image)); ?>" alt=""
                    class="object h-100vh w-100">
            </div>
            <div class="col-xl-5 col-lg-6 col-md-6">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="col-xl-8">
                        <div class="login-right-content h-100">
                            <div class="pb-0 px-0">
                                <div class="text-primary d-flex align-items-center justify-content-between">
                                    <div>
                                        <h2 class="fw-bold text-color color-changer title-text mb-2">
                                            <?php echo e(trans('labels.login')); ?></h2>
                                        <p class="text-color color-changer"><?php echo e(trans('labels.please_login')); ?></p>
                                    </div>
                                    <!-- FOR SMALL DEVICE TOP CATEGORIES -->
                                    <?php if(helper::available_language('')->count() > 1): ?>
                                        <?php if(@helper::checkaddons('language')): ?>
                                            <div class="lag-btn dropdown border-0 shadow-none login-lang">
                                                <button class="border-0 bg-transparent language-dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <img src="<?php echo e(helper::image_path(session()->get('flag'))); ?>"
                                                        alt="" class="lag-img rounded-circle w-25">
                                                </button>
                                                <ul
                                                    class="dropdown-menu rounded-1 mt-1 p-0 bg-body-secondary shadow border-0 rounded-3 overflow-hidden">
                                                    <?php $__currentLoopData = helper::listoflanguage(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languagelist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <a class="dropdown-item text-dark d-flex align-items-center px-2 gap-2 py-2"
                                                                href="<?php echo e(URL::to('/lang/change?lang=' . $languagelist->code)); ?>">
                                                                <img src="<?php echo e(helper::image_path($languagelist->image)); ?>"
                                                                    alt="" class="img-fluid lag-img w-25">
                                                                <?php echo e($languagelist->name); ?>

                                                            </a>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <form class="mt-3" method="POST" action="<?php echo e(URL::to('/admin/checklogin-normal')); ?>"
                                    id="login-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="email" class="form-label"><?php echo e(trans('labels.email')); ?>

                                            <span class="text-danger"> * </span></label>
                                        <input type="email" class="form-control extra-padding" name="email"
                                            placeholder="<?php echo e(trans('labels.email')); ?>" id="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password"
                                            class="form-label fs-7 text-color"><?php echo e(trans('labels.password')); ?><span
                                                class="text-danger"> * </span></label>
                                        <div class="form-control extra-padding d-flex align-items-center gap-3">
                                            <input type="password" class="form-control text-color border-0 p-0"
                                                name="password" placeholder="<?php echo e(trans('labels.password')); ?>" id="password"
                                                required="">
                                            <span>
                                                <a href="#"><i class="fa-light fa-eye-slash color-changer"
                                                        id="eye"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="form-group mb-2 col-6 d-flex align-items-center">
                                            <input class="form-check-input mt-0" type="checkbox" value=""
                                                name="remember_me" id="remember_me" checked>
                                            <label class="form-check-label cursor-pointer mx-1" for="remember_me">
                                                <span class="fs-7 text-color color-changer">
                                                    <?php echo e(trans('labels.remember_me')); ?>

                                                </span>
                                            </label>
                                        </div>
                                        <div
                                            class="<?php echo e(session()->get('direction') == 2 ? 'text-start' : 'text-end '); ?> mb-2 col-6">
                                            <a href="<?php echo e(URL::to('/admin/forgot_password')); ?>"
                                                class="fs-7 fw-600 color-changer">
                                                <?php echo e(trans('labels.forgot_password_')); ?>

                                            </a>
                                        </div>
                                    </div>
                                    <?php if(env('Environment') != 'sendbox'): ?>
                                        <button class="btn btn-primary w-100 mt-2 mb-3 padding"
                                            type="submit"><?php echo e(trans('labels.login')); ?></button>
                                    <?php endif; ?>

                                    <?php if(helper::appdata('')->vendor_register == 1): ?>
                                        <p class="fs-7 text-center mt-2 color-changer">
                                            <?php echo e(trans('labels.dont_have_account')); ?>

                                            <a href="<?php echo e(URL::to('admin/register')); ?>"
                                                class="text-secondary fw-semibold text-decoration fw-600"><?php echo e(trans('labels.create_acount')); ?></a>
                                        </p>
                                    <?php endif; ?>
                                    <?php if(env('Environment') == 'sendbox'): ?>
                                        <div class="my-3 border-bottom"></div>
                                        <p class="text-center text-danger">Explore with <b
                                                class="text-black color-changer">Included</b>
                                            addons
                                        </p>

                                        <div class="d-flex">
                                            <button class="btn btn-secondary w-50 mt-2 mb-3 padding mx-2"
                                                id="admin_free_addon_login">Admin login</button>

                                            <button class="btn btn-secondary w-50 mt-2 mb-3 padding mx-2"
                                                id="vendor_free_addon_login">Vendor login</button>
                                        </div>

                                        <p class="text-center text-danger">Explore with <b
                                                class="text-black color-changer">ALL</b> addons
                                        </p>

                                        <div class="d-flex">
                                            <button class="btn btn-secondary w-50 mt-2 mb-3 padding mx-2"
                                                id="admin_all_addon">Admin
                                                login</button>

                                            <button class="btn btn-secondary w-50 mt-2 mb-3 padding mx-2"
                                                id="vendor_all_addon">Vendor login</button>
                                        </div>
                                    <?php endif; ?>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php if(env('Environment') == 'sendbox'): ?>
        <button class="btn btn-primary theme-label text-white" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">

            <i class="fa-solid fa-list text-white px-2"></i>
            Themes</button>

        <div class="offcanvas <?php echo e(session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end '); ?>" tabindex="-1"
            id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header justify-content-between border-bottom">
                <h5 id="offcanvasRightLabel" class="color-changer">Themes</h5>
                <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                    aria-label="Close">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="row px-3">
                    <a href="https://store-mart.paponapps.co.in/theme-1" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-1.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 1</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-2" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-2.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 2</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-3" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-3.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 3</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-4" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-4.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 4</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-5" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-5.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 5</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-6" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-6.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 6</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-7" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-7.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 7</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-8" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-8.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 8</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-9" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-9.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 9</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-10" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-10.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 10</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-11" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-11.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 11</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-12" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-12.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 12</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-13" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-13.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 13</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-14" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-14.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 14</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-15" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="<?php echo e(helper::image_path('theme-15.png')); ?>" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 15</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php if(count($errors) > 0): ?>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>");
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <script>
        function AdminFill(email, password) {
            $('#email').val(email);
            $('#password').val(password);
        }
        // password eye hide
        $(function() {
            $('#eye').click(function() {
                if ($(this).hasClass('fa-eye-slash')) {
                    $(this).removeClass('fa-eye-slash');
                    $(this).addClass('fa-eye');
                    $('#password').attr('type', 'text');
                } else {
                    $(this).removeClass('fa-eye');
                    $(this).addClass('fa-eye-slash');
                    $('#password').attr('type', 'password');
                }
            });
        });


        $(document).on("click", "#super_admin", function() {
            $("#super_admin").attr("disabled", true);

            $("#email").val('admin@gmail.com');
            $("#password").val('123456');
            SessionSave('all-addon');
        });

        $(document).on("click", "#vendor_admin", function() {
            $("#vendor_admin").attr("disabled", true);

            $("#email").val('grocery@yopmail.com');
            $("#password").val('123456');
            SessionSave('all-addon');
        });

        $(document).on("click", "#admin_free_addon_login", function() {
            $("#admin_free_addon_login").attr("disabled", true);

            $("#email").val('admin@gmail.com');
            $("#password").val('123456');
            SessionSave('free-addon');
        });

        $(document).on("click", "#vendor_free_addon_login", function() {
            $("#vendor_free_addon_login").attr("disabled", true);

            $("#email").val('grocery@yopmail.com');
            $("#password").val('123456');
            SessionSave('free-addon');
        });

        $(document).on("click", "#admin_free_with_extended_addon_login", function() {
            $("#admin_free_with_extended_addon_login").attr("disabled", true);

            $("#email").val('admin@gmail.com');
            $("#password").val('123456');
            SessionSave('free-with-extended-addon');
        });

        $(document).on("click", "#vendor_free_with_extended_addon_login", function() {
            $("#vendor_free_with_extended_addon_login").attr("disabled", true);

            $("#email").val('grocery@yopmail.com');
            $("#password").val('123456');
            SessionSave('free-with-extended-addon');
        });

        $(document).on("click", "#admin_all_addon", function() {
            $("#admin_all_addon").attr("disabled", true);

            $("#email").val('admin@gmail.com');
            $("#password").val('123456');
            SessionSave('all-addon');
        });

        $(document).on("click", "#vendor_all_addon", function() {
            $("#vendor_all_addon").attr("disabled", true);

            $("#email").val('grocery@yopmail.com');
            $("#password").val('123456');
            SessionSave('all-addon');
        });

        function SessionSave(addon = null) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: "json",
                url: "<?php echo e(URL::to('add-on/session/save')); ?>",
                data: {
                    'demo_type': addon,
                },
                success: function(response) {
                    $('#login-form').submit();
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.auth_default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>