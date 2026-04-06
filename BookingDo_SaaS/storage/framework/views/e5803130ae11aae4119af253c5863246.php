<div
    class="d-flex justify-content-between align-items-center flex-wrap <?php echo e(str_contains(request()->url(), 'add') || str_contains(request()->url(), 'edit') ? 'mb-3' : ''); ?> ">
    <?php if(str_contains(request()->url(), 'add')): ?>
        <h5 class="text-capitalize fs-4 fw-600 color-changer"><?php echo e(trans('labels.add_new')); ?></h5>
    <?php endif; ?>
    <?php if(str_contains(request()->url(), 'edit')): ?>
        <h5 class="text-capitalize fs-4 fw-600 color-changer"><?php echo e(trans('labels.edit')); ?></h5>
    <?php endif; ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <?php if(request()->is('admin/users')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fs-4 fw-600 color-changer"><?php echo e(trans('labels.vendors')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/users/add') || str_contains(request()->url(), 'admin/users/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/users')); ?>" class="color-changer">
                        <?php echo e(trans('labels.vendors')); ?>

                    </a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/plan')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.pricing_plan')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/plan/add') || str_contains(request()->url(), 'admin/plan/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/plan')); ?>" class="color-changer"><?php echo e(trans('labels.pricing_plan')); ?> </a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/payment')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.payment')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/transaction')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.transactions')); ?><h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/payout')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.payout')); ?><h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.setting')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/basic_settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.basic_settings')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/whatsapp_settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.whatsapp_settings')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/telegram_settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.telegram_settings')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/pos')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.pos')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/categories')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.categories')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/categories/add') || str_contains(request()->url(), 'admin/categories/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/categories')); ?>"
                        class="color-changer"><?php echo e(trans('labels.categories')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/product-category')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.categories')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/product-category/add') || str_contains(request()->url(), 'admin/product-category/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/product-category')); ?>"
                        class="color-changer"><?php echo e(trans('labels.categories')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/services')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.services')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/services/add') || str_contains(request()->url(), 'admin/services/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/services')); ?>"
                        class="color-changer"><?php echo e(trans('labels.services')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/products')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.product_s')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/products/add') || str_contains(request()->url(), 'admin/products/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/products')); ?>"
                        class="color-changer"><?php echo e(trans('labels.product_s')); ?></a>
                </li>
            <?php endif; ?>
            
            <?php if(request()->is('admin/workinghours*')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.workinghours')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/theme_settings*')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.theme_settings')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/mobile_section')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.mobile_section')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/faqs')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.faqs')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/faqs/add') || str_contains(request()->url(), 'admin/faqs/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/faqs')); ?>" class="color-changer"><?php echo e(trans('labels.faqs')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/features')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.features')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/features/add') || str_contains(request()->url(), 'admin/features/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/features')); ?>"
                        class="color-changer"><?php echo e(trans('labels.features')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/roles')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.roles')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/roles/add') || str_contains(request()->url(), 'admin/roles/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/roles')); ?>" class="color-changer"><?php echo e(trans('labels.roles')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/employees')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.employees')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/employees/add') || str_contains(request()->url(), 'admin/employees/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/employees')); ?>"
                        class="color-changer"><?php echo e(trans('labels.employees')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/custom_status')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.custom_status')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/custom_status/add') || str_contains(request()->url(), 'admin/custom_status/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/custom_status')); ?>"
                        class="color-changer"><?php echo e(trans('labels.custom_status')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/tax')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.tax')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/tax/add') || str_contains(request()->url(), 'admin/tax/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/tax')); ?>" class="color-changer"><?php echo e(trans('labels.tax')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/promotionalbanners')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.promotional_banners')); ?>

                    </h5>
                </li>
            <?php elseif(request()->is('admin/promotionalbanners/add') || str_contains(request()->url(), 'admin/promotionalbanners/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/promotionalbanners')); ?>"
                        class="color-changer"><?php echo e(trans('labels.promotional_banners')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/testimonials')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.testimonials')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/testimonials/add') || str_contains(request()->url(), 'admin/testimonials/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/testimonials')); ?>"
                        class="color-changer"><?php echo e(trans('labels.testimonials')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/cities')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.cities')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/cities/add') || str_contains(request()->url(), 'admin/cities/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/cities')); ?>" class="color-changer"><?php echo e(trans('labels.cities')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/countries')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.countries')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/countries/add') || str_contains(request()->url(), 'admin/countries/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/countries')); ?>"
                        class="color-changer"><?php echo e(trans('labels.countries')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/promocode')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.coupons')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/promocode/add') || str_contains(request()->url(), 'admin/promocode/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/promocode')); ?>"
                        class="color-changer"><?php echo e(trans('labels.coupons')); ?></a>
                </li>
            <?php endif; ?>
            <?php
                $url = '';
            ?>
            <?php if(request()->is('admin/bannersection-1') ||
                    request()->is('admin/bannersection-2') ||
                    request()->is('admin/bannersection-3')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(@$title); ?></h5>
                </li>
            <?php elseif(
                (str_contains(request()->url(), 'add') || str_contains(request()->url(), 'edit')) &&
                    str_contains(request()->url(), 'bannersection')): ?>
                <li class="breadcrumb-item"><a href="<?php echo e($table_url); ?>"
                        class="color-changer"><?php echo e(@$title); ?></a></li>
            <?php endif; ?>
            <?php if(request()->is('admin/blogs')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.blogs')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/blogs/add') || str_contains(request()->url(), 'admin/blogs/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/blogs')); ?>" class="color-changer"><?php echo e(trans('labels.blogs')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/gallery')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.gallery')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/gallery/add') || str_contains(request()->url(), 'admin/gallery/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/gallery')); ?>"
                        class="color-changer"><?php echo e(trans('labels.gallery')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/choose_us')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.choose_us')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/choose_us/add') || str_contains(request()->url(), 'admin/choose_us/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/choose_us')); ?>"
                        class="color-changer"><?php echo e(trans('labels.choose_us')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/subscription_settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.subscription_settings')); ?>

                    </h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/bookings')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/bookings')); ?>">
                        <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.bookings')); ?></h5>
                    </a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/orders')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/orders')); ?>">
                        <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.order_s')); ?></h5>
                    </a>
                </li>
            <?php endif; ?>
           
            <?php if(request()->is('admin/currency-settings')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.currency-settings')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/currency-settings/add') ||
                    str_contains(request()->url(), 'admin/currency-settings/currency/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/currency-settings')); ?>"
                        class="color-changer"><?php echo e(trans('labels.currency-settings')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/currencys')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.currency')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/currencys/currency_add') ||
                    str_contains(request()->url(), 'admin/currencys/currency_edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/currency_add')); ?>"
                        class="color-changer"><?php echo e(trans('labels.currency')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/reports*')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.reports')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/orderreports*')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.reports')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/privacypolicy')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.privacy_policy')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/termscondition')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.terms_condition')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/aboutus')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.about_us')); ?></h5>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/custom_domain')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.custom_domain')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/custom_domain/add') || str_contains(request()->url(), 'admin/custom_domain/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/custom_domain')); ?>"
                        class="color-changer"><?php echo e(trans('labels.custom_domain')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/store_categories')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.store_categories')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/store_categories/add') || str_contains(request()->url(), 'admin/store_categories/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/store_categories')); ?>"
                        class="color-changer"><?php echo e(trans('labels.store_categories')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/shipping')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.shipping_management')); ?>

                    </h5>
                </li>
            <?php elseif(request()->is('admin/shipping/add') || str_contains(request()->url(), 'admin/shipping/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/shipping')); ?>"
                        class="color-changer"><?php echo e(trans('labels.shipping_management')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/how_it_works')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.how_it_works')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/how_it_works/add') || str_contains(request()->url(), 'admin/how_it_works/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/how_it_works')); ?>"
                        class="color-changer"><?php echo e(trans('labels.how_it_works')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/themes')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.theme_images')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/themes/add') || str_contains(request()->url(), 'admin/themes/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/themes')); ?>"
                        class="color-changer"><?php echo e(trans('labels.theme_images')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/customers')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.customers')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/customers/add') || str_contains(request()->url(), 'admin/customers/edit')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/customers')); ?>"
                        class="color-changer"><?php echo e(trans('labels.customers')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/apps')): ?>
                <li class="breadcrumb-item">
                    <h5 class="text-capitalize fw-600 fs-4 color-changer"><?php echo e(trans('labels.addons_manager')); ?></h5>
                </li>
            <?php elseif(request()->is('admin/apps/add')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/apps')); ?>"
                        class="color-changer"><?php echo e(trans('labels.addons_manager')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(request()->is('admin/calendar/add')): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(URL::to('/admin/calendar')); ?>"
                        class="color-changer"><?php echo e(trans('labels.calendar')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(str_contains(request()->url(), 'add')): ?>
                <li
                    class="breadcrumb-item active color-changer <?php echo e(session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''); ?>">
                    <?php echo e(trans('labels.add')); ?></li>
            <?php endif; ?>
            <?php if(str_contains(request()->url(), 'edit')): ?>
                <li
                    class="breadcrumb-item active color-changer <?php echo e(session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''); ?>">
                    <?php echo e(trans('labels.edit')); ?></li>
            <?php endif; ?>
            <?php if(str_contains(request()->url(), 'selectplan')): ?>
                <li class="breadcrumb-item active color-changer"><?php echo e(trans('labels.buy_now')); ?></li>
            <?php endif; ?>

            <?php if(str_contains(request()->url(), 'invoice')): ?>
                <h5 class="text-capitalize fw-600 fs-4">
                    <li class="breadcrumb-item active text-dark color-changer"><?php echo e(trans('labels.invoice')); ?></li>
                </h5>
            <?php endif; ?>

        </ol>
    </nav>

    <?php if(request()->is('admin/apps')): ?>
        <a href="<?php echo e(URL::to('admin/apps/add')); ?>" class="btn btn-secondary px-sm-4 d-flex">
            <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.install_update_addons')); ?></a>
    <?php endif; ?>
    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php if(request()->is('admin/custom_domain')): ?>
            <a href="<?php echo e(URL::to('admin/custom_domain/add')); ?>"
                class="btn btn-secondary px-sm-4 mt-2 mt-sm-0 col-sm-auto col-12 <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.request_custom_domain')); ?></a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(request()->is('admin/transaction')): ?>
        <form action="<?php echo e(URL::to('/admin/transaction')); ?> " method="get">
            <div class="row">
                <div class="col-12">
                    <div class="input-group gap-2 ps-0 justify-content-end">
                        <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                            <select class="form-select transaction-select" name="vendor">
                                <option value=""
                                    data-value="<?php echo e(URL::to('/admin/transaction?vendor=' . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate'))); ?>"
                                    selected><?php echo e(trans('labels.select')); ?></option>
                                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($vendor->id); ?>"
                                        data-value="<?php echo e(URL::to('/admin/transaction?vendor=' . $vendor->id . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate'))); ?>"
                                        <?php echo e(request()->get('vendor') == $vendor->id ? 'selected' : ''); ?>>
                                        <?php echo e($vendor->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>
                        <div class="input-group-append col-sm-auto col-12">
                            <input type="date" class="form-control rounded p-2" name="startdate"
                                value="<?php echo e(request()->get('startdate')); ?>">
                        </div>
                        <div class="input-group-append col-sm-auto col-12">
                            <input type="date" class="form-control rounded p-2" name="enddate"
                                value="<?php echo e(request()->get('enddate')); ?>">
                        </div>
                        <div class="input-group-append col-sm-auto col-12">
                            <button class="btn btn-primary w-100 rounded"
                                type="submit"><?php echo e(trans('labels.fetch')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <?php if(request()->is('admin/reports') || request()->is('admin/orderreports')): ?>
        <?php if(request()->is('admin/orderreports')): ?>
            <form action="<?php echo e(URL::to('/admin/orderreports')); ?>">
            <?php else: ?>
                <form action="<?php echo e(URL::to('/admin/reports')); ?>">
        <?php endif; ?>
        <div class="input-group gap-2 col-md-12 ps-0">
            <?php if(@helper::checkaddons('customer_login')): ?>
                <?php if($getcustomerslist->count() > 0): ?>
                    <div
                        class="input-group-append col-auto px-1 <?php echo e(Auth::user()->type == 4 ? (helper::check_menu(Auth::user()->role_id, 'role_customers') == 1 ? '' : 'd-none') : ''); ?>">
                        <select name="customer_id" class="form-select rounded">
                            <option value=""><?php echo e(trans('labels.select_customer')); ?></option>
                            <?php $__currentLoopData = $getcustomerslist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $getcustomer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($getcustomer->id); ?>"
                                    <?php echo e($getcustomer->id == @$_GET['customer_id'] ? 'selected' : ''); ?>>
                                    <?php echo e($getcustomer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="input-group-append col-sm-auto col-12">
                <input type="date" class="form-control rounded p-2" name="startdate"
                    value="<?php echo e(request()->get('startdate')); ?>" required="">
            </div>

            <div class="input-group-append col-sm-auto col-12">
                <input type="date" class="form-control rounded p-2" name="enddate"
                    value="<?php echo e(request()->get('enddate')); ?>" required="">
            </div>
            <div class="input-group-append col-sm-auto col-12">
                <button class="btn btn-primary px-sm-4 rounded w-100"
                    type="submit"><?php echo e(trans('labels.fetch')); ?></button>
            </div>
        </div>
        </form>
    <?php endif; ?>
    <?php if(str_contains(request()->url(), 'add') ||
            str_contains(request()->url(), 'edit') ||
            request()->is('admin/payment') ||
            request()->is('admin/transaction') ||
            request()->is('admin/payout') ||
            request()->is('admin/settings') ||
            request()->is('admin/whatsapp_settings') ||
            request()->is('admin/telegram_settings') ||
            request()->is('admin/workinghours*') ||
            request()->is('admin/bookings*') ||
            request()->is('admin/orders*') ||
            request()->is('admin/reports*') ||
            request()->is('admin/orderreports*') ||
            request()->is('admin/privacypolicy') ||
            request()->is('admin/termscondition') ||
            request()->is('admin/aboutus') ||
            request()->is('admin/custom_domain') ||
            str_contains(request()->url(), 'invoice') ||
            request()->is('admin/apps') ||
            request()->is('admin/theme_settings') ||
            request()->is('admin/mobile_section') ||
            request()->is('admin/choose_us') ||
            request()->is('admin/shipping') ||
            request()->is('admin/basic_settings') ||
            request()->is('admin/pos') ||
            (request()->is('admin/plan*') &&
                (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)))): ?>
        <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>" class="btn btn-secondary px-sm-4 d-none">
            <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
    <?php else: ?>
        <?php if(request()->is('admin/services')): ?>
            <div class="d-flex">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <!-- Bulk Delete Button -->
                    <?php if(@helper::checkaddons('bulk_delete')): ?>
                        <button id="bulkDeleteBtn"
                            <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>"
                        class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                        <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
                </div>
                <?php if(@helper::checkaddons('service_import')): ?>
                    <?php if($service->count() > 0): ?>
                        <a href="<?php echo e(URL::to('/admin/services/exportservices')); ?>"
                            class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_services', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?> mx-2"><?php echo e(trans('labels.export')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php elseif(request()->is('admin/products')): ?>
            <div class="d-flex">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <!-- Bulk Delete Button -->
                    <?php if(@helper::checkaddons('bulk_delete')): ?>
                        <button id="bulkDeleteBtn"
                            <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>"
                        class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                        <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
                </div>
                <?php if(@helper::checkaddons('product_import')): ?>
                    <?php if($product->count() > 0): ?>
                        <a href="<?php echo e(URL::to('/admin/products/exportproducts')); ?>"
                            class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?> mx-2"><?php echo e(trans('labels.export')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php elseif(request()->is('admin/customers')): ?>
            <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                 <div class="d-flex align-items-center" style="gap: 10px;">
                    <!-- Bulk Delete Button -->
                    <?php if(@helper::checkaddons('bulk_delete')): ?>
                        <button id="bulkDeleteBtn"
                            <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                        <i class="fa-regular fa-trash"></i>
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>"
                        class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                        <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
                 </div>
            <?php endif; ?>
        <?php elseif(request()->is('admin/currency-settings')): ?>
            <?php if(helper::checkaddons('currency_settigns')): ?>
                <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <!-- Bulk Delete Button -->
                        <?php if(@helper::checkaddons('bulk_delete')): ?>
                            <button id="bulkDeleteBtn"
                                <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                                <i class="fa-regular fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>"
                            class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                            <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php elseif(request()->is('admin/currencys')): ?>
            <?php if(helper::checkaddons('currency_settigns')): ?>
                <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <!-- Bulk Delete Button -->
                        <?php if(@helper::checkaddons('bulk_delete')): ?>
                            <button id="bulkDeleteBtn"
                                <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                                <i class="fa-regular fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo e(URL::to(request()->url() . '/currency_add')); ?>"
                            class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                            <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
         <div class="d-flex align-items-center" style="gap: 10px;">
            <!-- Bulk Delete Button -->
            <?php if(@helper::checkaddons('bulk_delete')): ?>
                <button id="bulkDeleteBtn"
                    <?php if(env('Environment')=='sendbox' ): ?> onclick="myFunction()" <?php else: ?> onclick="deleteSelected('<?php echo e(URL::to(request()->url(). '/bulk_delete')); ?>')" <?php endif; ?> class="btn btn-danger hov btn-sm d-none d-flex" tooltip="<?php echo e(trans('labels.delete')); ?>">
                    <i class="fa-regular fa-trash"></i>
                </button>
            <?php endif; ?>

            <a href="<?php echo e(URL::to(request()->url() . '/add')); ?>"
                class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access($module, Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
         </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/breadcrumb/breadcrumb.blade.php ENDPATH**/ ?>