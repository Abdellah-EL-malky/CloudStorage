<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->files();

        if ($request->has('folder_id')) {
            $query->where('folder_id', $request->input('folder_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $files = $query->paginate(12);

        $folders = null;
        $currentFolder = null;

        if ($request->has('folder_id')) {
            $currentFolder = Folder::findOrFail($request->input('folder_id'));
            $folders = Folder::where('parent_id', $currentFolder->id)
                ->where('user_id', $user->id)
                ->orderBy('name')
                ->get();
        } else {
            $folders = Folder::where('parent_id', null)
                ->where('user_id', $user->id)
                ->orderBy('name')
                ->get();
        }

        $storageUsed = $user->storage_used;
        $storageLimit = $user->storage_limit;
        $storagePercentage = $storageLimit > 0
            ? round(($storageUsed / $storageLimit) * 100, 2)
            : 0;

        return view('files.index', compact(
            'files',
            'folders',
            'currentFolder',
            'storageUsed',
            'storageLimit',
            'storagePercentage'
        ));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $user = Auth::user();

        $fileSize = $request->file('file')->getSize();
        if ($user->storage_used + $fileSize > $user->storage_limit) {
            return back()->with('error', 'Espace de stockage insuffisant. Veuillez libérer de l\'espace ou passer à un forfait supérieur.');
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        $storagePath = 'files/' . $user->id . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        Storage::disk('local')->put($storagePath, file_get_contents($file));

        $newFile = File::create([
            'name' => $fileName,
            'path' => $storagePath,
            'mime_type' => $mimeType,
            'size' => $fileSize,
            'user_id' => $user->id,
            'folder_id' => $request->input('folder_id'),
        ]);

        $user->storage_used += $fileSize;
        $user->save();

        return back()->with('success', 'Fichier téléversé avec succès.');
    }


    public function download(File $file)
    {
        $this->authorize('view', $file);

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        $user = $file->user;
        $size = $file->size;
        $path = $file->path;

        Storage::disk('local')->delete($path);

        $file->delete();

        $user->storage_used -= $size;
        $user->storage_used = max(0, $user->storage_used);
        $user->save();

        return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
    }
}
