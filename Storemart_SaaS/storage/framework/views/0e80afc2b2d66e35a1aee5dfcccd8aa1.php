<?php $__env->startSection('content'); ?>
    <?php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    ?>
    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php if(@helper::otherappdata(1)->notice_on_off == 1): ?>
            <div class="card mb-3 notice_card border-0 box-shadow">
                <div class="card-body">
                    <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
                        <div class="d-flex justify-content-between col-12 col-sm-auto">
                            <div class="alert-icons rgb-danger-light col-auto">
                                <i class="fa-regular fa-circle-exclamation text-danger"></i>
                            </div>
                            <div class="d-sm-none">
                                <div class="close-button cursor-pointer" id="close-btn3">
                                    <i class="fa-solid fa-xmark text-danger"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <div class="d-flex gap-2 align-items-center mb-2 justify-content-between">
                                <h6 class="line-2 color-changer fs-17">
                                    <?php echo e(@helper::otherappdata(1)->notice_title); ?>

                                </h6>
                                <div class="d-sm-block d-none">
                                    <div class="close-button cursor-pointer" id="close-btn2">
                                        <i class="fa-solid fa-xmark text-danger"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted fs-13 m-0">
                                <?php echo e(@helper::otherappdata(1)->notice_description); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="d-flex mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4"><?php echo e(trans('labels.dashboard')); ?></h5>
    </div>
    <div class="row g-3 mb-3">
        <?php
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $user = App\Models\User::where('id', $vendor_id)->first();
        ?>
        <div class="col-xl-6 col-12">
            <div class="row g-3">
                <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                    <div class="col-md-6">
                        <div class="card border-0 rgb-secondary-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-secondary">
                                        <i class="fa-regular fa-user fs-5"></i>
                                    </span>
                                    <span class="<?php echo e(session()->get('direction') == '2' ? 'text-start' : 'text-end'); ?>">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1"><?php echo e(trans('labels.users')); ?></p>
                                        <h5 class="color-changer fw-600"><?php echo e($totalvendors); ?></h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 rgb-info-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-info">
                                        <i class="fa-regular fa-medal fs-5"></i>
                                    </span>
                                    <span class="<?php echo e(session()->get('direction') == 2 ? 'text-start' : 'text-end'); ?>">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1"><?php echo e(trans('labels.pricing_plans')); ?></p>
                                        <h5 class="color-changer fw-600"><?php echo e($totalplans); ?></h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                    <div class="col-md-6">
                        <div class="card border-0 rgb-secondary-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-secondary">
                                        <i class="fa-solid fa-list-timeline fs-5"></i>
                                    </span>
                                    <span class="<?php echo e(session()->get('direction') == '2' ? 'text-start' : 'text-end'); ?>">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1"><?php echo e(trans('labels.products')); ?></p>
                                        <h5 class="color-changer fw-600"><?php echo e($totalvendors); ?></h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 box-shadow rgb-info-light h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-info">
                                        <i class="fa-regular fa-medal fs-5"></i>
                                    </span>
                                    <span class="<?php echo e(session()->get('direction') == '2' ? 'text-start' : 'text-end'); ?>">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1"><?php echo e(trans('labels.current_plan')); ?></p>
                                        <?php if(!empty($currentplanname)): ?>
                                            <h5 class="color-changer fw-600"> <?php echo e(@$currentplanname->name); ?> </h4>
                                            <?php else: ?>
                                                <i class="fa-regular fa-exclamation-triangle m-0 text-muted"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <div class="card rgb-dark-light border-0 box-shadow h-100">
                        <div class="card-body">
                            <div class="dashboard-card">
                                <span class="card-icon bg-dark">
                                    <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                                        <i class="fa-solid fa-ballot-check fs-5"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-cart-shopping fs-5"></i>
                                    <?php endif; ?>
                                </span>
                                <span class="<?php echo e(session()->get('direction') == 2 ? 'text-start' : 'text-end'); ?>">
                                    <p class="text-dark color-changer fs-15 fw-500 mb-1">
                                        <?php echo e(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.transaction') : trans('labels.orders')); ?>

                                    </p>
                                    <h5 class="color-changer fw-600"><?php echo e($totalorders); ?></h5>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card rgb-danger-light border-0 box-shadow h-100">
                        <div class="card-body">
                            <div class="dashboard-card">
                                <span class="card-icon bg-danger">
                                    <i class="fa-regular fa-money-bill-1-wave fs-5"></i>
                                </span>
                                <span class="<?php echo e(session()->get('direction') == 2 ? 'text-start' : 'text-end'); ?>">
                                    <p class="text-dark color-changer fs-15 fw-500 mb-1"><?php echo e(trans('labels.revenue')); ?></p>
                                    <h5 class="color-changer fw-600">
                                        <?php echo e(helper::currency_formate($totalrevenue, $vendor_id)); ?></h4>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card border-0 box-shadow h-100 fixed-bg-card-changer">
                <div class="card-body">

                    <div class="d-flex flex-wrap justify-content-sm-between justify-content-center gap-2">
                        <div
                            class="col-xxl-8 col-xl-7 col-lg-8 col-md-8 col-sm-7 d-flex flex-column gap-2 justify-content-center align-items-start">
                            <h5 class="text-dark fw-600 d-flex gap-2 align-items-center">
                                <img src="<?php echo e(helper::image_path(@Auth::user()->image)); ?>"
                                    class="object border rounded-circle dasbord-img" alt="">
                                <small class="text-dark color-changer"><?php echo e(@Auth::user()->name); ?></small>
                            </h5>
                            <p class="text-muted fs-7 m-0 line-3"><?php echo e(trans('labels.dashboard_description')); ?></p>
                            <div class="dropdown">
                                <a class="btn btn-secondary fs-7 text-light fw-500 dropdown-toggle" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-plus"></i> <?php echo e(trans('labels.quick_add')); ?>

                                </a>
                                <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                                    <ul class="dropdown-menu fw-500 fs-7 text-dark">
                                        <li><a class="dropdown-item py-2 <?php echo e(helper::check_menu(@Auth::user()->role_id, 'role_products') == 1 ? 'd-block' : 'd-none'); ?>"
                                                href="<?php echo e(URL::to('/admin/products')); ?>"><?php echo e(trans('labels.products')); ?></a>
                                        </li>
                                        <li><a class="dropdown-item py-2 <?php echo e(helper::check_menu(@Auth::user()->role_id, 'role_categories') == 1 ? 'd-block' : 'd-none'); ?>"
                                                href="<?php echo e(URL::to('/admin/categories')); ?>"><?php echo e(trans('labels.categories')); ?>

                                            </a></li>
                                        <li><a class="dropdown-item py-2 <?php echo e(helper::check_menu(@Auth::user()->role_id, 'role_settings') == 1 ? 'd-block' : 'd-none'); ?>"
                                                href="<?php echo e(URL::to('/admin/basic_settings')); ?>"><?php echo e(trans('labels.basic_settings')); ?></a>
                                        </li>
                                    </ul>
                                <?php else: ?>
                                    <ul class="dropdown-menu fw-500 p-0 bg-body-secondary fs-7 overflow-hidden">
                                        <li><a class="dropdown-item py-2"
                                                href="<?php echo e(URL::to('admin/users')); ?>"><?php echo e(trans('labels.restaurants')); ?></a>
                                        </li>
                                        <li><a class="dropdown-item py-2"
                                                href="<?php echo e(URL::to('admin/plan')); ?>"><?php echo e(trans('labels.pricing_plans')); ?>

                                            </a></li>
                                        <li><a class="dropdown-item py-2"
                                                href="<?php echo e(URL::to('/admin/basic_settings')); ?>"><?php echo e(trans('labels.basic_settings')); ?></a>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
                            <?php
                                if ($user->custom_domain == null) {
                                    $url = URL::to('/' . $user->slug);
                                } else {
                                    $url = 'https://' . $user->custom_domain;
                                }
                            ?>
                            <div
                                class="col-xxl-3 col-xl-4 mt-2 mt-sm-0 col-lg-3 col-md-3 col-sm-5 gap-2 d-flex flex-column justify-content-center align-items-center">
                                <img src="https://qrcode.tec-it.com/API/QRCode?data=<?php echo e($url); ?>&choe=UTF-8"
                                    class="object quer-code" alt="">
                                <div class="d-flex mt-sm-2">
                                    <button class="btn btn-primary fw-500 fs-7" id="copyButton">
                                        <i class="fa-regular fa-clone"></i> <?php echo e(trans('labels.copy_link')); ?>

                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-8 mb-3">
            <div class="card border-0 box-shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3 border-bottom pb-3 justify-content-between">
                        <h5 class="card-title color-changer m-0"><?php echo e(trans('labels.revenue')); ?></h5>
                        <select class="form-select form-select-sm w-auto" id="revenueyear"
                            data-url="<?php echo e(URL::to('/admin/dashboard')); ?>">
                            <?php if(count($revenue_years) > 0 && !in_array(date('Y'), array_column($revenue_years->toArray(), 'year'))): ?>
                                <option value="<?php echo e(date('Y')); ?>" selected><?php echo e(date('Y')); ?></option>
                            <?php endif; ?>
                            <?php $__empty_1 = true; $__currentLoopData = $revenue_years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revenue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($revenue->year); ?>" <?php echo e(date('Y') == $revenue->year ? 'selected' : ''); ?>>
                                    <?php echo e($revenue->year); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" selected disabled><?php echo e(trans('labels.select')); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <canvas id="revenuechart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 box-shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <h5 class="card-title color-changer m-0">
                            <?php echo e(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.users') : trans('labels.orders')); ?>

                        </h5>
                        <select class="form-select form-select-sm w-auto" id="doughnutyear"
                            data-url="<?php echo e(request()->url()); ?>">
                            <?php if(count($doughnut_years) > 0 && !in_array(date('Y'), array_column($doughnut_years->toArray(), 'year'))): ?>
                                <option value="<?php echo e(date('Y')); ?>" selected><?php echo e(date('Y')); ?></option>
                            <?php endif; ?>
                            <?php $__empty_1 = true; $__currentLoopData = $doughnut_years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $useryear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($useryear->year); ?>"
                                    <?php echo e(date('Y') == $useryear->year ? 'selected' : ''); ?>><?php echo e($useryear->year); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" selected disabled><?php echo e(trans('labels.select')); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <canvas id="doughnut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)): ?>
        <?php
            $ran = [
                'gradient-1',
                'gradient-2',
                'gradient-3',
                'gradient-4',
                'gradient-5',
                'gradient-6',
                'gradient-7',
                'gradient-8',
                'gradient-9',
            ];
        ?>
        <div class="row g-3 mb-3">
            <div class="col-xl-6">
                <div class="card border-0 box-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title pb-3 color-changer border-bottom"><?php echo e(trans('labels.top_products')); ?></h5>
                        <div class="table-responsive" id="table-items">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.image')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.item_name')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.category')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.orders')); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if(count($topitems) > 0): ?>
                                        <?php $__currentLoopData = @$topitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="fs-7 fw-500 text-dark align-middle">
                                                <td>
                                                    <img src="<?php echo e(Helper::image_path($row['product_image']->image)); ?>"
                                                        class="rounded hw-50 object" alt="">
                                                </td>
                                                <td>
                                                    <a
                                                        href="<?php echo e(URL::to('admin/products/edit-' . $row->slug)); ?>" class="td_a"><?php echo e($row->item_name); ?></a>
                                                </td>
                                                <td><?php echo e(@$row['category_info']->name); ?></td>
                                                <td>
                                                    <?php
                                                        $per =
                                                            $getorderdetailscount > 0
                                                                ? ($row->item_order_counter * 100) /
                                                                    $getorderdetailscount
                                                                : 0;
                                                    ?>
                                                    <?php echo e(number_format($per, 2)); ?>%
                                                    <div class="progress h-10-px">
                                                        <div class="progress-bar gradient-6 <?php echo e($ran[array_rand($ran, 1)]); ?>"
                                                            style="width: <?php echo e($per); ?>%;" role="progressbar">
                                                            <span class="sr-only"><?php echo e($per); ?>%
                                                                <?php echo e(trans('labels.orders')); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 box-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title pb-3 color-changer border-bottom"><?php echo e(trans('labels.top_customers')); ?></h5>
                        <div class="table-responsive" id="table-users">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.image')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.customer_info')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.customer_email')); ?></th>
                                        <th class="fs-15 fw-500"><?php echo e(trans('labels.orders')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php if(count($topusers) > 0): ?>
                                        <?php $__currentLoopData = @$topusers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="fs-7 fw-500 text-dark align-middle">
                                                <td>
                                                    <img src="<?php echo e(Helper::image_path($user->profile_image)); ?>"
                                                        class="rounded hw-50 object" alt="">
                                                </td>
                                                <td>
                                                    <div class="fs-7 fw-500 td_a">
                                                        <p><?php echo e($user->name); ?></p>
                                                        <p><?php echo e($user->mobile); ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo e($user->email); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(number_format($per, 2)); ?>%
                                                    <div class="progress h-10-px">
                                                        <div class="progress-bar <?php echo e($ran[array_rand($ran, 1)]); ?>"
                                                            style="width: <?php echo e($per); ?>%;" role="progressbar">
                                                            <span class="sr-only"><?php echo e($per); ?>%
                                                                <?php echo e(trans('labels.orders')); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <h5 class="card-title color-changer pb-3 border-bottom">
                        <?php echo e(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.today_transaction') : trans('labels.processing_orders')); ?>

                    </h5>
                    <div class="table-responsive">
                        <?php if(Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)): ?>
                            <?php echo $__env->make('admin.dashboard.admintransaction', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make('admin.orders.orderstable', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            var urlToCopy = "<?php echo e(@$url); ?>";
            var tempInput = document.createElement('input');
            tempInput.value = urlToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            var confirmationMessage = 'Link copied to clipboard!';
            toastr.success(confirmationMessage);
        });
    </script>
    <!--- Admin -------- users-chart-script --->
    <!--- VendorAdmin -------- orders-count-chart-script --->
    <script type="text/javascript">
        var doughnut = null;
        var doughnutlabels = <?php echo e(Js::from($doughnutlabels)); ?>;
        var doughnutdata = <?php echo e(Js::from($doughnutdata)); ?>;
    </script>
    <!--- Admin ------ revenue-by-plans-chart-script --->
    <!--- vendorAdmin ------ revenue-by-orders-script --->
    <script type="text/javascript">
        var revenuechart = null;
        var labels = <?php echo e(Js::from($revenuelabels)); ?>;
        var revenuedata = <?php echo e(Js::from($revenuedata)); ?>;
    </script>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/dashboard.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>