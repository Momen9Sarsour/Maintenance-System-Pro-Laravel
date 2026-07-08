<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    /**
     * Display a listing of technicians.
     */
    public function index()
    {
        $technicians = User::where('role', 'technician')
            ->with('technicianProfile')
            ->latest()
            ->get();

        return view('admin.technicians.index', compact('technicians'));
    }

    /**
     * Fetch all technicians (AJAX).
     */
    public function fetch()
    {
        $technicians = User::where('role', 'technician')
            ->with('technicianProfile')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $technicians
        ]);
    }

    /**
     * View a specific technician (AJAX).
     */
    public function view($id)
    {
        $technician = User::where('role', 'technician')
            ->with('technicianProfile')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $technician
        ]);
    }

    /**
     * Store a newly created technician.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:available,busy,offline',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'technician',
            'is_active' => true,
        ]);

        // Create Technician Profile
        Technician::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'status' => $request->status,
            'rating' => 0,
            'tasks_completed' => 0,
            'first_time_fix_rate' => 0,
            'on_time_rate' => 0,
            'avg_repair_time' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Technician created successfully!',
            'data' => $user->load('technicianProfile')
        ]);
    }

    /**
     * Update the specified technician.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'technician')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:available,busy,offline',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update User
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);

        // Update Technician Profile
        if ($user->technicianProfile) {
            $user->technicianProfile->update([
                'specialization' => $request->specialization,
                'status' => $request->status,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Technician updated successfully!',
            'data' => $user->load('technicianProfile')
        ]);
    }

    /**
     * Toggle technician status (AJAX).
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::where('role', 'technician')->with('technicianProfile')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:available,busy,offline'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status'
            ], 422);
        }

        if ($user->technicianProfile) {
            $user->technicianProfile->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'data' => $user->load('technicianProfile')
        ]);
    }

    /**
     * Toggle technician active status (AJAX).
     */
    public function toggleActive(Request $request, $id)
    {
        $user = User::where('role', 'technician')->findOrFail($id);

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'Technician activated successfully!' : 'Technician deactivated successfully!',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Remove the specified technician.
     */
    public function destroy($id)
    {
        $user = User::where('role', 'technician')->findOrFail($id);

        // Delete technician profile first
        if ($user->technicianProfile) {
            $user->technicianProfile->delete();
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Technician deleted successfully!'
        ]);
    }
}
