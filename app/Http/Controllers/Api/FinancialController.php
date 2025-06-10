<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\MemberFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FinancialTransaction::with(['recordedBy:id,name', 'approvedBy:id,name']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('year')) {
            $query->whereYear('transaction_date', $request->year);
        }

        if ($request->boolean('pending_approval')) {
            $query->pending();
        }

        $transactions = $query->latest('transaction_date')->paginate($request->get('per_page', 15));

        return response()->json($transactions);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expense',
            'category' => 'required|in:membership_fees,event_income,donations,grants,office_expenses,event_expenses,maintenance,insurance,legal_fees,marketing,other',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'payment_method' => 'nullable|in:cash,bank_transfer,check,card,other',
            'notes' => 'nullable|string',
        ]);

        $transaction = FinancialTransaction::create([
            'concept' => $request->concept,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'reference_number' => $request->reference_number,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'recorded_by' => auth()->id(),
            'is_approved' => false,
        ]);

        $transaction->load('recordedBy:id,name');

        return response()->json([
            'message' => 'Transacción registrada exitosamente',
            'transaction' => $transaction
        ], 201);
    }

    public function show(FinancialTransaction $financialTransaction): JsonResponse
    {
        $financialTransaction->load(['recordedBy:id,name,email', 'approvedBy:id,name,email']);
        return response()->json($financialTransaction);
    }

    public function update(Request $request, FinancialTransaction $financialTransaction): JsonResponse
    {
        if ($financialTransaction->is_approved) {
            return response()->json(['message' => 'No se puede modificar una transacción aprobada'], 422);
        }

        $request->validate([
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'payment_method' => 'nullable|in:cash,bank_transfer,check,card,other',
            'notes' => 'nullable|string',
        ]);

        $financialTransaction->update($request->validated());

        return response()->json([
            'message' => 'Transacción actualizada exitosamente',
            'transaction' => $financialTransaction
        ]);
    }

    public function approve(FinancialTransaction $financialTransaction): JsonResponse
    {
        if ($financialTransaction->is_approved) {
            return response()->json(['message' => 'La transacción ya está aprobada'], 422);
        }

        $financialTransaction->update([
            'is_approved' => true,
            'approved_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Transacción aprobada exitosamente',
            'transaction' => $financialTransaction
        ]);
    }

    public function memberFees(Request $request): JsonResponse
    {
        $query = MemberFee::with('user:id,name,email');

        if ($request->has('year')) {
            $query->byYear($request->year);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        $fees = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($fees);
    }

    public function payFee(Request $request, MemberFee $fee): JsonResponse
    {
        if ($fee->status === 'paid') {
            return response()->json(['message' => 'La cuota ya está pagada'], 422);
        }

        return DB::transaction(function () use ($request, $fee) {
            // Crear transacción financiera
            $transaction = FinancialTransaction::create([
                'concept' => "Cuota de socio - {$fee->user->name}",
                'type' => 'income',
                'category' => 'membership_fees',
                'amount' => $fee->amount,
                'transaction_date' => now()->toDateString(),
                'recorded_by' => auth()->id(),
                'is_approved' => true,
                'approved_by' => auth()->id(),
            ]);

            // Actualizar cuota
            $fee->update([
                'status' => 'paid',
                'paid_date' => now(),
                'financial_transaction_id' => $transaction->id,
            ]);

            return response()->json([
                'message' => 'Cuota pagada exitosamente',
                'fee' => $fee,
                'transaction' => $transaction
            ]);
        });
    }

    public function reports(Request $request): JsonResponse
    {
        $year = $request->get('year', now()->year);

        $income = FinancialTransaction::income()
            ->approved()
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $expenses = FinancialTransaction::expense()
            ->approved()
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $balance = $income - $expenses;

        $incomeByCategory = FinancialTransaction::income()
            ->approved()
            ->whereYear('transaction_date', $year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $expensesByCategory = FinancialTransaction::expense()
            ->approved()
            ->whereYear('transaction_date', $year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return response()->json([
            'year' => $year,
            'summary' => [
                'total_income' => $income,
                'total_expenses' => $expenses,
                'balance' => $balance,
            ],
            'income_by_category' => $incomeByCategory,
            'expenses_by_category' => $expensesByCategory,
        ]);
    }

    public function destroy(FinancialTransaction $financialTransaction): JsonResponse
    {
        if ($financialTransaction->is_approved) {
            return response()->json(['message' => 'No se puede eliminar una transacción aprobada'], 422);
        }

        $financialTransaction->delete();

        return response()->json([
            'message' => 'Transacción eliminada exitosamente'
        ]);
    }
}