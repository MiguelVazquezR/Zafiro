<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceEntry;
use App\Models\FinancePayment;
use App\Models\FinanceExpense;
use App\Models\FinanceService;
use App\Models\FinanceInvoice;
use App\Models\User;

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
        $usersMap = User::pluck('name', 'id');
        $usersList = User::select('id', 'name')->get(); // Lista para los dropdowns

        $entries = FinanceEntry::with('payments')->orderBy('id', 'desc')->get()->map(function($e) use ($usersMap) {
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
                'registradoPor' => $usersMap[$e->user_id] ?? 'Sistema',
                'historialPagos' => $e->payments->map(function($p) use ($usersMap) {
                    return [
                        'id' => $p->id,
                        'fecha' => $p->date,
                        'monto' => (float)$p->amount,
                        'recibo' => $p->receipt_number,
                        'registradoPor' => $usersMap[$p->user_id] ?? 'Sistema'
                    ];
                })
            ];
        });

        $expenses = FinanceExpense::orderBy('id', 'desc')->get()->map(function($ex) use ($usersMap) {
            return [
                'id' => $ex->id,
                'fecha' => $ex->date,
                'concepto' => $ex->concept,
                'monto' => (float)$ex->amount,
                'facturado' => (bool)$ex->is_invoiced,
                'registradoPor' => $usersMap[$ex->user_id] ?? 'Sistema'
            ];
        });

        $invoices = FinanceInvoice::orderBy('id', 'desc')->get()->map(function($inv) use ($usersMap) {
            return [
                'id' => $inv->id,
                'fecha' => $inv->date,
                'folioFactura' => $inv->invoice_folio,
                'honorariosTotal' => (float)$inv->total_fees,
                'ivaTotal' => (float)$inv->total_iva,
                'totalGeneral' => (float)$inv->grand_total,
                'pagoSat' => (float)$inv->sat_payment,
                'liberado' => (float)$inv->released_balance,
                'registrosLiquidacion' => $inv->liquidated_records_count,
                'registradoPor' => $usersMap[$inv->user_id] ?? 'Sistema'
            ];
        });

        $services = FinanceService::all()->map(function($s) {
            return ['id' => $s->id, 'nombre' => $s->name];
        });

        return response()->json([
            'entries' => $entries,
            'expenses' => $expenses,
            'invoices' => $invoices,
            'services' => $services,
            'users' => $usersList
        ]);
    }

    // ==========================================
    // MÉTODO DE REGISTRO RÁPIDO (WhatsApp)
    // ==========================================
    public function quickRecord(Request $request)
    {
        // 1. Validar el token de seguridad
        $secretToken = env('QUICK_FINANCE_TOKEN', 'ZafiroRapido');
        if ($request->token !== $secretToken) {
            abort(403, 'Acceso denegado. Token inválido.');
        }

        // 2. Validar parámetros básicos
        $request->validate([
            'tipo' => 'required|in:ingreso,gasto',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'usuario' => 'nullable|string'
        ]);

        $tipo = $request->tipo;
        $concepto = $request->concepto;
        $monto = $request->monto;
        $fecha = now()->toDateString();
        
        $userId = null;
        $registradoPor = 'Sistema';

        // 3. Buscar match de usuario
        if ($request->has('usuario') && !empty($request->usuario)) {
            $user = User::where('name', 'LIKE', '%' . $request->usuario . '%')->first();
            if ($user) {
                $userId = $user->id;
                $registradoPor = $user->name;
            }
        }

        // 4. Guardar (Usamos instanciación directa para esquivar el $fillable si no está actualizado)
        if ($tipo === 'gasto') {
            $expense = new FinanceExpense([
                'date' => $fecha,
                'concept' => $concepto,
                'amount' => $monto,
                'is_invoiced' => false,
            ]);
            $expense->user_id = $userId;
            $expense->save();
            
        } else {
            // Ingreso
            $entry = new FinanceEntry([
                'date' => $fecha,
                'client_name' => $concepto, 
                'service_type' => 'Ingreso Rápido',
                'original_amount' => $monto,
                'iva_mode' => 'sin_iva',
                'fees' => $monto,
                'iva' => 0,
                'total_with_iva' => $monto,
                'advance_payment' => $monto,
                'balance' => 0,
                'status' => 'Completado',
                'is_declared' => false,
            ]);
            $entry->user_id = $userId;
            $entry->save();

            // Abono Automático
            $payment = new FinancePayment([
                'finance_entry_id' => $entry->id,
                'date' => $fecha,
                'amount' => $monto,
                'receipt_number' => 'Exprés',
            ]);
            $payment->user_id = $userId;
            $payment->save();
        }

        // 5. Retornar la vista de éxito
        return view('finanzas.quick', compact('tipo', 'concepto', 'monto', 'registradoPor'));
    }

    // ==========================================
    // MÉTODOS DEL PANEL (Vue/React)
    // ==========================================

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

        $entry = new FinanceEntry($data);
        // Si el frontend envía user_id, lo usa; si no, usa el usuario logueado
        $entry->user_id = $request->user_id ?: auth()->id(); 
        $entry->save();

        if ($request->anticipo > 0) {
            $payment = new FinancePayment([
                'finance_entry_id' => $entry->id,
                'date' => $request->fecha,
                'amount' => $request->anticipo,
                'receipt_number' => $request->numeroRecibo ?? 'S/N'
            ]);
            $payment->user_id = $request->user_id ?: auth()->id();
            $payment->save();
        }

        $usuarioNombre = User::find($entry->user_id)->name ?? 'Sistema';

        return response()->json([
            'success' => true, 
            'id' => $entry->id, 
            'registrado_por' => $usuarioNombre
        ]);
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

    public function storePayment(Request $request)
    {
        $payment = new FinancePayment([
            'finance_entry_id' => $request->entryId,
            'date' => $request->fecha,
            'amount' => $request->amount,
            'receipt_number' => $request->recibo
        ]);
        $payment->user_id = $request->user_id ?: auth()->id();
        $payment->save();

        // Actualizar el saldo
        $entry = FinanceEntry::find($request->entryId);
        $newAdvance = $entry->advance_payment + $request->amount;
        $newBalance = $entry->total_with_iva - $newAdvance;
        $status = ($newBalance <= 0) ? 'Completado' : $entry->status;
        
        $entry->update([
            'advance_payment' => $newAdvance,
            'balance' => $newBalance,
            'status' => $status
        ]);

        $usuarioNombre = User::find($payment->user_id)->name ?? 'Sistema';

        return response()->json([
            'success' => true, 
            'id' => $payment->id,
            'registrado_por' => $usuarioNombre
        ]);
    }

    public function storeExpense(Request $request)
    {
        $expense = new FinanceExpense([
            'date' => $request->fecha,
            'concept' => $request->concepto,
            'amount' => $request->monto,
            'is_invoiced' => $request->facturado
        ]);
        $expense->user_id = $request->user_id ?: auth()->id();
        $expense->save();

        $usuarioNombre = User::find($expense->user_id)->name ?? 'Sistema';

        return response()->json([
            'success' => true, 
            'id' => $expense->id,
            'registrado_por' => $usuarioNombre
        ]);
    }

    public function destroyExpense(FinanceExpense $financeExpense)
    {
        $financeExpense->delete();
        return response()->json(['success' => true]);
    }

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

    public function storeInvoice(Request $request)
    {
        $invoice = new FinanceInvoice([
            'date' => $request->fecha,
            'invoice_folio' => $request->folioFactura,
            'total_fees' => $request->honorariosTotal,
            'total_iva' => $request->ivaTotal,
            'grand_total' => $request->totalGeneral,
            'sat_payment' => $request->pagoSat,
            'released_balance' => $request->liberado,
            'liquidated_records_count' => $request->registrosLiquidacion
        ]);
        $invoice->user_id = auth()->id(); // Usualmente solo admins facturan
        $invoice->save();

        FinanceEntry::whereIn('id', $request->entryIds)->update([
            'is_declared' => true,
            'finance_invoice_id' => $invoice->id
        ]);

        return response()->json([
            'success' => true, 
            'id' => $invoice->id,
            'registrado_por' => auth()->user()->name ?? 'Sistema'
        ]);
    }
}