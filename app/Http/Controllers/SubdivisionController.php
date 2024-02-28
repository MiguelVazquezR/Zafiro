<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubdivisionResource;
use App\Models\Subdivision;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
{
    
    public function index()
    {
        $subdivisions = SubdivisionResource::collection(Subdivision::with('media')->get());

        // return $subdivisions;
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
            'description' => 'nullable|string|max:800',
            'amenities' => 'nullable|array',
        ]);

        $product = Subdivision::create($request->all());

        // Lógica para guardar las imagenes
        $collections = ['cover1', 'cover2', 'cover3', 'cover4', 'cover5', 'cover6', 'cover7', 'cover8'];

        foreach ($collections as $collection) {
            $inputName = 'image_' . $collection;

            if ($request->hasFile($inputName)) {
                $product->addMediaFromRequest($inputName)->toMediaCollection($collection);
            }
        }

        return to_route('subdivisions.index');
    }

   
    public function show(Subdivision $subdivision)
    {
        //
    }

    
    public function edit(Subdivision $subdivision)
    {
        //
    }

    
    public function update(Request $request, Subdivision $subdivision)
    {
        //
    }

    
    public function destroy(Subdivision $subdivision)
    {
        //
    }
}
