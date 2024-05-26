<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    
    public function index()
    {
        $quotes = QuoteResource::collection(Quote::all());

        return inertia('Quote/Index', compact('quotes'));
    }

    
    public function create()
    {
        return inertia('Quote/Create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'client' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:999999',
            'payment_conditions' => 'required|string',
        ]);

        Quote::create($request->all());

        return to_route('quotes.index');
    }

    
    public function show($quote_id)
    {   
        $quote = QuoteResource::make(Quote::find($quote_id));

        return inertia('Quote/Show', compact('quote'));
    }

    
    public function edit(Quote $quote)
    {
        return inertia('Quote/Edit', compact('quote'));
    }

    
    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'client' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:999999',
            'payment_conditions' => 'required|string',
        ]);

        $quote->update($request->all());

        return to_route('quotes.index');
    }

    
    public function destroy(Quote $quote)
    {
        //
    }


    public function massiveDelete(Request $request)
    {
        foreach ($request->quotes as $quote) {
            $quote = Quote::find($quote['id']);
            $quote?->delete();
        }

        return response()->json(['message' => 'cotizacion(es) eliminada(s)']);
    }


    public function updateStatus(Request $request, Quote $quote)
    {
        if ( $request->status == 'pendent' ) {
            $status = 'Esperando autorización del cliente';
        } else if ( $request->status == 'authorized' ) {
            $status = 'Autorizada';
        } else {
            $status = 'Rechazada';
        }

        $quote->update([
            'status' => $status
        ]);
        
        return response()->json(['item' => $status]);
    }
}
