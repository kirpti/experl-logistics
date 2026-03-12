<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index() { return view('tracking.index'); }

    public function show(string $number)
    {
        $shipment = DB::table('shipments')->where('tracking_number', $number)->first();
        $events   = $shipment ? DB::table('shipment_events')->where('shipment_id', $shipment->id)->orderByDesc('created_at')->get() : collect();
        return view('tracking.show', compact('shipment', 'events', 'number'));
    }
}
