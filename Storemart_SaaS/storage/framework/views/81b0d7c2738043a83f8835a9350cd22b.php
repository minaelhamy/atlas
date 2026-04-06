<?php
    if (Auth::user()->type == 4) {
        $role_id = Auth::user()->role_id;
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $role_id = '';
        $vendor_id = Auth::user()->id;
    }
    $user = App\Models\User::where('id', $vendor_id)->first();
?>
<ul class="navbar-nav">
    <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_dashboard') == 1 ? 'd-block' : 'd-none'); ?>">
        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/dashboard') ? 'active' : ''); ?>" aria-current="page"
            href="<?php echo e(URL::to('admin/dashboard')); ?>">
            <i class="fa-solid fa-house-user"></i> <span class=""><?php echo e(trans('labels.dashboards')); ?></span>
        </a>
    </li>

    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_addons_manager') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/apps*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/apps')); ?>" aria-expanded="false">
                <i class="fa fa-puzzle-piece"></i>
                <span class="nav-text d-flex justify-content-between w-100"><?php echo e(trans('labels.addons_manager')); ?></span>
                <span class="rainbowText float-right mr-1 mt-1">Premium</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_orders') == 1 || helper::check_menu($role_id, 'role_report') == 1 || helper::check_menu($role_id, 'role_pos') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.orders_management')); ?></h6>
        </li>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('pos')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();

                    if ($user->allow_without_subscription == 1) {
                        $pos = 1;
                    } else {
                        $pos = @$checkplan->pos;
                    }
                ?>
                <?php if($pos == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_pos') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/pos*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('admin/pos')); ?>">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.pos')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('pos')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_pos') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/pos*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('admin/pos')); ?>">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.pos')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_orders') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex d-flex <?php echo e(request()->is('admin/orders*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/orders')); ?>" aria-expanded="false">
                <i class="fa-solid fa-cart-shopping"></i><span class="nav-text "><?php echo e(trans('labels.orders')); ?></span>
            </a>
        </li>
        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_report') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex d-flex <?php echo e(request()->is('admin/report*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/report')); ?>" aria-expanded="false">
                <i class="fa-solid fa-chart-mixed"></i><span class="nav-text "><?php echo e(trans('labels.report')); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if(Auth::user()->type == 1 ||
            (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ||
            @helper::checkaddons('customer_login')): ?>
        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_customers') == 1 || helper::check_menu($role_id, 'role_vendors') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.user_management')); ?></h6>
        </li>
    <?php endif; ?>

    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_vendors') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>" aria-current="page"
                href="<?php echo e(URL::to('admin/users')); ?>">
                <i class="fa-solid fa-user-plus"></i> <span class=""><?php echo e(trans('labels.users')); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if(@helper::checkaddons('customer_login')): ?>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_customers') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/customers*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('admin/customers')); ?>">
                <i class="fa-solid fa-users"></i>
                <p class="w-100 d-flex justify-content-between">
                    <span class="nav-text "><?php echo e(trans('labels.customers')); ?></span>
                    <?php if(env('Environment') == 'sendbox'): ?>
                        <span class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                    <?php endif; ?>
                </p>
            </a>
        </li>
    <?php endif; ?>
    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_categories') == 1 || helper::check_menu($role_id, 'role_tax') == 1 || helper::check_menu($role_id, 'role_global_extras') == 1 || helper::check_menu($role_id, 'role_products') == 1 || helper::check_menu($role_id, 'role_import_product') == 1 || helper::check_menu($role_id, 'role_shipping_management') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.product_managment')); ?></h6>
        </li>
        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_categories') == 1 || helper::check_menu($role_id, 'role_tax') == 1 || helper::check_menu($role_id, 'role_global_extras') == 1 || helper::check_menu($role_id, 'role_products') == 1 || helper::check_menu($role_id, 'role_import_product') == 1 || helper::check_menu($role_id, 'role_shipping_management') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#products-menu" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="products-menu">
                <span class="d-flex"><i class="fa-solid fa-file-lines"></i>
                    <span class="multimenu-title"><?php echo e(trans('labels.products')); ?></span>
                </span>
            </a>
            <ul class="collapse" id="products-menu">
                <li
                    class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_categories') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/categories*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/categories')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.categories')); ?></span>
                    </a>
                </li>
                <li
                    class="av-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_tax') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded  <?php echo e(request()->is('admin/tax*') ? 'active' : ''); ?>" aria-current="page"
                        href="<?php echo e(URL::to('/admin/tax')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.tax')); ?></span>

                    </a>
                </li>
                <li
                    class="av-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_global_extras') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded  <?php echo e(request()->is('admin/extras*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('admin/extras')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.global_extras')); ?></span>
                    </a>
                </li>

                <li
                    class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_products') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/products') || request()->is('admin/products/add') || request()->is('admin/products/add') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/products')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.products')); ?>

                        </span>
                    </a>
                </li>
                <?php if(@helper::checkaddons('question_answer')): ?>
                    <li
                        class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_product_question_answer') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded <?php echo e(request()->is('admin/question_answer*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/question_answer')); ?>">
                            <span class="d-flex align-items-center multimenu-menu-indicator">
                                <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.product_question_answer')); ?>

                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <small class="badge bg-danger"><?php echo e(trans('labels.addon')); ?></small>
                                <?php endif; ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(@helper::checkaddons('product_import')): ?>
                    <li
                        class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_import_product') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded <?php echo e(request()->is('admin/products/import') || request()->is('admin/media*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/products/import')); ?>">
                            <span class="d-flex align-items-center multimenu-menu-indicator">
                                <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.product_upload')); ?>

                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <li
                    class="av-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_shipping_management') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded  <?php echo e(request()->is('admin/shipping*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('admin/shipping')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.shipping_management')); ?>

                        </span>
                    </a>
                </li>
            </ul>
        </li>

        <?php if(@helper::checkaddons('shopify')): ?>
            <li
                class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_shopify') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                    href="#shopify-menu" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="shopify-menu">
                    <i class="fa-brands fa-shopify"></i>
                    <div class="d-flex align-items-center gap-1 justify-content-between w-100">
                        <span class="multimenu-title"><?php echo e(trans('labels.shopify')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span class="badge badge bg-danger"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
                <ul class="collapse" id="shopify-menu">
                    <li class="nav-item ps-4 mb-1">
                        <a class="nav-link rounded <?php echo e(request()->is('admin/shopify-category*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/shopify-category')); ?>">
                            <span class="d-flex align-items-center multimenu-menu-indicator">
                                <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.shopify_category')); ?>

                            </span>
                        </a>
                    </li>
                    <li class="nav-item ps-4 mb-1">
                        <a class="nav-link rounded <?php echo e(request()->is('admin/shopify-products*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/shopify-products')); ?>">
                            <span class="d-flex align-items-center multimenu-menu-indicator">
                                <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.shopify_products')); ?>

                            </span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_sliders') == 1 || helper::check_menu($role_id, 'role_banner') == 1 || helper::check_menu($role_id, 'role_coupons') == 1 || helper::check_menu($role_id, 'role_top_deals') == 1 || helper::check_menu($role_id, 'role_firebase_notification') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.promotions')); ?></h6>
        </li>
        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_sliders') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/sliders*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/sliders')); ?>" aria-expanded="false">
                <i class="fa-solid fa-image"></i><span class="nav-text "><?php echo e(trans('labels.sliders')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_banner') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#banner-menu" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="banner-menu">
                <span class="d-flex">
                    <i class="fa-solid fa-image"></i>
                    <span class="nav-text"><?php echo e(trans('labels.banner')); ?></span>
                </span>
            </a>
            <ul class="collapse" id="banner-menu">
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/bannersection-1*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/bannersection-1')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.section-1')); ?>

                        </span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/bannersection-2*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/bannersection-2')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.section-2')); ?>

                        </span>
                    </a>
                </li>
            </ul>
        </li>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('coupon')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();

                    if ($user->allow_without_subscription == 1) {
                        $coupons = 1;
                    } else {
                        $coupons = @$checkplan->coupons;
                    }
                ?>
                <?php if($coupons == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_coupons') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/coupons*') ? 'active' : ''); ?>"
                            href="<?php echo e(URL::to('/admin/coupons')); ?>" aria-expanded="false">
                            <i class="fa-solid fa-badge-percent"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.coupons')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('coupon')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_coupons') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/coupons*') ? 'active' : ''); ?>"
                        href="<?php echo e(URL::to('/admin/coupons')); ?>" aria-expanded="false">
                        <i class="fa-solid fa-badge-percent"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.coupons')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(@helper::checkaddons('top_deals')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_top_deals') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/top_deals') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/top_deals')); ?>">
                    <i class="fa-solid fa-badge-percent"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.top_deals')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>

        <?php if(@helper::checkaddons('firebase_notification')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_firebase_notification') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/notification') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/notification')); ?>">
                    <i class="fa-solid fa-tags"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.firebase_notification')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>

    <?php endif; ?>

    <li
        class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_tax') == 1 || helper::check_menu($role_id, 'role_pricing_plans') == 1 || helper::check_menu($role_id, 'role_transaction') == 1 || helper::check_menu($role_id, 'role_payment_methods') == 1 || helper::check_menu($role_id, 'role_working_hours') == 1 || helper::check_menu($role_id, 'role_table') == 1 || helper::check_menu($role_id, 'role_cities') == 1 || helper::check_menu($role_id, 'role_areas') == 1 || helper::check_menu($role_id, 'role_store_categories') == 1 || helper::check_menu($role_id, 'role_custom_domains') == 1 ? 'd-block' : 'd-none'); ?>">
        <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.business_management')); ?></h6>
    </li>

    <?php if(@helper::checkaddons('subscription')): ?>
        <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_tax') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/tax*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/tax')); ?>">
                    <i class="fa-solid fa-magnifying-glass-dollar"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.tax')); ?></span>
                    </p>
                </a>
            </li>
        <?php endif; ?>
        <?php if($user->allow_without_subscription != 1): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_pricing_plans') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/plan*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('admin/plan')); ?>">
                    <i class="fa-solid fa-medal"></i>
                    <span class=""><?php echo e(trans('labels.pricing_plans')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_transaction') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/transaction*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('admin/transaction')); ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span class=""><?php echo e(trans('labels.transaction')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_payment_methods') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/payment') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('admin/payment')); ?>">
                <i class="fa-solid fa-money-check-dollar-pen"></i>
                <span class=""><?php echo e(trans('labels.payment_methods')); ?></span>
            </a>
        </li>
    <?php else: ?>
        <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_payment_methods') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/payment') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('admin/payment')); ?>">
                    <i class="fa-solid fa-money-check-dollar-pen"></i>
                    <span class=""><?php echo e(trans('labels.payment_methods')); ?></span>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_working_hours') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/time*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/time')); ?>" aria-expanded="false">
                <i class="fa-solid fa-business-time"></i>
                <span class="nav-text"><?php echo e(trans('labels.working_hours')); ?></span>
            </a>
        </li>

        <?php
            $delivery_type = explode('|', helper::appdata($vendor_id)->delivery_type);
        ?>
        <?php if(in_array('table', $delivery_type)): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_table') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/dinein*') ? 'active' : ''); ?>"
                    href="<?php echo e(URL::to('/admin/dinein')); ?>" aria-expanded="false">
                    <i class="fa-solid fa-utensils"></i><span class="nav-text "><?php echo e(trans('labels.table')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(helper::appdata($vendor_id)->product_type == 1): ?>
            <?php if(@helper::checkaddons('custom_status')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_custom_status') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/custom_status*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('admin/custom_status')); ?>">
                        <i class="fa-regular fa-clipboard-list-check"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.custom_status')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_cities') == 1 || helper::check_menu($role_id, 'role_areas') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#location" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="location">
                <span class="d-flex">
                    <i class="fa-sharp fa-solid fa-location-dot"></i>
                    <span class="multimenu-title"><?php echo e(trans('labels.location')); ?></span>
                </span>
            </a>
            <ul class="collapse" id="location">
                <li
                    class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_cities') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/countries*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/countries')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.cities')); ?></span>
                    </a>
                </li>
                <li
                    class="nav-item ps-4 mb-1 <?php echo e(helper::check_menu($role_id, 'role_areas') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/cities*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/cities')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.areas')); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_store_categories') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/store_categories*') ? 'active' : ''); ?>"
                href="<?php echo e(URL::to('/admin/store_categories')); ?>" aria-expanded="false">
                <i class="fa-sharp fa-solid fa-list"></i>
                <span class="nav-text "><?php echo e(trans('labels.store_categories')); ?></span>
            </a>
        </li>

        <?php if(@helper::checkaddons('custom_domain')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_custom_domains') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/custom_domain*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/custom_domain')); ?>">
                    <i class="fa-solid fa-link"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.custom_domains')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('custom_domain')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();
                    if ($user->allow_without_subscription == 1) {
                        $custom_domain = 1;
                    } else {
                        $custom_domain = @$checkplan->custom_domain;
                    }

                ?>
                <?php if($custom_domain == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_custom_domains') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/custom_domain*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/custom_domain')); ?>">
                            <i class="fa-solid fa-link"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.custom_domains')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('custom_domain')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_custom_domains') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/custom_domain*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/custom_domain')); ?>">
                        <i class="fa-solid fa-link"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.custom_domains')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_basic_settings') == 1 || helper::check_menu($role_id, 'role_blogs') == 1 || helper::check_menu($role_id, 'role_cms_pages') == 1 || helper::check_menu($role_id, 'role_who_we_are') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.website_settings')); ?></h6>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_basic_settings') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/basic_settings*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/basic_settings')); ?>">
                <i class="fa-solid fa-gears"></i>
                <span><?php echo e(trans('labels.basic_settings')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_who_we_are') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/whoweare*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/whoweare')); ?>">
                <i class="fa-solid fa-gears"></i>
                <span><?php echo e(trans('labels.who_we_are')); ?></span>
            </a>
        </li>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('blog')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();

                    if ($user->allow_without_subscription == 1) {
                        $blogs = 1;
                    } else {
                        $blogs = @$checkplan->blogs;
                    }
                ?>
                <?php if($blogs == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_blogs') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/blogs*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('admin/blogs')); ?>">
                            <i class="fa-solid fa-blog"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.blogs')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('blog')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_blogs') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/blogs*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('admin/blogs')); ?>">
                        <i class="fa-solid fa-blog"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.blogs')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(@helper::checkaddons('store_reviews')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_testimonials') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/testimonials*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/testimonials')); ?>">
                    <i class="fa-solid fa-comment-dots"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.testimonials')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_faqs') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/faqs*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/faqs')); ?>">
                <i class="fa-solid fa-question"></i>
                <span><?php echo e(trans('labels.faqs')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_cms_pages') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#pages" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="pages">
                <span class="d-flex"><i class="fa-solid fa-file-lines"></i>
                    <span class="multimenu-title"><?php echo e(trans('labels.cms_pages')); ?></span>
                </span>
            </a>
            <ul class="collapse" id="pages">
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/privacy-policy*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/privacy-policy')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.privacypolicy')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/terms-conditions*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/terms-conditions')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.terms')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/aboutus*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/aboutus')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.about')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/refund_policy') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/refund_policy')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.refund_policy')); ?></span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?>

    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <li
            class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_basic_settings') == 1 || helper::check_menu($role_id, 'role_how_it_works') == 1 || helper::check_menu($role_id, 'role_theme_images') == 1 || helper::check_menu($role_id, 'role_features') == 1 || helper::check_menu($role_id, 'role_promotional_banners') == 1 || helper::check_menu($role_id, 'role_blogs') == 1 || helper::check_menu($role_id, 'role_testimonials') == 1 || helper::check_menu($role_id, 'role_faqs') == 1 || helper::check_menu($role_id, 'role_cms_pages') == 1 || helper::check_menu($role_id, 'role_coupons') == 1 ? 'd-block' : 'd-none'); ?>">
            <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.landing_page')); ?></h6>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_basic_settings') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/basic_settings*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/basic_settings')); ?>">
                <i class="fa-solid fa-gears"></i>
                <span><?php echo e(trans('labels.basic_settings')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_how_it_works') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/how_it_works*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/how_it_works')); ?>">
                <i class="fa-regular fa-hourglass"></i>
                <span><?php echo e(trans('labels.how_it_works')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_theme_images') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/themes*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/themes')); ?>">
                <i class="fa-solid fa-palette"></i>
                <span><?php echo e(trans('labels.theme_images')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_features') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/features*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/features')); ?>">
                <i class="fa-solid fa-list"></i>
                <span><?php echo e(trans('labels.features')); ?></span>
            </a>
        </li>
        <li
            class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_promotional_banners') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/promotionalbanners*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/promotionalbanners')); ?>">
                <i class="fa-solid fa-bullhorn"></i>
                <span><?php echo e(trans('labels.promotional_banners')); ?></span>
            </a>
        </li>
        <?php if(@helper::checkaddons('blog')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_blogs') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/blogs') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/blogs')); ?>">
                    <i class="fa-solid fa-blog"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.blogs')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>

        <?php if(@helper::checkaddons('store_reviews')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_testimonials') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/testimonials') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/testimonials')); ?>">
                    <i class="fa-solid fa-comment-dots"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.testimonials')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_faqs') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link d-flex rounded <?php echo e(request()->is('admin/faqs*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('/admin/faqs')); ?>">
                <i class="fa-solid fa-question"></i>
                <span><?php echo e(trans('labels.faqs')); ?></span>
            </a>
        </li>


        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_cms_pages') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#pages" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="pages">
                <span class="d-flex"><i class="fa-solid fa-file-lines"></i>
                    <span class="multimenu-title"><?php echo e(trans('labels.cms_pages')); ?></span>
                </span>
            </a>
            <ul class="collapse" id="pages">
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/privacy-policy*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/privacy-policy')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.privacypolicy')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/terms-conditions*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/terms-conditions')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.terms')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/aboutus*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/aboutus')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.about')); ?></span>
                    </a>
                </li>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/refund_policy') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/refund_policy')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator">
                            <i class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.refund_policy')); ?></span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?>

    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <?php if(@helper::checkaddons('coupon')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_coupons') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/coupons*') ? 'active' : ''); ?>"
                    href="<?php echo e(URL::to('/admin/coupons')); ?>" aria-expanded="false">
                    <i class="fa-solid fa-badge-percent"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.coupons')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>
        
        <?php if(@helper::checkaddons('employee')): ?>
            <li
                class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 || helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.employee_management')); ?></h6>
            </li>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/roles*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/roles')); ?>">
                    <i class="fa-solid fa-user-secret"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.roles')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/employees*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/employees')); ?>">
                    <i class="fa fa-users"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.employees')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>


    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('employee')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();

                    if ($user->allow_without_subscription == 1) {
                        $role_management = 1;
                    } else {
                        $role_management = @$checkplan->role_management;
                    }

                ?>
                <?php if($role_management == 1): ?>
                    
                    <li
                        class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 || helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                        <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.employee_management')); ?>

                        </h6>
                    </li>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/roles*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/roles')); ?>">
                            <i class="fa-solid fa-user-secret"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.roles')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/employees*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/employees')); ?>">
                            <i class="fa fa-users"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.employees')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            
            <?php if(@helper::checkaddons('employee')): ?>
                <li
                    class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 || helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                    <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.employee_management')); ?></h6>
                </li>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_roles') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/roles*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/roles')); ?>">
                        <i class="fa-solid fa-user-secret"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.roles')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_employees') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link  d-flex rounded <?php echo e(request()->is('admin/employees*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/employees')); ?>">
                        <i class="fa fa-users"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.employees')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <li
        class="nav-item mt-3 <?php echo e(helper::check_menu($role_id, 'role_subscribers') == 1 || helper::check_menu($role_id, 'role_inquiries') == 1 || helper::check_menu($role_id, 'role_product_inquiry') == 1 || helper::check_menu($role_id, 'role_share') == 1 || helper::check_menu($role_id, 'role_whatsapp_settings') == 1 || helper::check_menu($role_id, 'role_telegram_settings') == 1 || helper::check_menu($role_id, 'role_settings') == 1 ? 'd-block' : 'd-none'); ?>">
        <h6 class="text-muted mb-2 fs-7 text-uppercase"><?php echo e(trans('labels.other')); ?></h6>
    </li>
    <li
        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_subscribers') == 1 ? 'd-block' : 'd-none'); ?>">
        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/subscribers*') ? 'active' : ''); ?>"
            aria-current="page" href="<?php echo e(URL::to('admin/subscribers')); ?>">
            <i class="fa-solid fa-envelope"></i>
            <span class=""><?php echo e(trans('labels.subscribers')); ?></span>
        </a>
    </li>
    <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_inquiries') == 1 ? 'd-block' : 'd-none'); ?>">
        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/inquiries*') ? 'active' : ''); ?>"
            aria-current="page" href="<?php echo e(URL::to('admin/inquiries')); ?>">
            <i class="fa-solid fa-solid fa-address-book"></i>
            <span class=""><?php echo e(trans('labels.inquiries')); ?></span>
        </a>
    </li>
    <?php if(Auth::user()->type == '2' || Auth::user()->type == 4): ?>
        <?php if(@helper::checkaddons('product_inquiry')): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_product_inquiry') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex align-items-center <?php echo e(request()->is('admin/product_inquiry*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/product_inquiry')); ?>">
                    <i class="fa-solid fa-solid fa-address-book"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.product_inquiry')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_share') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/share*') ? 'active' : ''); ?>"
                aria-current="page" href="<?php echo e(URL::to('admin/share')); ?>">
                <i class="fa-solid fa-share-from-square"></i>
                <span class=""><?php echo e(trans('labels.share')); ?></span>
            </a>
        </li>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('whatsapp_message')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();
                    if (@$user->allow_without_subscription == 1) {
                        $whatsapp_message = 1;
                    } else {
                        $whatsapp_message = @$checkplan->whatsapp_message;
                    }
                ?>
                <?php if($whatsapp_message == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_whatsapp_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded d-flex align-items-center <?php echo e(request()->is('admin/whatsapp_settings*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/whatsapp_settings')); ?>">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.whatsapp_settings')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('whatsapp_message')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_whatsapp_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex align-items-center <?php echo e(request()->is('admin/whatsapp_settings*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/whatsapp_settings')); ?>">
                        <i class="fa-brands fa-whatsapp fs-5"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.whatsapp_settings')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(@helper::checkaddons('subscription')): ?>
            <?php if(@helper::checkaddons('telegram_message')): ?>
                <?php
                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)->orderByDesc('id')->first();
                    if (@$user->allow_without_subscription == 1) {
                        $telegram_message = 1;
                    } else {
                        $telegram_message = @$checkplan->telegram_message;
                    }
                ?>
                <?php if($telegram_message == 1): ?>
                    <li
                        class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_telegram_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                        <a class="nav-link rounded d-flex align-items-center <?php echo e(request()->is('admin/telegram_settings*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/telegram_settings')); ?>">
                            <i class="fa-brands fa-telegram fs-5"></i>
                            <p class="w-100 d-flex justify-content-between">
                                <span class="nav-text "><?php echo e(trans('labels.telegram_settings')); ?></span>
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <span
                                        class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if(@helper::checkaddons('telegram_message')): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_telegram_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex align-items-center <?php echo e(request()->is('admin/telegram_settings*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/telegram_settings')); ?>">
                        <i class="fa-brands fa-telegram fs-5"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.telegram_settings')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(@helper::checkaddons('language')): ?>
        <?php if(
            (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)) &&
                helper::listoflanguage()->count() > 1): ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_language_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/language-settings*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/language-settings')); ?>">
                    <i class="fa-solid fa-language"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.language-settings')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php else: ?>
            <li
                class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_language_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/language-settings*') ? 'active' : ''); ?>"
                    aria-current="page" href="<?php echo e(URL::to('/admin/language-settings')); ?>">
                    <i class="fa-solid fa-language"></i>
                    <p class="w-100 d-flex justify-content-between">
                        <span class="nav-text "><?php echo e(trans('labels.language-settings')); ?></span>
                        <?php if(env('Environment') == 'sendbox'): ?>
                            <span
                                class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>

        <?php endif; ?>
    <?php endif; ?>
    <?php if(Auth::user()->type == '2' || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php if(@helper::checkaddons('currency_settigns')): ?>
            <?php if(helper::available_currency()->count() > 1): ?>
                <li
                    class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_currency_settings') == 1 ? 'd-block' : 'd-none'); ?>">
                    <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/currency-settings*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/currency-settings')); ?>">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <p class="w-100 d-flex justify-content-between">
                            <span class="nav-text "><?php echo e(trans('labels.currency-settings')); ?></span>
                            <?php if(env('Environment') == 'sendbox'): ?>
                                <span
                                    class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php elseif(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
        <li
            class="nav-item mb-2 fs-7 dropdown multimenu <?php echo e(helper::check_menu($role_id, 'role_currency_settings') == 1 ? 'd-block' : 'd-none'); ?>">
            <a class="nav-link collapsed rounded d-flex align-items-center justify-content-between dropdown-toggle mb-1"
                href="#currency_setting" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="currency_setting">
                <span class="d-flex"><i class="fa-solid  fa-dollar-sign"></i><span
                        class="multimenu-title"><?php echo e(trans('labels.currency-settings')); ?></span>
                    <?php if(env('Environment') == 'sendbox'): ?>
                        <span class="badge badge bg-danger float-right mr-1 mt-1"><?php echo e(trans('labels.addon')); ?></span>
                    <?php endif; ?>
                </span>
            </a>
            <ul class="collapse" id="currency_setting">
                <?php if(@helper::checkaddons('currency_settigns')): ?>
                    <li class="nav-item ps-4 mb-1">
                        <a class="nav-link rounded <?php echo e(request()->is('admin/currencys*') ? 'active' : ''); ?>"
                            aria-current="page" href="<?php echo e(URL::to('/admin/currencys')); ?>">
                            <span class="d-flex align-items-center multimenu-menu-indicator"><i
                                    class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.currency')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item ps-4 mb-1">
                    <a class="nav-link rounded <?php echo e(request()->is('admin/currency-settings*') ? 'active' : ''); ?>"
                        aria-current="page" href="<?php echo e(URL::to('/admin/currency-settings')); ?>">
                        <span class="d-flex align-items-center multimenu-menu-indicator"><i
                                class="fa-solid fa-circle-small"></i><?php echo e(trans('labels.currency-settings')); ?></span>
                    </a>
                </li>

            </ul>
        </li>
    <?php endif; ?>
    <li class="nav-item mb-2 fs-7 <?php echo e(helper::check_menu($role_id, 'role_settings') == 1 ? 'd-block' : 'd-none'); ?>">
        <a class="nav-link rounded d-flex <?php echo e(request()->is('admin/settings') ? 'active' : ''); ?>" aria-current="page"
            href="<?php echo e(URL::to('admin/settings')); ?>">
            <i class="fa-solid fa-gear"></i> <span class=""><?php echo e(trans('labels.settings')); ?></span>
        </a>
    </li>
</ul>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/layout/sidebarcommon.blade.php ENDPATH**/ ?>