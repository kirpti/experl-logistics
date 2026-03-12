<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $stats = [
            'total_shipments'  => DB::table('shipments')->where('tenant_id',$tenantId)->count(),
            'delivered'        => DB::table('shipments')->where('tenant_id',$tenantId)->where('status','delivered')->count(),
            'in_transit'       => DB::table('shipments')->where('tenant_id',$tenantId)->where('status','in_transit')->count(),
            'total_revenue'    => DB::table('shipments')->where('tenant_id',$tenantId)->sum('total_amount'),
            'total_customers'  => DB::table('customers')->where('tenant_id',$tenantId)->count(),
            'total_branches'   => DB::table('branches')->where('tenant_id',$tenantId)->count(),
        ];

        $recentShipments = DB::table('shipments')
            ->where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentShipments'));
    }
}
