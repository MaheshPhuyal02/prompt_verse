<?php
// app/Http/Controllers/PromptController.php
namespace App\Http\Controllers;

use App\Models\Prompt;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PromptController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

//        return "got user {$user}";

        if ($user) {
            $purchasedPromptIds = Purchase::where('user_id', $user->id)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('prompt_id');
            return Prompt::whereNotIn('id', $purchasedPromptIds)
                ->get(['id', 'title', 'rating', 'price', 'image', 'category', 'popular']);
        }
        return Prompt::all(['id', 'title', 'rating', 'price', 'image', 'category', 'popular']);
    }


    public function show($id)
    {
        return Prompt::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'nullable|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'popular' => 'boolean',
        ]);

        return Prompt::create($data);
    }

    public function update(Request $request, $id)
    {
        $prompt = Prompt::findOrFail($id);

        $data = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'rating' => 'numeric',
            'price' => 'numeric',
            'image' => 'string',
            'category' => 'string|max:255',
            'popular' => 'boolean',
        ]);

        $prompt->update($data);

        return $prompt;
    }

    public function destroy($id)
    {
        $prompt = Prompt::findOrFail($id);
        $prompt->delete();

        return response()->json(['message' => 'Prompt deleted']);
    }

    public function allPrompts()
    {
        return Prompt::all(['id', 'title', 'rating', 'price', 'image', 'category', 'popular']);
    }
}
