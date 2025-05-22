<?php
// app/Http/Controllers/PromptController.php
namespace App\Http\Controllers;

use App\Models\Prompt;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    public function index()
    {
        return Prompt::all();
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
}
