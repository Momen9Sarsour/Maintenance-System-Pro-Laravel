<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment.
     */
    public function index()
    {
        $equipment = Equipment::with('assignedTechnician')->latest()->get();
        $technicians = User::where('role', 'technician')->get();

        return view('admin.equipment.index', compact('equipment', 'technicians'));
    }

    /**
     * Fetch all equipment (AJAX).
     */
    public function fetch()
    {
        $equipment = Equipment::with('assignedTechnician')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    /**
     * View a specific equipment (AJAX).
     */
    public function view($id)
    {
        $equipment = Equipment::with('assignedTechnician')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'required|string|unique:equipment,serial_number',
            'manufacturer' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'status' => 'required|in:operational,maintenance,out_of_service,retired',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:installation_date',
            'description' => 'nullable|string',
            'assigned_technician_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $equipment = Equipment::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Equipment created successfully!',
            'data' => $equipment->load('assignedTechnician')
        ]);
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'required|string|unique:equipment,serial_number,' . $id,
            'manufacturer' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'status' => 'required|in:operational,maintenance,out_of_service,retired',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:installation_date',
            'description' => 'nullable|string',
            'assigned_technician_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $equipment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Equipment updated successfully!',
            'data' => $equipment->load('assignedTechnician')
        ]);
    }

    /**
     * Toggle equipment status (AJAX).
     */
    public function toggleStatus(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:operational,maintenance,out_of_service,retired'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status'
            ], 422);
        }

        $equipment->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'data' => $equipment->load('assignedTechnician')
        ]);
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Equipment deleted successfully!'
        ]);
    }
}
