<?php

namespace App\Http\Controllers;

use App\Models\Obligation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ObligationController extends Controller
{
    public function list() {
        $this->updatePayments();
        $this->updateStatus();

        $obligations = Obligation::orderBy('status', 'desc')->get();

        foreach ($obligations as $obligation) {
            $obligation->total_paid = Payment::where('obligation_id', $obligation->obligation_id)->sum('paid');
        }
        return response()->json($obligations);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'obligation_description' => 'required|string',
            'quantity' => 'required|integer',
            'period' => 'required|string',
            'alert_time' => 'required|integer',
            'created_by' => 'nullable|integer',
            'last_payment' => 'nullable|numeric',
            'expiration_date' => 'nullable|string',
            'observations' => 'required|string',
            'reviewed_by' => 'nullable|integer',
            'review_date' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $obligation = new Obligation();
            $obligation->obligation_id = $request->obligation_id;
            $obligation->obligation_description = $request->obligation_description;
            $obligation->quantity = $request->quantity;
            $obligation->period = $request->period;
            $obligation->alert_time = $request->alert_time;
            $obligation->created_by = 1; // Sin autenticación
            $obligation->last_payment = $request->last_payment;
            $obligation->expiration_date = $request->expiration_date;
            $obligation->observations = $request->observations;
            $obligation->reviewed_by = $request->reviewed_by;
            $obligation->review_date = $request->review_date;
            $obligation->status = 12;

            $obligation->save();

            $obligation->load('status');

            return response()->json(['message' => 'Obligation creado correctamente.', 'obligation' => $obligation], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar guardar obligation.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function view($obligation_id) {
        $obligation = Obligation::findORfail($obligation_id);
        return response()->json(['obligation' => $obligation]);
    }

    public function update(Request $request, $obligation_id) {
        $validator = Validator::make($request->all(), [
            'obligation_description' => 'required|string',
            'quantity' => 'required|integer',
            'period' => 'required|string',
            'alert_time' => 'required|integer',
            'last_payment' => 'nullable|numeric',
            'expiration_date' => 'nullable|string',
            'observations' => 'required|string',
            'reviewed_by' => 'nullable|integer',
            'review_date' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $obligation = Obligation::findORfail($obligation_id);
            $obligation->update([
                'obligation_description' => $request->obligation_description,
                'quantity' => $request->quantity,
                'period' => $request->period,
                'alert_time' => $request->alert_time,
                'last_payment' => $request->last_payment,
                'expiration_date' => $request->expiration_date,
                'observations' => $request->observations,
                'reviewed_by' => $request->reviewed_by,
                'review_date' => $request->review_date,
            ]);
            return response()->json(['message' => 'Obligation actualizado correctamente.']);
        }

        catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar actualizar obligation.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function delete($obligation_id) {
        $obligation = Obligation::findORfail($obligation_id);
        $obligation->update([
            'status' => 9
        ]);
        return response()->json(['message' => 'Obligation eliminado correctamente.']);
    }

    public function storePayment(Request $request) {
        $validator = Validator::make($request->all(), [
            'obligation_id' => 'required|integer',
            'date_ini' => 'required|date',
            'date_end' => 'nullable|date',
            'paid' => 'required|integer',
            'observations' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $payment = new Payment();

            $payment->obligation_id = $request->obligation_id;
            $payment->date_ini = $request->date_ini;
            $payment->date_end = $request->date_end;
            $payment->paid = $request->paid;
            $payment->observations = $request->observations;
            $payment->created_by = 1; // Sin autenticación
            $payment->creation_date = Carbon::now();
            $payment->status = 2;

            $payment->save();

            return response()->json(['message' => 'Payment creado correctamente.', 'payment' => $payment], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar guardar payment.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function listPayments($obligation_id) {
        $payments = Payment::where('obligation_id', $obligation_id)->get();
        return response()->json(['payments' => $payments]);
    }

    public function updatePayments() {
        $payments = Payment::all();

        if ($payments->isEmpty()) {
            return response()->json(['message' => 'No se encontraron pagos.'], 404);
        }

        foreach ($payments as $payment) {
            $obligations = Obligation::where('obligation_id', $payment->obligation_id)->get();

            if ($obligations->isEmpty()) {
                continue;
            }

            foreach ($obligations as $obligation) {
                $obligation->update([
                    'last_payment' => $payment->paid,
                    'expiration_date' => $payment->date_end,
                ]);
            }
        }
    }

    public function updateStatus() {
        $where = "1";
        DB::update(
            "
                UPDATE obligations
                SET status = 10
                WHERE $where
                AND obligations.status > 9
                AND obligations.status != 10
                AND CURDATE() < DATE_SUB(obligations.expiration_date, INTERVAL obligations.alert_time DAY)
            "
        );

        DB::update(
            "
                UPDATE obligations
                SET status = 12,
                    reviewed_by = NULL,
                    review_date = NULL
                WHERE $where
                AND obligations.status > 9
                AND obligations.status != 12
                AND obligations.period != 'UNDEFINED'
                AND CURDATE() > DATE_SUB(obligations.expiration_date, INTERVAL obligations.alert_time DAY)
            "
        );

        DB::update(
            "
                UPDATE obligations
                SET status = 13,
                    reviewed_by = NULL,
                    review_date = NULL
                WHERE $where
                AND obligations.status > 9
                AND obligations.status != 13
                AND obligations.period != 'UNDEFINED'
                AND obligations.expiration_date < CURDATE()
            "
        );

        DB::update(
            "
                UPDATE obligations
                SET status = 12
                WHERE last_payment IS NULL
                AND expiration_date IS NULL
            "
        );
    }
}