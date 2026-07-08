<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SparePartController extends Controller
{
    /**
     * Display a listing of spare parts.
     */
    public function index()
    {
        $spareParts = SparePart::with('equipment')->latest()->get();
        $equipment = Equipment::all();

        return view('admin.spare-parts.index', compact('spareParts', 'equipment'));
    }

    /**
     * Fetch all spare parts (AJAX).
     */
    public function fetch()
    {
        $spareParts = SparePart::with('equipment')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $spareParts
        ]);
    }

    /**
     * View a specific spare part (AJAX).
     */
    public function view($id)
    {
        $sparePart = SparePart::with('equipment')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $sparePart
        ]);
    }

    /**
     * Store a newly created spare part.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:spare_parts,sku',
            'category' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'warehouse' => 'nullable|string|max:255',
            'shelf' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'equipment_id' => 'nullable|exists:equipment,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sparePart = SparePart::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Spare part created successfully!',
            'data' => $sparePart->load('equipment')
        ]);
    }

    /**
     * Update the specified spare part.
     */
    public function update(Request $request, $id)
    {
        $sparePart = SparePart::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:spare_parts,sku,' . $id,
            'category' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'warehouse' => 'nullable|string|max:255',
            'shelf' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'equipment_id' => 'nullable|exists:equipment,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sparePart->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Spare part updated successfully!',
            'data' => $sparePart->load('equipment')
        ]);
    }

    /**
     * Remove the specified spare part.
     */
    public function destroy($id)
    {
        $sparePart = SparePart::findOrFail($id);
        $sparePart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Spare part deleted successfully!'
        ]);
    }
}
