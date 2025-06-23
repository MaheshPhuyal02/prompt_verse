<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;

class ProfileController extends Controller
{
    // Require auth:sanctum middleware in routes

    /**
     * Get the authenticated user's profile.
     */
    public function get(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }

    /**
     * Delete the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }

    /**
     * Get the authenticated user's purchases (purchase history).
     */
    public function purchases(Request $request)
    {
        $user = $request->user();
        $purchases = \App\Models\Purchase::where('user_id', $user->id)
            ->orderBy('purchased_at', $request->get('sort', 'desc'))
            ->get();

        $result = $purchases->map(function ($purchase) {
            $prompt = \App\Models\Prompt::find($purchase->prompt_id);
            if (!$prompt) return null;
            return [
                'id' => $purchase->id,
                'prompt_id' => $prompt->id,
                'title' => $prompt->title,
                'description' => $prompt->description,
                'category' => $prompt->category,
                'image' => $prompt->image,
                'price' => $purchase->price_at_time,
                'purchaseDate' => $purchase->purchased_at ? $purchase->purchased_at->format('M d, Y') : null,
                'status' => $purchase->status,
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->updated_at,
            ]; 
        })->filter();

        return response()->json([
            'success' => true,
            'data' => $result->values(),
        ]);
    }
}
