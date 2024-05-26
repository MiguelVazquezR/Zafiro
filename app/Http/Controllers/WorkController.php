<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkResource;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index()
    {
        $works = WorkResource::collection(Work::latest()->get());

        return inertia('Work/Index', compact('works'));
    }

    public function create()
    {
        return inertia('Work/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'town' => 'required|string|max:255',
            'ejido' => 'required|string|max:255',
            'parcel_number' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payments' => 'nullable|array',
            'area' => 'required|numeric|min:0',
            'work_type' => 'required|string|max:255',
            'start_date' => 'required|date',
        ]);

        Work::create($validated);

        return to_route('works.index');
    }

    public function show(Work $work)
    {
        return inertia('Work/Show');
    }

    public function edit(Work $work)
    {
        $date = $work->start_date->toDateString();
        return inertia('Work/Edit', compact('work', 'date'));
    }

    public function update(Request $request, Work $work)
    {
        $validated = $request->validate([
            'town' => 'required|string|max:255',
            'ejido' => 'required|string|max:255',
            'parcel_number' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payments' => 'nullable|array',
            'area' => 'required|numeric|min:0',
            'work_type' => 'required|string|max:255',
            'start_date' => 'required|date',
        ]);

        $work->update($validated);

        return to_route('works.index');
    }

    public function destroy(Work $work)
    {
        //
    }

    public function massiveDelete(Request $request)
    {
        Work::whereIn('id', $request->works)->delete();

        return response()->json(['message' => 'trabajo(s) eliminado(s)']);
    }

    public function showInvoice($work_id)
    {
        $work = WorkResource::make(Work::find($work_id));
        $today = today()->isoFormat('DD MMMM, YYYY');

        return inertia('Work/Invoice', compact('work', 'today'));
    }

    public function massiveStore(Request $request)
    {
        foreach ($request->works as $item) {
            Work::create($item);
        }

        return response()->json(['message' => 'Se sincronizaron los datos']);
    }
}
