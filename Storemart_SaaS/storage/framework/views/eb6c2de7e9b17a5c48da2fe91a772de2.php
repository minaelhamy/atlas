<div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-6" data-id="<?php echo e($plandata->id); ?>">
    <div class="card border-0 box-shadow h-100 <?php echo e($plan_id == $plandata->id ? 'plan-card-active' : 'border-0'); ?> handle">
        <div class="card-header bg-secondary sub-plan">
            <div class="d-flex justify-content-between">
                <h5 class="settings-color"><?php echo e($plandata->name); ?></h5>
                <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                    <a tooltip="<?php echo e(trans('labels.move')); ?>"><i class="fa-light fa-up-down-left-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h5 class="mb-1 settings-color color-changer"><?php echo e(helper::currency_formate($plandata->price, '')); ?>


                    <span class="fs-7 text-muted">/
                        <?php if($plandata->plan_type == 1): ?>
                            <?php if($plandata->duration == 1): ?>
                                <?php echo e(trans('labels.one_month')); ?>

                            <?php elseif($plandata->duration == 2): ?>
                                <?php echo e(trans('labels.three_month')); ?>

                            <?php elseif($plandata->duration == 3): ?>
                                <?php echo e(trans('labels.six_month')); ?>

                            <?php elseif($plandata->duration == 4): ?>
                                <?php echo e(trans('labels.one_year')); ?>

                            <?php elseif($plandata->duration == 5): ?>
                                <?php echo e(trans('labels.lifetime')); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if($plandata->plan_type == 2): ?>
                            <?php echo e($plandata->days); ?>

                            <?php echo e($plandata->days > 1 ? trans('labels.days') : trans('labels.day')); ?>

                        <?php endif; ?>

                    </span>
                </h5>
                <?php if($plandata->tax != null && $plandata->tax != ''): ?>
                    <small class="text-danger"><?php echo e(trans('labels.exclusive_taxes')); ?></small><br>
                <?php else: ?>
                    <small class="text-success"><?php echo e(trans('labels.inclusive_taxes')); ?></small> <br>
                <?php endif; ?>
                <small class="text-muted text-center"><?php echo e(Str::limit($plandata->description, 150)); ?></small>
            </div>
            <ul>

                <?php $features = ($plandata->features == null ? null : explode('|', $plandata->features));?>

                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2">
                        <?php echo e($plandata->order_limit == -1 ? trans('labels.unlimited') : $plandata->order_limit); ?>

                        <?php echo e($plandata->order_limit > 1 || $plandata->order_limit == -1 ? trans('labels.products') : trans('labels.product')); ?>

                    </span>
                </li>
                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2">
                        <?php echo e($plandata->appointment_limit == -1 ? trans('labels.unlimited') : $plandata->appointment_limit); ?>

                        <?php echo e($plandata->appointment_limit > 1 || $plandata->appointment_limit == -1 ? trans('labels.orders') : trans('labels.order')); ?>

                    </span>
                </li>
                <?php
                    $themes = [];
                    if ($plandata->themes_id != '' && $plandata->themes_id != null) {
                        $themes = explode('|', $plandata->themes_id);
                } ?>
                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2"><?php echo e(count($themes)); ?>

                        <?php echo e(count($themes) > 1 ? trans('labels.themes') : trans('labels.theme')); ?>

                        <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                            <a onclick="themeinfo('<?php echo e($plandata->id); ?>','<?php echo e($plandata->themes_id); ?>','<?php echo e($plandata->name); ?>')"
                                tooltip="<?php echo e(trans('labels.info')); ?>" class="cursor-pointer"> <i
                                    class="fa-regular fa-circle-info"></i> </a>
                        <?php endif; ?>
                    </span>
                </li>
                <?php if(@helper::checkaddons('coupon')): ?>
                    <?php if($plandata->coupons == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.coupons')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('custom_domain')): ?>
                    <?php if($plandata->custom_domain == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.custome_domain')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('blog')): ?>
                    <?php if($plandata->blogs == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.blogs')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('google_login')): ?>
                    <?php if($plandata->google_login == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.google_login')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('facebook_login')): ?>
                    <?php if($plandata->facebook_login == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.facebook_login')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('notification')): ?>
                    <?php if($plandata->sound_notification == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.sound_notification')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('whatsapp_message')): ?>
                    <?php if($plandata->whatsapp_message == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.whatsapp_message')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('telegram_message')): ?>
                    <?php if($plandata->telegram_message == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.telegram_message')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('vendor_app')): ?>
                    <?php if($plandata->vendor_app == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.vendor_app_available')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('user_app')): ?>
                    <?php if($plandata->customer_app == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.customer_app')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('pos')): ?>
                    <?php if($plandata->pos == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.pos')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('pwa')): ?>
                    <?php if($plandata->pwa == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.pwa')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('employee')): ?>
                    <?php if($plandata->role_management == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.role_management')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(@helper::checkaddons('pixel')): ?>
                    <?php if($plandata->pixel == 1): ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"><?php echo e(trans('labels.pixel')); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($features != null): ?>
                    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"> <?php echo e($feature); ?> </span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>

        </div>
        <div class="card-footer bg-transparent border-top-0 my-2 text-center">
            <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <?php if($plandata->is_available == 1): ?>
                        <a tooltip="<?php echo e(trans('labels.active')); ?>"
                            <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/plan/status_change-' . $plandata->id . '/2')); ?>')" <?php endif; ?>
                            class="btn btn-success hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>">
                            <i class="fas fa-check"></i>
                        </a>
                    <?php elseif($plandata->is_available == 2): ?>
                        <a tooltip="<?php echo e(trans('labels.inactive')); ?>"
                            <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/plan/status_change-' . $plandata->id . '/1')); ?>')" <?php endif; ?>
                            class="btn btn-danger hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>">
                            <i class="fas fa-close mx-1"></i>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(URL::to('admin/plan/edit-' . $plandata->id)); ?>"
                        class="btn btn-info hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                        tooltip="<?php echo e(trans('labels.edit')); ?>">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="javascript:void(0)"
                        class="btn btn-danger hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : ''); ?>"
                        <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/plan/delete-' . $plandata->id)); ?>')" <?php endif; ?>
                        tooltip="<?php echo e(trans('labels.delete')); ?>">
                        <i class="fa-regular fa-trash"></i>
                    </a>
                </div>
            <?php else: ?>
                <?php if($plan_id == $plandata->id): ?>
                    <?php if(@$data['original']['status'] == '2'): ?>
                        <?php if($plandata->price > 0): ?>
                            <?php if(@$plandata->duration == 5): ?>
                                <small
                                    class="text-success d-block"><span><?php echo e(@$data['original']['plan_message']); ?></span></small>
                            <?php else: ?>
                                <?php if(@$data['original']['plan_date'] > date('Y-m-d')): ?>
                                    <small class="text-dark d-block"><?php echo e(@$data['original']['plan_message']); ?>

                                        : <span
                                            class="text-success"><?php echo e($data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : ''); ?></span>
                                    </small>
                                <?php else: ?>
                                    <small class="text-dark d-block"><?php echo e(@$data['original']['plan_message']); ?>

                                        : <span
                                            class="text-danger"><?php echo e($data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : ''); ?></span>
                                    </small>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if(@$data['original']['showclick'] == 1): ?>
                                <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                                    class="btn btn-sm hov btn-primary d-block mt-2 <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if(@$data['original']['plan_date'] > date('Y-m-d')): ?>
                                <small class="text-dark d-block"><?php echo e(@$data['original']['plan_message']); ?>

                                    <span class="text-success">
                                        <?php echo e($data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : ''); ?>

                                    </span>
                                </small>
                                <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                                    class="btn btn-sm btn-primary hov d-block <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                            <?php else: ?>
                                <small class="text-dark d-block"><?php echo e(@$data['original']['plan_message']); ?>

                                    <span class="text-danger">
                                        <?php echo e($data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : ''); ?></span>
                                </small>
                                <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                                    class="btn btn-sm btn-primary hov d-block d-none <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php elseif(@$data['original']['status'] == '1'): ?>
                        <?php if(@$plandata->duration == 5): ?>
                            <small class="text-dark">
                                <span>
                                    <?php echo e(@$data['original']['plan_message']); ?>

                                </span>
                            </small>
                        <?php else: ?>
                            <?php if($data['original']['plan_date'] != ''): ?>
                                <small class="text-dark">
                                    <?php echo e(@$data['original']['plan_message']); ?>: <span
                                        class="text-success"><?php echo e($data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : ''); ?></span>
                                </small>
                                <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                                    class="btn btn-sm hov btn-primary d-block py-2 mt-1 <?php if($purchase_amount <= 0): ?> d-none <?php endif; ?> <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                            <?php else: ?>
                                <small class="text-success"><?php echo e(@$data['original']['plan_message']); ?></small>
                                <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                                    class="btn btn-sm hov btn-primary d-block py-2 mt-1 <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                <?php else: ?>
                    <?php if($plandata->price > 0): ?>
                        <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                            class="btn btn-sm btn-primary hov hov d-block py-2 <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                    <?php elseif((float) $purchase_amount > $plandata->price): ?>
                    <?php else: ?>
                        <a href="<?php echo e(URL::to('admin/plan/selectplan-' . $plandata->id)); ?>"
                            class="btn btn-sm btn-primary hov d-block py-2 <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.subscribe')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/plan/plancommon.blade.php ENDPATH**/ ?>