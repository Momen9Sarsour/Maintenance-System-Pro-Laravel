<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        $clients = User::where('role', 'client')
            ->withCount(['clientWorkOrders', 'invoices'])
            ->latest()
            ->get();

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Fetch all clients (AJAX).
     */
    public function fetch()
    {
        $clients = User::where('role', 'client')
            ->withCount(['clientWorkOrders', 'invoices'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    /**
     * View a specific client (AJAX).
     */
    public function view($id)
    {
        $client = User::where('role', 'client')
            ->withCount(['clientWorkOrders', 'invoices'])
            ->with(['clientWorkOrders' => function($query) {
                $query->latest()->limit(5);
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $client
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client created successfully!',
            'data' => $client
        ]);
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, $id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client updated successfully!',
            'data' => $client
        ]);
    }

    /**
     * Toggle client active status (AJAX).
     */
    public function toggleActive(Request $request, $id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $client->update([
            'is_active' => !$client->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => $client->is_active ? 'Client activated successfully!' : 'Client deactivated successfully!',
            'is_active' => $client->is_active
        ]);
    }

    /**
     * Remove the specified client.
     */
    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        // Check if client has work orders
        if ($client->clientWorkOrders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete client with existing work orders.'
            ], 422);
        }

        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully!'
        ]);
    }
}
