<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Get all addresses for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $addresses = Auth::user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($address) {
                return [
                    'id' => $address->id,
                    'user_id' => $address->user_id,
                    'first_name' => $address->first_name,
                    'last_name' => $address->last_name,
                    'phone' => $address->phone,
                    'is_default' => $address->is_default,
                    'full_name' => $address->first_name . ' ' . $address->last_name,
                    'complete_address' => $address->full_name . ', ' . $address->phone,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    /**
     * Store a new address for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, remove default from other addresses
            if ($request->is_default) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }

            // If this is the first address, make it default
            if (Auth::user()->addresses()->count() === 0) {
                $request->merge(['is_default' => true]);
            }

            $address = Auth::user()->addresses()->create($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => [
                    'id' => $address->id,
                    'user_id' => $address->user_id,
                    'first_name' => $address->first_name,
                    'last_name' => $address->last_name,
                    'phone' => $address->phone,
                    'is_default' => $address->is_default,
                    'full_name' => $address->first_name . ' ' . $address->last_name,
                    'complete_address' => $address->full_name . ', ' . $address->phone,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing address.
     */
    public function update(Request $request, Address $address): JsonResponse
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, remove default from other addresses
            if ($request->is_default) {
                Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            }

            $address->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => [
                    'id' => $address->id,
                    'user_id' => $address->user_id,
                    'first_name' => $address->first_name,
                    'last_name' => $address->last_name,
                    'phone' => $address->phone,
                    'is_default' => $address->is_default,
                    'full_name' => $address->first_name . ' ' . $address->last_name,
                    'complete_address' => $address->full_name . ', ' . $address->phone,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an address.
     */
    public function destroy(Address $address): JsonResponse
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Don't allow deleting the last address
        if (Auth::user()->addresses()->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the last address'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $address->delete();

            // If we deleted the default address, make another address default
            if ($address->is_default) {
                Auth::user()->addresses()->first()->update(['is_default' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set an address as default.
     */
    public function setDefault(Address $address): JsonResponse
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Remove default from all addresses
            Auth::user()->addresses()->update(['is_default' => false]);

            // Set this address as default
            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update default address',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 