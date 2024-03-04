<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\Subdivision;
use Illuminate\Http\Request;

class BatchController extends Controller
{

    public function index()
    {
        $batches = BatchResource::collection(Batch::with(['media', 'subdivision'])->get());

        // return $batches;
        return inertia('Batch/Index', compact('batches'));
    }


    public function create()
    {
        $subdivisions = Subdivision::all(['id', 'name']);

        return inertia('Batch/Create', compact('subdivisions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'subdivision_id' => 'nullable',
            'address' => 'nullable|string',
            'maps_url' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:9999999',
            'surface' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
            'planos' => 'nullable|array',
        ]);

        $batch = Batch::create($request->all());

        // Lógica para guardar las imagenes
        $collections = ['cover1', 'cover2', 'cover3', 'cover4', 'cover5', 'cover6', 'cover7', 'cover8'];

        foreach ($collections as $collection) {
            $inputName = 'image_' . $collection;

            if ($request->hasFile($inputName)) {
                $batch->addMediaFromRequest($inputName)->toMediaCollection('images');
            }
        }

        // Lógica para guardar los planos
        if ($request->hasFile('planos')) {
            $batch->addMediaFromRequest('planos')->toMediaCollection('planos');
        }

        return to_route('batches.index');
    }


    public function show($batch_id)
    {
        $batch = BatchResource::make(Batch::with(['media', 'subdivision'])->find($batch_id));

        // return $batch;
        return inertia('Batch/Show', compact('batch'));
    }


    public function edit($batch_id)
    {
        $batch = Batch::with(['media'])->find($batch_id);
        $subdivisions = Subdivision::all(['id', 'name']);

        return inertia('Batch/Edit', compact('subdivisions', 'batch'));
    }


    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'subdivision_id' => 'nullable',
            'address' => 'nullable|string',
            'maps_url' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:9999999',
            'surface' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
            'planos' => 'nullable|array',
        ]);

        $batch->update($request->all());

        $images = ['cleared_cover1', 'cleared_cover2', 'cleared_cover3', 'cleared_cover4', 'cleared_cover5', 'cleared_cover6', 'cleared_cover7', 'cleared_cover8'];

        if (collect($images)->contains(fn($image) => $request->$image == true)) {
            $batch->clearMediaCollection('images');
        }

        return to_route('batches.index');
    }


    public function updateWithMedia(Request $request, Batch $batch)
    {
        $request->validate([
        'name' => 'required|string|max:100',
        'subdivision_id' => 'nullable',
        'address' => 'nullable|string',
        'maps_url' => 'nullable|string',
        'price' => 'required|numeric|min:0|max:9999999',
        'surface' => 'required|numeric|min:0|max:9999999',
        'description' => 'nullable|string|max:800',
        'amenities' => 'nullable|array',
        'planos' => 'nullable|array',
        ]);

        $batch->update($request->all());

        // update images. Clear all then attach all
        $images = ['image_cover1', 'image_cover2', 'image_cover3', 'image_cover4', 'image_cover5', 'image_cover6', 'image_cover7', 'image_cover8'];

        if (collect($images)->contains(fn($image) => $request->hasFile($image))) {
            $batch->clearMediaCollection('images');
        }

         // Lógica para guardar las imagenes
         $collections = ['cover1', 'cover2', 'cover3', 'cover4', 'cover5', 'cover6', 'cover7', 'cover8'];

         foreach ($collections as $collection) {
             $inputName = 'image_' . $collection;
 
             if ($request->hasFile($inputName)) {
                 $batch->addMediaFromRequest($inputName)->toMediaCollection('images');
             }
         }

        // Lógica para guardar los planos
        if ($request->hasFile('planos')) {
            $batch->clearMediaCollection('planos');
            $batch->addMediaFromRequest('planos')->toMediaCollection('planos');
        }

        return to_route('batches.index');
    }

    
    public function destroy(Batch $batch)
    {
        $batch->delete();
    }


    public function getItemsByPage($currentPage)
    {
        $offset = $currentPage * 2;
        $batches = BatchResource::collection(Batch::with(['media', 'subdivision'])->skip($offset)
            ->take(2)
            ->get());

        return response()->json(['items' => $batches]);
    }
}
