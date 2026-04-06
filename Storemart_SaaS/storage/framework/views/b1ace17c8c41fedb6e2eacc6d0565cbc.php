<nav class="navbar navbar-expand-lg header sticky-top">
    <div class="container">

        <div class="d-flex gap-2 align-items-center">
            <div class="d-xl-none">
                <a class="text-white" data-bs-toggle="offcanvas" href="#footersidebar" role="button"
                    aria-controls="footersidebar">
                    <i class="fa-solid fa-bars fs-3"></i>
                </a>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function(event) {
                    if (localStorage.getItem('theme') === 'dark') {
                        var logo = "<?php echo e(helper::image_path(helper::appdata('')->darklogo)); ?>";
                    } else {
                        var logo = "<?php echo e(helper::image_path(helper::appdata('')->logo)); ?>";
                    }
                    $('#logoimage').attr('src', logo);
                });
            </script>

            <a href="<?php echo e(URL::to('/')); ?>" class="navbar-brand">
                <img src="" id="logoimage" height="50" alt="">
            </a>
        </div>
        <div class="d-none d-xl-block">
            <div class="collapse navbar-collapse gap-2" id="navbarSupportedContent">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item dropdown px-3">
                        <a class="nav-link text-white fw-500 active" href="<?php echo e(URL::to('/')); ?>" role="button">
                            <?php echo e(trans('landing.home')); ?>

                        </a>
                    </li>
                    <li class="nav-item dropdown px-3">
                        <a class="nav-link text-white fw-500" href="<?php echo e(URL::to('/#features')); ?>" role="button">
                            <?php echo e(trans('landing.features')); ?>

                        </a>
                    </li>
                    <?php if(helper::storedata()->count() > 0): ?>
                        <?php if(@helper::checkaddons('subscription')): ?>
                            <li class="nav-item dropdown px-3">
                                <a class="nav-link text-white fw-500" href="<?php echo e(URL::to('/#our-stores')); ?>"
                                    role="button">
                                    <?php echo e(trans('landing.our_stores')); ?>

                                </a>
                            </li>
                            <?php if(is_countable(@$planlist) && count(@$planlist) > 0): ?>
                                <li class="nav-item dropdown px-3">
                                    <a class="nav-link text-white fw-500" href="<?php echo e(URL::to('/#pricing-plans')); ?>"
                                        role="button">
                                        <?php echo e(trans('landing.pricing_plan')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(@helper::checkaddons('blog')): ?>
                        <?php if(helper::getblogs(1)->count() > 0): ?>
                            <li class="nav-item dropdown px-3">
                                <a class="nav-link text-white fw-500" href="<?php echo e(URL::to('/#blogs')); ?>" role="button">
                                    <?php echo e(trans('landing.blogs')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li class="nav-item dropdown px-3">
                        <a class="nav-link text-white fw-500" href="<?php echo e(URL::to('/#contact-us')); ?>" role="button">
                            <?php echo e(trans('landing.contact_us')); ?>

                        </a>
                    </li>
                </ul>
                <div class="d-flex gap-3 align-items-center">
                    <?php if(helper::available_language('')->count() > 1): ?>
                        <?php if(@helper::checkaddons('language')): ?>
                            <div class="lag-btn dropdown lag-btn rounded-2">
                                <a class="p-0 border-0 rounded-1 language-drop" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-globe fs-5"></i>
                                </a>
                                <ul
                                    class="dropdown-menu shadow p-0 border-0 mt-2 bg-body-secondary <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                                    <?php $__currentLoopData = helper::available_language(''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languagelist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a class="dropdown-item p-2 d-flex gap-2 align-items-center"
                                                href="<?php echo e(URL::to('/lang/change?lang=' . $languagelist->code)); ?>">
                                                <img src="<?php echo e(helper::image_path($languagelist->image)); ?>"
                                                    alt="" class="lag-img">
                                                <?php echo e($languagelist->name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="dropdown lag-btn">
                        <a class="p-0 border-0 rounded-1 language-drop" type="button" id="dropdownMenuButton2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-half-stroke fs-5"></i>
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
                    <?php if(helper::available_currency('')->count() > 1): ?>
                        <?php if(@helper::checkaddons('currency_settigns')): ?>
                            <div class="lag-btn dropdown lag-btn rounded-2">
                                <a class="p-0 border-0 rounded-1 language-drop" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="fs-5 ">
                                        <?php echo e(session()->get('currency')); ?>

                                    </span>
                                </a>
                                <ul
                                    class="dropdown-menu shadow p-0 border-0 mt-2 bg-body-secondary <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                                    <?php $__currentLoopData = helper::available_currency(''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencylist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a class="dropdown-item p-2 d-flex gap-2 align-items-center"
                                                href="<?php echo e(URL::to('/currency/change?currency=' . $currencylist['code'])); ?>">
                                                <p class="fs-13">
                                                    <?php echo e($currencylist['currency'] . '  ' . $currencylist['name']); ?>

                                                </p>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?php echo e(URL::to('/admin')); ?>" target="_blank"
                        class="btn-secondary py-2 px-3 fw-500 text-center w-100 fs-7 m-0 btn-class rounded-2">
                        <?php echo e(trans('landing.get_started')); ?></a>
                </div>
            </div>
        </div>
        <div class=" d-flex gap-3 d-xl-none">
            <?php if(helper::available_language('')->count() > 1): ?>
                <?php if(@helper::checkaddons('language')): ?>
                    <div class="lag-btn dropdown lag-btn rounded-2">
                        <a class="p-0 border-0 rounded-1 language-drop" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-globe fs-4"></i>
                        </a>
                        <ul
                            class="dropdown-menu shadow p-0 border-0 mt-2 bg-body-secondary <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                            <?php $__currentLoopData = helper::available_language(''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languagelist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item p-2 d-flex gap-2 align-items-center"
                                        href="<?php echo e(URL::to('/lang/change?lang=' . $languagelist->code)); ?>">
                                        <img src="<?php echo e(helper::image_path($languagelist->image)); ?>" alt=""
                                            class="lag-img">
                                        <?php echo e($languagelist->name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="dropdown lag-btn">
                <a class="p-0 border-0 rounded-1 language-drop" type="button" id="dropdownMenuButton2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-circle-half-stroke fs-4"></i>
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
            <?php if(helper::available_currency('')->count() > 1): ?>
                <?php if(@helper::checkaddons('currency_settigns')): ?>
                    <div class="lag-btn dropdown lag-btn rounded-2">
                        <a class="p-0 border-0 rounded-1 language-drop" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="fs-4">
                                <?php echo e(session()->get('currency')); ?>

                            </span>
                        </a>
                        <ul
                            class="dropdown-menu shadow p-0 border-0 mt-2 bg-body-secondary <?php echo e(session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr'); ?>">
                            <?php $__currentLoopData = helper::available_currency(''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencylist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item p-2 d-flex gap-2 align-items-center"
                                        href="<?php echo e(URL::to('/currency/change?currency=' . $currencylist['code'])); ?>">
                                        <p class="fs-7">
                                            <?php echo e($currencylist['currency'] . '  ' . $currencylist['name']); ?>

                                        </p>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php echo $__env->make('cookie-consent::index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            // Remove 'active' class from all links
            document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));

            // Add 'active' class to the clicked link
            this.classList.add('active');
        });
    });
</script>

<div class="offcanvas <?php echo e(session()->get('direction') == 2 ? 'offcanvas-end' : 'offcanvas-start'); ?>" tabindex="-1"
    id="footersidebar" aria-labelledby="footersidebarLabel">
    <div class="offcanvas-header d-flex justify-content-between align-items-center bg-black">
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                if (localStorage.getItem('theme') === 'dark') {
                    var logo = "<?php echo e(helper::image_path(helper::appdata('')->darklogo)); ?>";
                } else {
                    var logo = "<?php echo e(helper::image_path(helper::appdata('')->logo)); ?>";
                }
                $('#logoimage').attr('src', logo);
            });
        </script>
        <img src="" id="logoimage" height="35px" alt="">
        <button type="button"
            class="text-white border-0 bg-transparent d-flex justify-content-center align-items-center m-0"
            data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fs-4 fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-add list-group-flush border-bottom">
            <li
                class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                    href="<?php echo e(URL::to('/')); ?>">
                    <i class="fa-solid fa-circle-dot fs-7"></i>
                    <?php echo e(trans('landing.home')); ?>

                </a>
            </li>
            <li
                class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                    href="<?php echo e(URL::to('/#features')); ?>">
                    <i class="fa-solid fa-circle-dot fs-7"></i>
                    <?php echo e(trans('landing.features')); ?>

                </a>
            </li>
            <?php if(helper::storedata()->count() > 0): ?>
                <?php if(@helper::checkaddons('subscription')): ?>
                    <li
                        class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                        <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                            href="<?php echo e(URL::to('/#our-stores')); ?>">
                            <i class="fa-solid fa-circle-dot fs-7"></i>
                            <?php echo e(trans('landing.our_stores')); ?>

                        </a>
                    </li>
                    <?php if(is_countable(@$planlist) && count(@$planlist) > 0): ?>
                        <li
                            class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                            <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                                href="<?php echo e(URL::to('/#pricing-plans')); ?>">
                                <i class="fa-solid fa-circle-dot fs-7"></i>
                                <?php echo e(trans('landing.pricing_plan')); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if(@helper::checkaddons('blog')): ?>
                <?php if(helper::getblogs(1)->count() > 0): ?>
                    <li
                        class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                        <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                            href="<?php echo e(URL::to('/#blogs')); ?>">
                            <i class="fa-solid fa-circle-dot fs-7"></i>
                            <?php echo e(trans('landing.blogs')); ?>

                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <li
                class="list-group-item bg-transparent px-0 py-3 <?php echo e(session()->get('direction') == 2 ? 'pe-3' : 'ps-3'); ?>">
                <a class="fs-7 fw-500 d-flex gap-2 align-items-center text-dark color-changer"
                    href="<?php echo e(URL::to('/#contact-us')); ?>">
                    <i class="fa-solid fa-circle-dot fs-7"></i>
                    <?php echo e(trans('landing.contact_us')); ?>

                </a>
            </li>
        </ul>
    </div>
    <div class="offcanvas-footer bg-black p-2">
        <h5 class="fs-8 text-center text-white m-0"><?php echo e(helper::appdata('')->copyright); ?></h5>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/landing/layout/header.blade.php ENDPATH**/ ?>