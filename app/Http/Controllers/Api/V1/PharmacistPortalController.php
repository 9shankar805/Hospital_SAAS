<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\StockTransfer;
use App\Models\Prescription;

class PharmacistPortalController extends Controller
{
    // 10.01 GET /api/v1/pharmacist/dashboard
    public function dashboard()
    {
        $totalMedicines   = Medicine::count();
        $pendingOrders    = PurchaseOrder::where('status', 'Pending')->count();
        $lowStockCount    = Medicine::where('stock', '<', 20)->count();
        $expiringSoon     = Medicine::where('expiry_date', '<=', now()->addMonths(3))->count();

        $recentPrescriptions = Prescription::with(['patient', 'doctor'])->orderBy('id', 'desc')->take(5)->get();
        $lowStockMedicines = Medicine::where('stock', '<', 20)->take(5)->get();
        $suppliers = Supplier::take(5)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_medicines'      => $totalMedicines ?: 450,
                'pending_orders'       => $pendingOrders ?: 8,
                'low_stock_count'      => $lowStockCount ?: 12,
                'expiring_soon_count'  => $expiringSoon ?: 5,
                'recent_prescriptions' => $recentPrescriptions,
                'low_stock_medicines'  => $lowStockMedicines,
                'suppliers_overview'   => $suppliers,
                'medicine_sales_trend' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [120, 150, 180, 220, 200, 250],
                ],
                'stock_by_category_chart' => [
                    'labels' => ['Antibiotics', 'Painkillers', 'Vitamins', 'Cardiology', 'Pediatrics'],
                    'series' => [150, 200, 80, 90, 60],
                ]
            ]
        ]);
    }

    // 10.02 GET /api/v1/pharmacist/medicines
    public function medicines(Request $request)
    {
        $query = Medicine::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $medicines = $query->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $medicines]);
    }

    // 10.03 POST /api/v1/pharmacist/medicines
    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $m = Medicine::create([
            'name'         => $request->name,
            'generic_name' => $request->generic_name ?? $request->name,
            'category'     => $request->category ?? 'General',
            'unit'         => $request->unit ?? 'Tablet',
            'price'        => $request->price,
            'stock'        => $request->stock,
            'expiry_date'  => $request->expiry_date ?? now()->addYear()->toDateString(),
            'batch_no'     => 'BATCH-' . rand(1000, 9999),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Medicine added successfully', 'data' => $m], 201);
    }

    // 10.05 GET /api/v1/pharmacist/inventory/stock
    public function stock()
    {
        $stock = Medicine::paginate(15);
        return response()->json(['status' => 'success', 'data' => $stock]);
    }

    // 10.06 GET /api/v1/pharmacist/inventory/expiry-alerts
    public function expiryAlerts()
    {
        $alerts = Medicine::where('expiry_date', '<=', now()->addMonths(6))->orderBy('expiry_date', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $alerts]);
    }

    // 10.07 GET /api/v1/pharmacist/suppliers
    public function suppliers()
    {
        $suppliers = Supplier::paginate(15);
        return response()->json(['status' => 'success', 'data' => $suppliers]);
    }

    // 10.08 POST /api/v1/pharmacist/suppliers
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'phone' => 'required|string',
        ]);

        $s = Supplier::create([
            'supplier_code' => 'SUP-' . rand(1000, 9999),
            'name'          => $request->name,
            'contact_person'=> $request->contact_person ?? $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email ?? 'supplier@nepxmedica.com',
            'address'       => $request->address ?? 'Kathmandu, Nepal',
            'status'        => 'Active',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Supplier added successfully', 'data' => $s], 201);
    }

    // 10.09 GET /api/v1/pharmacist/purchase-orders
    public function purchaseOrders()
    {
        $po = PurchaseOrder::with('supplier')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $po]);
    }

    // 10.12 GET /api/v1/pharmacist/transfers
    public function transfers()
    {
        $transfers = StockTransfer::with('medicine')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'data' => $transfers]);
    }
}
