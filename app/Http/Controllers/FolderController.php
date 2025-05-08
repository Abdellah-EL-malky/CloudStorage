<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $user = Auth::user();

        $folder = Folder::create([
            'name' => $request->input('name'),
            'user_id' => $user->id,
            'parent_id' => $request->input('parent_id'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'folder' => $folder
            ]);
        }

        $redirectPath = $request->input('parent_id')
            ? route('files.index', ['folder_id' => $request->input('parent_id')])
            : route('files.index');

        return redirect($redirectPath)->with('success', 'Dossier créé avec succès.');
    }

    public function show(Folder $folder)
    {
        $this->authorize('view', $folder);
        return redirect()->route('files.index', ['folder_id' => $folder->id]);
    }

    public function destroy(Folder $folder)
    {
        $this->authorize('delete', $folder);

        $user = Auth::user();
        $parentId = $folder->parent_id;

        foreach ($folder->files as $file) {
            Storage::disk('local')->delete($file->path);
            $user->storage_used -= $file->size;
            $file->delete();
        }

        foreach ($folder->children as $child) {
            $this->deleteFolder($child, $user);
        }

        $folder->delete();

        $user->storage_used = max(0, $user->storage_used);
        $user->save();

        return redirect()->route('files.index', ['folder_id' => $parentId])
            ->with('success', 'Dossier et son contenu supprimés avec succès.');
    }

    private function deleteFolder(Folder $folder, $user)
    {
        foreach ($folder->files as $file) {
            Storage::disk('local')->delete($file->path);
            $user->storage_used -= $file->size;
            $file->delete();
        }

        foreach ($folder->children as $child) {
            $this->deleteFolder($child, $user);
        }

        $folder->delete();
    }
}
