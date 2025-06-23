<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class FileController extends Controller
{
    /**
     * Get all files for admin panel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $files = Storage::disk('public')->files('uploads');
        $fileList = [];

        foreach ($files as $file) {
            $fileId = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $size = Storage::disk('public')->size($file);
            
            $fileList[] = [
                'id' => $fileId,
                'name' => $fileId . '.' . $extension,
                'type' => $extension,
                'size' => $size,
                'url' => Storage::disk('public')->url($file)
            ];
        }

        return response()->json($fileList);
    }

    /**
     * Save an uploaded file from the request and return its URL.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFile(Request $request)
    {
        // Validate file is present in the request
        $request->validate([
            'file' => 'required|file|max:100240', // max 10MB
        ]);

        $file = $request->file('file');
        $fileId = (string) Str::uuid();
        $extension = $file->getClientOriginalExtension();
        $storedPath = "uploads/{$fileId}.{$extension}";

        // Store file in the public disk (storage/app/public/uploads)
        Storage::disk('public')->putFileAs('uploads', $file, "{$fileId}.{$extension}");

        $url = Storage::disk('public')->url($storedPath);

        return response()->json([
            'file_id' => $fileId,
            'url' => $url,
        ]);
    }

    /**
     * Retrieve a file by file id.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fileId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function getFile(Request $request, $fileId)
    {
        // Find the file in the uploads directory
        $files = Storage::disk('public')->files('uploads');
        $filename = null;

        foreach ($files as $file) {
            if (Str::startsWith(basename($file), $fileId)) {
                $filename = $file;
                break;
            }
        }

        if (!$filename || !Storage::disk('public')->exists($filename)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download(storage_path('app/public/' . $filename));
    }

    /**
     * Delete a file by file id.
     *
     * @param string $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($fileId)
    {
        // Find the file in the uploads directory
        $files = Storage::disk('public')->files('uploads');
        $filename = null;

        foreach ($files as $file) {
            if (Str::startsWith(basename($file), $fileId)) {
                $filename = $file;
                break;
            }
        }

        if (!$filename || !Storage::disk('public')->exists($filename)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Delete the file
        Storage::disk('public')->delete($filename);

        return response()->json(['message' => 'File deleted successfully']);
    }
}
