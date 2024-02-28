<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{

    public function index()
    {
        // $batches = SubdivisionResource::collection(Subdivision::with('media')->get());

        // return $batches;
        return inertia('Subdivision/Index', compact('batches'));
    }


    public function create()
    {
        return inertia('Subdivision/Create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Batch $batch)
    {
        //
    }


    public function edit(Batch $batch)
    {
        //
    }


    public function update(Request $request, Batch $batch)
    {
        //
    }

    
    public function destroy(Batch $batch)
    {
        //
    }
}
