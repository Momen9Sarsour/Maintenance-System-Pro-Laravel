<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of work orders.
     */
    public function index()
    {
        $workOrders = WorkOrder::with(['client', 'assignedTo', 'equipment', 'createdBy', 'invoice'])
            ->latest()
            ->get();

        $clients = User::where('role', 'client')->get();
        $technicians = User::where('role', 'technician')->get();
        $equipment = Equipment::all();

        return view('admin.work-orders.index', compact(
            'workOrders',
            'clients',
            'technicians',
            'equipment'
        ));
    }

    /**
     * Fetch all work orders (AJAX).
     */
    public function fetch()
    {
        $workOrders = WorkOrder::with(['client', 'assignedTo', 'equipment', 'createdBy', 'invoice'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $workOrders
        ]);
    }

    /**
     * View a specific work order (AJAX).
     */
    public function view($id)
    {
        $workOrder = WorkOrder::with(['client', 'assignedTo', 'equipment', 'createdBy', 'invoice'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $workOrder
        ]);
    }

    /**
     * Store a newly created work order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'required|date|after_or_equal:today',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $workOrder = WorkOrder::create([
            'title' => $request->title,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'assigned_to' => $request->assigned_to,
            'equipment_id' => $request->equipment_id,
            'priority' => $request->priority,
            'status' => 'pending',
            'due_date' => $request->due_date,
            'price' => $request->price,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work order created successfully!',
            'data' => $workOrder
        ]);
    }

    /**
     * Update the specified work order.
     */
    public function update(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'required|date|after_or_equal:today',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $workOrder->update([
            'title' => $request->title,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'assigned_to' => $request->assigned_to,
            'equipment_id' => $request->equipment_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work order updated successfully!',
            'data' => $workOrder
        ]);
    }

    /**
     * Toggle status (AJAX).
     */
    public function toggleStatus(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status'
            ], 422);
        }

        $workOrder->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'data' => $workOrder
        ]);
    }

    /**
     * Toggle priority (AJAX).
     */
    public function togglePriority(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'priority' => 'required|in:low,medium,high,critical'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid priority'
            ], 422);
        }

        $workOrder->update([
            'priority' => $request->priority
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Priority updated successfully!',
            'data' => $workOrder
        ]);
    }

    /**
     * Remove the specified work order.
     */
    public function destroy($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work order deleted successfully!'
        ]);
    }
}
