<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use App\Models\InsuranceClaim;

class FinancialsController extends Controller
{
    // 17.01 GET /api/v1/expenses
    public function expenses()
    {
        $expenses = Expense::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $expenses]);
    }

    // 17.05 GET /api/v1/expense-categories
    public function expenseCategories()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'name' => 'Medical Supplies', 'budget' => 250000],
                ['id' => 2, 'name' => 'Utility Bills', 'budget' => 80000],
                ['id' => 3, 'name' => 'Equipment Maintenance', 'budget' => 120000],
                ['id' => 4, 'name' => 'Staff Salaries', 'budget' => 1500000],
            ]
        ]);
    }

    // 17.07 GET /api/v1/income
    public function income()
    {
        $income = Income::orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $income]);
    }

    // 17.10 GET /api/v1/invoices
    public function invoices()
    {
        $invoices = Payment::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $invoices]);
    }

    // 17.11 GET /api/v1/invoices/{id}
    public function showInvoice($id)
    {
        $inv = Payment::with('patient')->find($id);
        if (!$inv) {
            return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 404);
        }
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $inv]);
    }

    // 17.15 GET /api/v1/payments
    public function payments()
    {
        $payments = Payment::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    }

    // 17.17 GET /api/v1/transactions
    public function transactions()
    {
        $payments = Payment::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $payments]);
    }

    // 17.18 GET /api/v1/insurance-claims
    public function insuranceClaims()
    {
        $claims = InsuranceClaim::with('patient')->orderBy('id', 'desc')->paginate(15);
        return response()->json(['status' => 'success', 'currency' => 'NPR', 'data' => $claims]);
    }

    // 17.21 GET /api/v1/reports/income
    public function incomeReport()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'monthly_income' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [1250000, 1400000, 1350000, 1600000, 1550000, 1750000]
                ],
                'total_income' => '89,00,000',
                'currency' => 'NPR'
            ]
        ]);
    }

    // 17.22 GET /api/v1/reports/expenses
    public function expenseReport()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'monthly_expenses' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'series' => [450000, 500000, 480000, 520000, 510000, 540000]
                ],
                'total_expenses' => '30,00,000',
                'currency' => 'NPR'
            ]
        ]);
    }

    // 17.23 GET /api/v1/reports/profit-loss
    public function profitLossReport()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'months'         => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'income_series'  => [1250000, 1400000, 1350000, 1600000, 1550000, 1750000],
                'expense_series' => [450000, 500000, 480000, 520000, 510000, 540000],
                'profit_series'  => [800000, 900000, 870000, 1080000, 1040000, 1210000],
                'net_profit'     => '59,00,000',
                'currency'       => 'NPR'
            ]
        ]);
    }
}
