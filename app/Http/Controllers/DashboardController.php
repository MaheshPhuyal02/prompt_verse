<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalUsers = User::count();
        $activePrompts = Prompt::count();
        $filesUploaded = count(Storage::disk('public')->files('uploads'));
        $totalRevenue = Purchase::where('status', 'completed')->sum('price_at_time');

        return response()->json([
            'total_users' => $totalUsers,
            'active_prompts' => $activePrompts,
            'files_uploaded' => $filesUploaded,
            'total_revenue' => $totalRevenue,
        ]);
    }
}
