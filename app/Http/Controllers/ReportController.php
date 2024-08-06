<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request)
    {
        $orders = Order::with(['client', 'inventory']);

        // Filter by date range if specified
        if ($request->has('start_date') && $request->has('end_date')) {
            $orders->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        $orders = $orders->get();

        return view('reports.index', compact('orders'));
    }

    /**
     * Generate an invoice for a specific order.
     */
    public function generateInvoice(Order $order)
    {
        // Load the view and pass the order data
        $pdf = PDF::loadView('reports.invoice', compact('order'));

        // Download the generated PDF
        return $pdf->download('invoice_' . $order->id . '.pdf');
    }
}
