<header class="page-topbar">
    <?php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $user = App\Models\User::where('id', $vendor_id)->first();
    ?>
    <div class="container-fluid">
        <div class="navbar-header py-2">
            <button class="navbar-toggler d-lg-none d-md-block" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarcollapse" aria-expanded="false" aria-controls="sidebarcollapse">
                <i class="fa-regular fa-bars fs-4 color-changer"></i>
            </button>
            <div class="d-flex align-items-center gap-2">
                <?php if(session('vendor_login')): ?>
                    <a href="<?php echo e(URL::to('/admin/admin_back')); ?>" title="<?php echo e(trans('labels.back_to_admin')); ?>"
                        class="btn btn-primary header-btn-icon btn-sm tooltip-bottom">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>
                <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                    <a class="btn btn-primary header-btn-icon btn-sm tooltip-bottom"
                        title="<?php echo e(trans('labels.view_website')); ?>"
                        href="<?php if($user->custom_domain == null): ?> <?php echo e(URL::to('/' . $user->slug)); ?> <?php else: ?> https://<?php echo e($user->custom_domain); ?> <?php endif; ?>"
                        target="_blank"><i class="fa-solid fa-link"></i>
                    </a>
                <?php endif; ?>
                <!-- dekstop-tablet-mobile-language-dropdown-button-start-->
                <?php if(@helper::checkaddons('language')): ?>
                    <?php if(helper::available_language('')->count() > 1): ?>
                        <div class="position-relative">
                            <div class="lag-btn dropdown border-0 shadow-none login-lang">
                                <a class="btn btn-sm btn-primary header-btn-icon dropdown-toggle" href="#"
                                    role="button" data-bs-toggle ="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-globe"></i>
                                </a>
                                <ul
                                    class="dropdown-menu rounded mt-1 p-0 bg-body-secondary shadow border-0 overflow-hidden <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                                    <?php $__currentLoopData = helper::available_language(''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languagelist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a class="dropdown-item text-dark d-flex align-items-center p-2 gap-2"
                                                href="<?php echo e(URL::to('/lang/change?lang=' . $languagelist->code)); ?>">
                                                <img src="<?php echo e(helper::image_path($languagelist->image)); ?>" alt=""
                                                    class="img-fluid lag-img w-25">
                                                <?php echo e($languagelist->name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- dekstop-tablet-mobile-language-dropdown-button-end-->
                <div class="lag-btn dropdown border-0 shadow-none login-lang">
                    <a class="btn btn-sm btn-primary header-btn-icon dropdown-toggle" type="button" id="dropdownMenuButton2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-half-stroke fs-6"></i>
                    </a>
                    <ul class="dropdown-menu bg-body-secondary shadow border-0 overflow-hidden p-0 <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>"
                        aria-labelledby="dropdownMenuButton2">
                        <li>
                            <a class="dropdown-item d-flex cursor-pointer align-items-center p-2 gap-2"
                                onclick="setLightMode()">
                                <i class="fa-light fa-lightbulb fs-6"></i>
                                <span>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex cursor-pointer align-items-center p-2 gap-2"
                                onclick="setDarkMode()">
                                <i class="fa-solid fa-moon fs-6"></i>
                                <span>Dark</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- dekstop-tablet-mobile-currency-dropdown-button-start-->
                <?php if(@helper::checkaddons('currency_settigns')): ?>
                    <?php if(helper::available_currency('')->count() > 1): ?>

                        <div class="position-relative">
                            <div class="lag-btn dropdown border-0 shadow-none login-lang">
                                <a class="btn btn-sm btn-primary header-btn-icon dropdown-toggle" href="#"
                                    role="button" data-bs-toggle ="dropdown" aria-expanded="false">
                                    <span class="fs-6 text-white">
                                        <?php echo e(session()->get('currency')); ?>

                                    </span>
                                </a>
                                <ul
                                    class="dropdown-menu rounded mt-1 p-0 bg-body-secondary shadow border-0 overflow-hidden <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                                    <?php $__currentLoopData = helper::available_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencylist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a class="dropdown-item text-dark d-flex align-items-center p-2 gap-2"
                                                href="<?php echo e(URL::to('/currency/change?currency=' . $currencylist['code'])); ?>">
                                                <p class="fs-7">
                                                    <?php echo e($currencylist['currency'] . '  ' . $currencylist['name']); ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- dekstop-tablet-mobile-currency-dropdown-button-end-->
                <div class="lag-btn dropwdown d-inline-block">
                    <button class="bg-transparent header-item p-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo e(helper::image_path(Auth::user()->image)); ?>">
                        <span class="d-none d-xxl-inline-block color-changer d-xl-inline-block ms-1"><?php echo e(Auth::user()->name); ?></span>
                        <i class="fa-regular fa-angle-down color-changer d-none d-xxl-inline-block d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu rounded-1 mt-1 p-0 bg-body-secondary shadow border-0 rounded-3">
                        <a href="<?php echo e(URL::to('admin/settings')); ?>#editprofile"
                            class="dropdown-item d-flex align-items-center gap-2 p-2">
                            <i class="fa-light fa-address-card fs-6"></i><?php echo e(trans('labels.edit_profile')); ?>

                        </a>
                        <a href="<?php echo e(URL::to('admin/settings')); ?>#changepasssword"
                            class="dropdown-item d-flex align-items-center gap-2 p-2">

                            <i class="fa-light fa-lock-keyhole fs-6"></i><?php echo e(trans('labels.change_password')); ?>


                        </a>

                        <a href="javascript:void(0)" onclick="statusupdate('<?php echo e(URL::to('/admin/logout')); ?>')"
                            class="dropdown-item d-flex align-items-center gap-2 p-2">
                            <i class="fa-light fa-right-from-bracket fs-6"></i><?php echo e(trans('labels.logout')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/layout/header.blade.php ENDPATH**/ ?>