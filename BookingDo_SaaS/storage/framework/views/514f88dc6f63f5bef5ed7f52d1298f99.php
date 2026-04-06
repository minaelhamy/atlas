<div id="whatsapp">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600 text-dark color-changer">
                        <?php echo e(trans('labels.whatsapp_message_settings')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(URL::to('admin/settings/order_message_update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label class="form-label"
                                        for="whatsapp_number"><?php echo e(trans('labels.whatsapp_number')); ?>

                                        <span class="text-danger">*</span></label>
                                    <input type="number" name="whatsapp_number" id="whatsapp_number"
                                        class="form-control" placeholder="<?php echo e(trans('labels.whatsapp_number')); ?>"
                                        value="<?php echo e(@whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_number); ?>"
                                        required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="form-label" for=""><?php echo e(trans('labels.whatsapp_chat')); ?></label>
                                    <div class="text-center">
                                        <input id="whatsapp_chat_on_off" type="checkbox" class="checkbox-switch"
                                            name="whatsapp_chat_on_off" value="1"
                                            <?php echo e(@whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_chat_on_off == 1 ? 'checked' : ''); ?>>
                                        <label for="whatsapp_chat_on_off" class="switch">
                                            <span
                                                class="<?php echo e(session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle'); ?>"><span
                                                    class="switch__circle-inner"></span></span>
                                            <span
                                                class="switch__left <?php echo e(session()->get('direction') == 2 ? 'pe-1' : 'ps-1'); ?>"><?php echo e(trans('labels.off')); ?></span>
                                            <span
                                                class="switch__right <?php echo e(session()->get('direction') == 2 ? 'ps-2' : 'pe-2'); ?>"><?php echo e(trans('labels.on')); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="form-label" for=""><?php echo e(trans('labels.mobile_view_display')); ?>

                                    </label>
                                    <div class="text-center">
                                        <input id="whatsapp_mobile_view_on_off" type="checkbox" class="checkbox-switch"
                                            name="whatsapp_mobile_view_on_off" value="1"
                                            <?php echo e(@whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_mobile_view_on_off == 1 ? 'checked' : ''); ?>>
                                        <label for="whatsapp_mobile_view_on_off" class="switch">
                                            <span
                                                class="<?php echo e(session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle'); ?>"><span
                                                    class="switch__circle-inner"></span></span>
                                            <span
                                                class="switch__left <?php echo e(session()->get('direction') == 2 ? 'pe-1' : 'ps-1'); ?>"><?php echo e(trans('labels.off')); ?></span>
                                            <span
                                                class="switch__right <?php echo e(session()->get('direction') == 2 ? 'ps-2' : 'pe-2'); ?>"><?php echo e(trans('labels.on')); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <p class="form-label">
                                        <?php echo e(trans('labels.whatsapp_chat_position')); ?>

                                    </p>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input form-check-input-secondary" type="radio"
                                            name="whatsapp_chat_position" id="chatradio" value="1" required
                                            <?php echo e(@whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_chat_position == '1' ? 'checked' : ''); ?>>
                                        <label for="chatradio"
                                            class="form-check-label"><?php echo e(trans('labels.left')); ?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input form-check-input-secondary" type="radio"
                                            name="whatsapp_chat_position" id="chatradio1" value="2" required
                                            <?php echo e(@whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_chat_position == '2' ? 'checked' : ''); ?>>
                                        <label for="chatradio1"
                                            class="form-check-label"><?php echo e(trans('labels.right')); ?></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="text-<?php echo e(session()->get('direction') == '2' ? 'start' : 'end'); ?>">
                            <button
                                <?php if(env('Environment') == 'sendbox'): ?> type="button" onclick="myFunction()" <?php else: ?> type="submit" <?php endif; ?>
                                class="btn btn-primary px-sm-4 <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_general_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"><?php echo e(trans('labels.save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/include/whatsapp_message/admin_setting_form.blade.php ENDPATH**/ ?>