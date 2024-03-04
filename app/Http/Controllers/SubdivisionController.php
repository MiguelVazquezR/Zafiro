<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubdivisionResource;
use App\Models\Batch;
use App\Models\Subdivision;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
{
    
    public function index()
    {
        $subdivisions = SubdivisionResource::collection(Subdivision::with('media')->get());

        return inertia('Subdivision/Index', compact('subdivisions'));
    }

    public function create()
    {
        return inertia('Subdivision/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'batches_availables' => 'required|numeric|min:1|max:255',
            'address' => 'required|string',
            'maps_url' => 'nullable|string',
            'lowest_price' => 'required|numeric|min:0|max:9999999',
            'lowest_surface' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
            'planos' => 'nullable|array',
            'soon' => 'boolean',
        ]);

        $subdivision = Subdivision::create($request->all());

        // Lógica para guardar las imagenes
        $collections = ['cover1', 'cover2', 'cover3', 'cover4', 'cover5', 'cover6', 'cover7', 'cover8'];

        foreach ($collections as $collection) {
            $inputName = 'image_' . $collection;

            if ($request->hasFile($inputName)) {
                $subdivision->addMediaFromRequest($inputName)->toMediaCollection('images');
            }
        }

        // Lógica para guardar los planos
        if ($request->hasFile('planos')) {
            $subdivision->addMediaFromRequest('planos')->toMediaCollection('planos');
        }

        return to_route('subdivisions.index');
    }

   
    public function show($subdivision_id)
    {   
        $subdivision = SubdivisionResource::make(Subdivision::with(['media', 'batches.subdivision'])->find($subdivision_id));
        // $total_batches = Batch::where('subdivision_id', $subdivision_id)->count();

        return inertia('Subdivision/Show', compact('subdivision'));
    }

    
    public function edit($subdivision_id)
    {   
        $subdivision = Subdivision::with('media')->find($subdivision_id);

        return inertia('Subdivision/Edit', compact('subdivision'));
    }

    
    public function update(Request $request, Subdivision $subdivision)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'batches_availables' => 'required|numeric|min:1|max:255',
            'address' => 'required|string',
            'maps_url' => 'nullable|string',
            'lowest_price' => 'required|numeric|min:0|max:9999999',
            'lowest_surface' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
            'planos' => 'nullable|array',
            'soon' => 'boolean',
        ]);

        $subdivision->update($request->all());

        $images = ['cleared_cover1', 'cleared_cover2', 'cleared_cover3', 'cleared_cover4', 'cleared_cover5', 'cleared_cover6', 'cleared_cover7', 'cleared_cover8'];

        if (collect($images)->contains(fn($image) => $request->$image == true)) {
            $subdivision->clearMediaCollection('images');
        }

        return to_route('subdivisions.index');
    }


    public function updateWithMedia(Request $request, Subdivision $subdivision)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'batches_availables' => 'required|numeric|min:1|max:255',
            'address' => 'required|string',
            'maps_url' => 'nullable|string',
            'lowest_price' => 'required|numeric|min:0|max:9999999',
            'lowest_surface' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
            'planos' => 'nullable|array',
            'soon' => 'boolean',
        ]);

        $subdivision->update($request->all());

        // update images. Clear all then attach all
        $images = ['image_cover1', 'image_cover2', 'image_cover3', 'image_cover4', 'image_cover5', 'image_cover6', 'image_cover7', 'image_cover8'];

        if (collect($images)->contains(fn($image) => $request->hasFile($image))) {
            $subdivision->clearMediaCollection('images');
        }

         // Lógica para guardar las imagenes
         $collections = ['cover1', 'cover2', 'cover3', 'cover4', 'cover5', 'cover6', 'cover7', 'cover8'];

         foreach ($collections as $collection) {
             $inputName = 'image_' . $collection;
 
             if ($request->hasFile($inputName)) {
                 $subdivision->addMediaFromRequest($inputName)->toMediaCollection('images');
             }
         }

        // Lógica para guardar los planos
        if ($request->hasFile('planos')) {
            $subdivision->clearMediaCollection('planos');
            $subdivision->addMediaFromRequest('planos')->toMediaCollection('planos');
        }

        return to_route('subdivisions.index');
    }

    
    public function destroy(Subdivision $subdivision)
    {
        $subdivision->delete();
    }
}
