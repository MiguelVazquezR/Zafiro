<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceEntry;
use App\Models\FinancePayment;
use App\Models\FinanceExpense;
use App\Models\FinanceService;
use App\Models\FinanceInvoice;

class FinanceController extends Controller
{
    // Carga la vista principal Blade que contiene el React
    public function index()
    {
        return view('finanzas.index');
    }

    // Devuelve todos los datos iniciales para el state de React
    public function getAllData()
    {
        $entries = FinanceEntry::with('payments')->orderBy('id', 'desc')->get()->map(function($e) {
            return [
                'id' => $e->id,
                'fecha' => $e->date,
                'cliente' => $e->client_name,
                'telefono' => $e->phone,
                'tipoServicio' => $e->service_type,
                'ubicacion' => $e->location,
                'montoOriginal' => (float)$e->original_amount,
                'modoIvaOriginal' => $e->iva_mode,
                'honorarios' => (float)$e->fees,
                'iva' => (float)$e->iva,
                'totalConIva' => (float)$e->total_with_iva,
                'anticipo' => (float)$e->advance_payment,
                'saldo' => (float)$e->balance,
                'estado' => $e->status,
                'declarado' => (bool)$e->is_declared,
                'folioFactura' => $e->finance_invoice_id ? $e->invoice->invoice_folio ?? null : null,
                'numeroRecibo' => $e->receipt_number,
                'historialPagos' => $e->payments->map(function($p) {
                    return [
                        'id' => $p->id,
                        'fecha' => $p->date,
                        'monto' => (float)$p->amount,
                        'recibo' => $p->receipt_number
                    ];
                })
            ];
        });

        $expenses = FinanceExpense::orderBy('id', 'desc')->get()->map(function($ex) {
            return [
                'id' => $ex->id,
                'fecha' => $ex->date,
                'concepto' => $ex->concept,
                'monto' => (float)$ex->amount,
                'facturado' => (bool)$ex->is_invoiced
            ];
        });

        $invoices = FinanceInvoice::orderBy('id', 'desc')->get()->map(function($inv) {
            return [
                'id' => $inv->id,
                'fecha' => $inv->date,
                'folioFactura' => $inv->invoice_folio,
                'honorariosTotal' => (float)$inv->total_fees,
                'ivaTotal' => (float)$inv->total_iva,
                'totalGeneral' => (float)$inv->grand_total,
                'pagoSat' => (float)$inv->sat_payment,
                'liberado' => (float)$inv->released_balance,
                'registrosLiquidacion' => $inv->liquidated_records_count
            ];
        });

        $services = FinanceService::all()->map(function($s) {
            return ['id' => $s->id, 'nombre' => $s->name];
        });

        return response()->json([
            'entries' => $entries,
            'expenses' => $expenses,
            'invoices' => $invoices,
            'services' => $services
        ]);
    }

    // Guarda o actualiza un trámite
    public function storeEntry(Request $request)
    {
        $data = [
            'date' => $request->fecha,
            'client_name' => $request->cliente,
            'phone' => $request->telefono,
            'service_type' => $request->tipoServicio,
            'location' => $request->ubicacion,
            'original_amount' => $request->montoOriginal,
            'iva_mode' => $request->modoIvaOriginal,
            'fees' => $request->honorarios,
            'iva' => $request->iva,
            'total_with_iva' => $request->totalConIva,
            'advance_payment' => $request->anticipo,
            'balance' => $request->saldo,
            'status' => $request->estado,
            'receipt_number' => $request->numeroRecibo,
        ];

        $entry = FinanceEntry::create($data);

        // Si hay anticipo inicial, registrarlo como el primer pago
        if ($request->anticipo > 0) {
            FinancePayment::create([
                'finance_entry_id' => $entry->id,
                'date' => $request->fecha,
                'amount' => $request->anticipo,
                'receipt_number' => $request->numeroRecibo ?? 'S/N'
            ]);
        }

        return response()->json(['success' => true, 'id' => $entry->id]);
    }

    public function updateEntry(Request $request, FinanceEntry $financeEntry)
    {
        $financeEntry->update([
            'date' => $request->fecha,
            'client_name' => $request->cliente,
            'phone' => $request->telefono,
            'service_type' => $request->tipoServicio,
            'location' => $request->ubicacion,
            'original_amount' => $request->montoOriginal,
            'iva_mode' => $request->modoIvaOriginal,
            'fees' => $request->honorarios,
            'iva' => $request->iva,
            'total_with_iva' => $request->totalConIva,
            'advance_payment' => $request->anticipo,
            'balance' => $request->saldo,
            'status' => $request->estado,
            'receipt_number' => $request->numeroRecibo,
        ]);
        return response()->json(['success' => true]);
    }

    public function destroyEntry(FinanceEntry $financeEntry)
    {
        $financeEntry->delete();
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, FinanceEntry $financeEntry)
    {
        $financeEntry->update(['status' => $request->estado]);
        return response()->json(['success' => true]);
    }

    // Guarda un abono/pago
    public function storePayment(Request $request)
    {
        $payment = FinancePayment::create([
            'finance_entry_id' => $request->entryId,
            'date' => $request->fecha,
            'amount' => $request->amount,
            'receipt_number' => $request->recibo
        ]);

        // Actualizar el saldo de la entrada principal
        $entry = FinanceEntry::find($request->entryId);
        $newAdvance = $entry->advance_payment + $request->amount;
        $newBalance = $entry->total_with_iva - $newAdvance;
        $status = ($newBalance <= 0) ? 'Completado' : $entry->status;
        
        $entry->update([
            'advance_payment' => $newAdvance,
            'balance' => $newBalance,
            'status' => $status
        ]);

        return response()->json(['success' => true, 'id' => $payment->id]);
    }

    // Gastos
    public function storeExpense(Request $request)
    {
        $expense = FinanceExpense::create([
            'date' => $request->fecha,
            'concept' => $request->concepto,
            'amount' => $request->monto,
            'is_invoiced' => $request->facturado
        ]);
        return response()->json(['success' => true, 'id' => $expense->id]);
    }

    public function destroyExpense(FinanceExpense $financeExpense)
    {
        $financeExpense->delete();
        return response()->json(['success' => true]);
    }

    // Servicios
    public function storeService(Request $request)
    {
        $service = FinanceService::create(['name' => $request->nombre]);
        return response()->json(['success' => true, 'id' => $service->id]);
    }

    public function destroyService(FinanceService $financeService)
    {
        $financeService->delete();
        return response()->json(['success' => true]);
    }

    // Facturación (IVA)
    public function storeInvoice(Request $request)
    {
        $invoice = FinanceInvoice::create([
            'date' => $request->fecha,
            'invoice_folio' => $request->folioFactura,
            'total_fees' => $request->honorariosTotal,
            'total_iva' => $request->ivaTotal,
            'grand_total' => $request->totalGeneral,
            'sat_payment' => $request->pagoSat,
            'released_balance' => $request->liberado,
            'liquidated_records_count' => $request->registrosLiquidacion
        ]);

        // Marcar trámites seleccionados como declarados
        FinanceEntry::whereIn('id', $request->entryIds)->update([
            'is_declared' => true,
            'finance_invoice_id' => $invoice->id
        ]);

        return response()->json(['success' => true, 'id' => $invoice->id]);
    }
}