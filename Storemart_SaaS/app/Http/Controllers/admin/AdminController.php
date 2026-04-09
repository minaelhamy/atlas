<?php

namespace App\Http\Controllers\admin;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Helpers\helper;
use App\Models\OrderDetails;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $user = User::where('id', $vendor_id)->first();
        // Admin
        $totalplans = PricingPlan::count();
        // Vendor-Admin
        $currentplanname = PricingPlan::select('name')->where('id',  $user->plan_id)->orderByDesc('id')->first();
        // ----------------------- chart -----------------------
        $doughnutyear = $request->doughnutyear != "" ? $request->doughnutyear : date('Y');
        $revenueyear = $request->revenueyear != "" ? $request->revenueyear : date('Y');
        if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)) {
            $totalvendors = User::where('id', '!=', 1)->where('is_available', 1)->where('type', 2)->where('is_deleted', 2)->count();
            $totalrevenue = Transaction::where('status', 2)->sum('grand_total');
            $totalorders = Transaction::count('id');
            // DOUGHNUT-CHART-START
            $doughnut_years = User::select(DB::raw("YEAR(created_at) as year"))->where('type', 2)->groupBy(DB::raw("YEAR(created_at)"))->orderByDesc('created_at')->get();
            $vendorlist = User::select(DB::raw("YEAR(created_at) as year"), DB::raw("MONTHNAME(created_at) as month_name"), DB::raw("COUNT(id) as total_user"))->whereYear('created_at', $doughnutyear)->where('type', 2)->orderBy('created_at')->groupBy(DB::raw("MONTHNAME(created_at)"))->pluck('total_user', 'month_name');
            $doughnutlabels = $vendorlist->keys();
            $doughnutdata = $vendorlist->values();
            // DOUGHNUT-CHART-END
            // revenue-CHART-START
            $revenue_years = Transaction::select(DB::raw("YEAR(purchase_date) as year"))->groupBy(DB::raw("YEAR(purchase_date)"))->orderByDesc('purchase_date')->get();
            $revenue_list = Transaction::select(DB::raw("YEAR(purchase_date) as year"), DB::raw("MONTHNAME(purchase_date) as month_name"), DB::raw("SUM(amount) as total_amount"))->whereYear('purchase_date', $revenueyear)->where('status', 2)->orderby('purchase_date')->groupBy(DB::raw("MONTHNAME(purchase_date)"))->pluck('total_amount', 'month_name');
            $revenuelabels = $revenue_list->keys();
            $revenuedata = $revenue_list->values();
            // revenue-CHART-END
            $transaction = Transaction::with('vendor_info')->wherenotNULL('plan_name')->whereDate('created_at', Carbon::today())->get();
            $getorders = array();
            $topitems = Item::with('category_info', 'product_image')->join('order_details', 'order_details.item_id', 'items.id')
                ->select('items.id', 'items.cat_id', 'items.item_name', 'items.slug', DB::raw('count(order_details.item_id) as item_order_counter'))
                ->groupBy('order_details.item_id')->having('item_order_counter', '>', 0)
                ->where('items.vendor_id', $vendor_id)->where('items.is_deleted', 2)->orderByDesc('item_order_counter')
                ->get()->take(5);
            $orders = Order::where('vendor_id', $vendor_id)->get();
            $orderIds = $orders->pluck('id');
            $getorderdetailscount = OrderDetails::whereIn('order_id', $orderIds)->count();

            $topusers = User::join('orders', 'orders.user_id', 'users.id')
                ->select('users.id', 'users.name', 'users.email', 'users.mobile', DB::raw('count(orders.user_id) as user_order_counter'))
                ->having('user_order_counter', '>', 0)->where('orders.vendor_id', $vendor_id)->where('users.type', 2)
                ->where('users.is_available', 1)->orderByDesc('user_order_counter')->get()->take(7);
        } else {
            $totalvendors = Item::where('vendor_id', $vendor_id)->count();
            $totalrevenue = Order::where('vendor_id', $vendor_id)->where('status_type', 3)->where('payment_status', '2')->sum('grand_total');
            $totalorders = Order::where('vendor_id',  $vendor_id)->count();
            // DOUGHNUT-CHART-START
            $doughnut_years = $revenue_years = Order::select(DB::raw("YEAR(created_at) as year"))->groupBy(DB::raw("YEAR(created_at)"))->orderByDesc('created_at')->get();
            $orderlist = Order::select(DB::raw("YEAR(created_at) as year"), DB::raw("MONTHNAME(created_at) as month_name"), DB::raw("COUNT(id) as total_orders"))->whereYear('created_at', $doughnutyear)->orderBy('created_at')->where('vendor_id',  $vendor_id)->groupBy(DB::raw("MONTHNAME(created_at)"))->pluck('total_orders', 'month_name');
            $doughnutlabels = $orderlist->keys();
            $doughnutdata = $orderlist->values();
            // DOUGHNUT-CHART-END
            // revenue-CHART-START
            $revenue_list = Order::select(DB::raw("YEAR(created_at) as year"), DB::raw("MONTHNAME(created_at) as month_name"), DB::raw("SUM(grand_total) as total_amount"))->whereYear('created_at', $revenueyear)->orderBy('created_at')->where('vendor_id', $vendor_id)->groupBy(DB::raw("MONTHNAME(created_at)"))->pluck('total_amount', 'month_name');
            $revenuelabels = $revenue_list->keys();
            $revenuedata = $revenue_list->values();
            // revenue-CHART-END
            $transaction = array();
            $getorders = order::select("id", "order_number", "grand_total", "order_type", "payment_type", "payment_id", "delivery_date", "delivery_time", "status", "status_type", "screenshot", DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as order_date'), 'created_at', 'updated_at', 'payment_status', 'dinein_table', 'dinein_tablename')->where('vendor_id', $vendor_id)->whereIn('status_type', [1, 2])->orderByDesc('id')->get();

            $topitems = Item::with('category_info', 'product_image')->join('order_details', 'order_details.item_id', 'items.id')
                ->select('items.id', 'items.cat_id', 'items.item_name', 'items.slug', DB::raw('count(order_details.item_id) as item_order_counter'))
                ->groupBy('order_details.item_id')->having('item_order_counter', '>', 0)
                ->where('items.vendor_id', $vendor_id)->where('items.is_deleted', 2)->orderByDesc('item_order_counter')
                ->get()->take(5);
            $orders = Order::where('vendor_id', $vendor_id)->get();
            $orderIds = $orders->pluck('id');
            $getorderdetailscount = OrderDetails::whereIn('order_id', $orderIds)->count();



            $topusers = User::join('orders', 'orders.user_id', 'users.id')
                ->select('users.id', 'users.name', 'users.email', 'users.mobile', DB::raw('count(orders.user_id) as user_order_counter'))
                ->having('user_order_counter', '>', 0)->where('orders.vendor_id', $vendor_id)->where('users.type', 2)
                ->where('users.is_available', 1)->orderByDesc('user_order_counter')->get()->take(7);
        }
        if (env('Environment') == 'sendbox') {
            $doughnutlabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
            $doughnutdata = [636, 1269, 2810, 2843, 3637, 467, 902, 1296, 402, 1173, 1509, 413];
            $revenuelabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
            $revenuedata = [636, 1269, 2810, 2843, 3637, 467, 902, 1296, 402, 1173, 1509, 413];
        }
        if ($request->ajax()) {
            return response()->json([
                'doughnutlabels' => $doughnutlabels,
                'doughnutdata' => $doughnutdata,
                'revenuelabels' => $revenuelabels,
                'revenuedata' => $revenuedata
            ], 200);
        } else {
            if (Auth::user()->type == 4) {
                if (helper::check_access('role_dashboard', Auth::user()->role_id, Auth::user()->vendor_id, 'manage') == 1) {
                    return view(
                        'admin.dashboard.index',
                        compact(
                            'totalvendors',
                            'totalplans',
                            'totalorders',
                            'totalrevenue',
                            'currentplanname',
                            'doughnut_years',
                            'doughnutlabels',
                            'doughnutdata',
                            'revenue_years',
                            'revenuelabels',
                            'revenuedata',
                            'transaction',
                            'getorders',
                            'topitems',
                            'getorderdetailscount',
                            'topusers'
                        )
                    );
                } else {
                    return view('admin.dashboard.access_denied');
                }
            } else {
                return view(
                    'admin.dashboard.index',
                    compact(
                        'totalvendors',
                        'totalplans',
                        'totalorders',
                        'totalrevenue',
                        'currentplanname',
                        'doughnut_years',
                        'doughnutlabels',
                        'doughnutdata',
                        'revenue_years',
                        'revenuelabels',
                        'revenuedata',
                        'transaction',
                        'getorders',
                        'topitems',
                        'getorderdetailscount',
                        'topusers'
                    )
                );
            }
        }
    }

    public function login(Request $request)
    {
        if (!env('ATLAS_EMBEDDED') && !file_exists(storage_path() . "/installed")) {
            return redirect('install');
            exit;
        }
        helper::language(1);
        return view('admin.auth.login', [
            'atlasSource' => $request->query('source') === 'atlas',
            'atlasEmail' => (string) $request->query('atlas_email', ''),
        ]);
    }

    public function check_admin_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => trans('messages.email_required'),
            'email.email' => trans('messages.invalid_email'),
            'password.required' => trans('messages.password_required'),
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        session()->put('admin_login', 1);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'type' => [1, 2, 4], 'is_deleted' => 2], $remember_me)) {

            if (!Auth::user()) {
                return Redirect::to('/admin/verification')->with('error', Session()->get('from_message'));
            }
            if (Auth::user()->type == 1) {
                return redirect('/admin/dashboard');
            } else {

                if (Auth::user()->type == 2 && Auth::user()->is_deleted == 2) {
                    if (Auth::user()->is_available == 1) {
                        return redirect('/admin/dashboard');
                    } else {
                        Auth::logout();
                        return redirect()->back()->with('error', trans('messages.account_blocked_by_admin'));
                    }
                } elseif (Auth::user()->type == 4 && Auth::user()->is_deleted == 2) {
                    if (Auth::user()->is_available == 1 && Auth::user()->is_deleted == 2) {
                        return redirect('/admin/dashboard');
                    } else {
                        Auth::logout();
                        return redirect()->back()->with('error', trans('messages.account_blocked_by_admin'));
                    }
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', trans('messages.email_pass_invalid'));
                }
            }
        } else {
            return redirect()->back()->with('error', trans('messages.email_pass_invalid'));
        }
    }
    public function logout()
    {
        session()->flush();
        Auth::logout();
        if (helper::appdata('')->landing_page == 2) {
            return redirect('/');
        } else {
            return redirect('admin');
        }
    }
    public function systemverification(Request $request)
    {
        $username = str_replace(' ', '', $request->username);

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6IkwxeWUxTEduU29hbXEyc05qSGtSNnc9PSIsInZhbHVlIjoiSTVJcWVFeUg2NzE3WnRGK2ZyLzc2dkNzWFMyWUt5VTlQR285M2NqRXJzR1lTZEtFc2VRWVlwTDcrR3R6TGZubCIsIm1hYyI6IjYzODgxNGY3MDk2OGRhMTgyMGI2NWIzZDhkNWNmY2Y3ZjdhZDQyZTYwZjdhZWVhMjdmZDEzYTQzZDBkN2M5ODYiLCJ0YWciOiIifQ=='), [
            'form_params' => [
                \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6ImJkS3crTk9COHRYL0tQK2kxRERNQXc9PSIsInZhbHVlIjoiU0NIcmkrT2xJdkppcnJ5cFY4OW5XWllud3c4Z3QyaGVHK0hYVVUyNnc3UT0iLCJtYWMiOiI0Njg1NTcwNTAxNTI0ODFmZDE0Y2FmMWU4M2M3ZGFkZThjNDgxZWZlNTY1ZmVkOTdiNGZjM2VkNDI5MmI2NTljIiwidGFnIjoiIn0=') => $username,
                \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6ImU5NjI5NFRTQXRWeTRHQndWVHJsUXc9PSIsInZhbHVlIjoiNHpzaHFCVjc0ajVKMktNampzN0NvUT09IiwibWFjIjoiODU5M2M0Y2VhMDE5YjgyNzU5MWE4NzZjOGUxNjlkZWFhMWI0ZjJiYTU2YTQ1NTMwYzI4YjBhMjg5Y2VhNzNiYiIsInRhZyI6IiJ9') => $request->email,
                \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6IkxHVHdQNGo0Vzd1TktaS21kZEQwL1E9PSIsInZhbHVlIjoiRDEvUGZZa3MzbkkzQ0pxUFlrRFJOektiU1FLN3YvZ3ZHK25UcE95UHFzdz0iLCJtYWMiOiI5NWJlNDVmM2VkNWViZDYxNTIwNGI1MWI0ZTU0YWEzN2VkNjhhZDZlM2QwOWMyOGY5OTFkYTY5ODVkNWY2NDdiIiwidGFnIjoiIn0=') => $request->purchase_key,
                \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6IlRTOCtHbEI3Slh3Wm1sWHRCNWM2SWc9PSIsInZhbHVlIjoiNGxHU2puYjdnaCtUUVhJeFVNa014dz09IiwibWFjIjoiNzY4ZjI2MjZmZTVjMjI1NTZhNGZjMjNjYjE4M2MyODUxOTkwNWE2NjA1MjI3NTRiOTViMDY3Zjc3OTEzYWY2NSIsInRhZyI6IiJ9') => $request->domain,
                \Illuminate\Support\Facades\Crypt::decrypt('^ "eyJpdiI6IlZSSHhaNC8rOFVHRHNsdXVseDZRTGc9PSIsInZhbHVlIjoieGp2aEFLOU85OGdPeUNDck9TeUg2WW12Y3d4L3AyeHNwRXh5dXN2UTRJVT0iLCJtYWMiOiI2NzE3NmIxM2M3YzgzM2JjMDkyMjMyMGUwODFhZDA3NzVhZDI4YmJjMDk4NzJiYmI1M2NkZWRmZTZhNzZhZWM0IiwidGFnIjoiIn0=') => \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6IlVJVTlCMXozN3BtY1RHTUZIS2N3ZXc9PSIsInZhbHVlIjoicXIreUdDeXNMb2lIZUg1QUphM1lCdz09IiwibWFjIjoiMWM2NzEyOTYzNjNmMzIwY2NjZWY2NGY2ZDIxMzNiNDIxMWUyMzc2MGQ1ODQyMmVkNzlkYjcxZDM5ZGRiYzI5YiIsInRhZyI6IiJ9'),
                \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6Ii9xbk0yamJvZTVRdXFUY1FPaE5DZ2c9PSIsInZhbHVlIjoidVJJUk5YZzMxc2VCOFVjUFJsZ2hCUT09IiwibWFjIjoiZjM5MzkxOWIxNmE5NWQxMzljMDZlNGVhZThlNWVmYmY2MzFkMmU5NDUxNmRiMTRkNWRjNGJmY2I5YzNhZDA3ZCIsInRhZyI6IiJ9') => \Illuminate\Support\Facades\Crypt::decrypt('eyJpdiI6IjJ2WmlxOHJBNzk2LzU0aENVMjJPemc9PSIsInZhbHVlIjoiUEF4Z1IrMjl1SkRGbzZFdXhSVG9wdz09IiwibWFjIjoiODM4YjgyMWYyZDY4NGMxODNjNmRiY2ExNWU4MzkzNWJlZGI1MjU3MjEwODFjMDA0NzY5YTZmNDJkNDZlY2Q5MiIsInRhZyI6IiJ9'),
            ]
        ]);

        $obj = json_decode($res->getBody());

        if ($obj->status == '1') {
            User::where('id', 1)->update(['license_type' => $obj->license_type]);
            return Redirect::to('/admin')->with('success', 'Success');
        } else {
            return Redirect::back()->with('error', $obj->message);
        }
    }

    public function sessionsave(Request $request)
    {
        session()->put('demo', $request->demo_type);

        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
